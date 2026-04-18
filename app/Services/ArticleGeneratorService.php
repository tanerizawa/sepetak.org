<?php

namespace App\Services;

use App\Contracts\ArticleAiProvider;
use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Post;
use App\Services\ArticleGeneration\ContentProfile;
use App\Services\ArticleGeneration\PromptComposer;
use App\Support\PostSlug;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;
use Mews\Purifier\Facades\Purifier;

/**
 * Orkestrator generasi artikel otomatis.
 *
 * Alur:
 *   ArticleAiProvider → markdown → ResponseParser → ArticleQualityValidator → Post (dengan log)
 */
class ArticleGeneratorService
{
    public function __construct(
        protected ArticleAiProvider $provider,
        protected PromptComposer $composer,
        protected ResponseParser $parser,
        protected ArticleQualityValidator $validator,
        protected ArticleImageService $images,
        protected ArticleReadabilityScorer $readability,
        protected ArticlePlagiarismChecker $plagiarism,
    ) {}

    /**
     * @param  ArticleTopic|null  $topic  Topik target; bila null, kemungkinan pemanggil akan memakai TopicPicker terlebih dahulu.
     * @param  string  $triggeredBy  'scheduler' | 'manual' | 'retry'
     */
    public function generate(ArticleTopic $topic, ?ArticlePool $pool = null, string $triggeredBy = 'scheduler'): ?Post
    {
        $startedAt = microtime(true);

        $profile = ContentProfile::forArticleGeneration($pool, $topic);
        $recentTitles = $profile === ContentProfile::MemberPractical
            ? $this->recentAutoPostTitles()
            : [];

        $systemPrompt = $this->composer->systemPrompt($pool, $topic);
        $promptMeta = $this->composer->buildUserPromptWithMeta($pool, $topic, $recentTitles);
        $userPrompt = $promptMeta['prompt'];
        $promptVariant = $promptMeta['variant'];

        $log = ArticleGenerationLog::query()->create([
            'article_topic_id' => $topic->getKey(),
            'article_pool_id' => $pool?->getKey(),
            'content_profile' => $profile->value,
            'status' => 'generating',
            'ai_provider' => $this->provider->name(),
            'ai_model' => (string) config('article-generator.openrouter.model', ''),
            'prompt_variant' => $promptVariant,
            'prompt_used' => $userPrompt,
            'triggered_by' => $triggeredBy,
        ]);

        try {
            $response = $this->provider->generate($systemPrompt, $userPrompt);

            $log->forceFill([
                'ai_model' => $response->model,
                'raw_response' => $response->content,
                'tokens_used' => $response->tokensUsed,
                'generation_time_ms' => (int) ((microtime(true) - $startedAt) * 1000),
            ])->save();

            $content = $response->content;
            $wordCount = $this->parser->countWords($content);
            $readabilityScore = $this->readability->scoreMarkdown($content);

            $log->forceFill([
                'word_count' => $wordCount,
                'readability_score' => $readabilityScore,
            ])->save();

            if ((bool) config('article-generator.quality.readability.enabled', true)) {
                $minReadability = (int) config('article-generator.quality.readability.min_score', 0);
                if ($minReadability > 0 && $readabilityScore < $minReadability) {
                    $log->forceFill([
                        'status' => 'rejected',
                        'error_message' => "Readability score terlalu rendah ({$readabilityScore} < {$minReadability}).",
                    ])->save();

                    Log::warning('ArticleGenerator: content rejected by readability gate', [
                        'topic_id' => $topic->getKey(),
                        'readability_score' => $readabilityScore,
                        'min_score' => $minReadability,
                    ]);

                    try {
                        Log::channel('article_metrics')->info('generation_completed', [
                            'status' => 'rejected',
                            'topic_id' => $topic->getKey(),
                            'pool_id' => $pool?->getKey(),
                            'log_id' => $log->getKey(),
                            'duration_ms' => $log->generation_time_ms,
                            'tokens_used' => $log->tokens_used,
                            'word_count' => $wordCount,
                            'content_profile' => $profile->value,
                            'prompt_variant' => $promptVariant,
                            'readability_score' => $readabilityScore,
                            'quality_passed' => false,
                        ]);
                    } catch (\Throwable) {
                    }

                    return null;
                }
            }

            if (! $this->validator->validate($content, $profile->value)) {
                $log->forceFill([
                    'status' => 'rejected',
                    'error_message' => 'Validator gagal: ' . $this->validator->allIssuesAsString(),
                ])->save();
                Log::warning('ArticleGenerator: content rejected by validator', [
                    'topic_id' => $topic->getKey(),
                    'issues' => $this->validator->allIssuesAsString(),
                ]);

                try {
                    Log::channel('article_metrics')->info('generation_completed', [
                        'status' => 'rejected',
                        'topic_id' => $topic->getKey(),
                        'pool_id' => $pool?->getKey(),
                        'log_id' => $log->getKey(),
                        'duration_ms' => $log->generation_time_ms,
                        'tokens_used' => $log->tokens_used,
                        'word_count' => $wordCount,
                        'content_profile' => $profile->value,
                        'prompt_variant' => $promptVariant,
                        'readability_score' => $readabilityScore,
                        'quality_passed' => false,
                    ]);
                } catch (\Throwable) {
                }

                return null;
            }

            $plagiarismResult = $this->plagiarism->checkMarkdown($content);
            $plagiarismScore = (float) $plagiarismResult->maxSimilarity;
            $log->forceFill([
                'plagiarism_score' => $plagiarismScore,
                'plagiarism_matched_post_id' => $plagiarismResult->matchedPostId,
            ])->save();

            $maxSimilarity = (float) config('article-generator.quality.plagiarism.max_similarity', 0.35);
            if ($plagiarismResult->isTooSimilar($maxSimilarity)) {
                $matched = $plagiarismResult->matchedPostId ? " (post_id={$plagiarismResult->matchedPostId})" : '';
                $log->forceFill([
                    'status' => 'rejected',
                    'error_message' => "Plagiarisme terdeteksi: similarity={$plagiarismScore}{$matched}.",
                ])->save();

                Log::warning('ArticleGenerator: content rejected by plagiarism gate', [
                    'topic_id' => $topic->getKey(),
                    'plagiarism_score' => $plagiarismScore,
                    'matched_post_id' => $plagiarismResult->matchedPostId,
                ]);

                try {
                    Log::channel('article_metrics')->info('generation_completed', [
                        'status' => 'rejected',
                        'topic_id' => $topic->getKey(),
                        'pool_id' => $pool?->getKey(),
                        'log_id' => $log->getKey(),
                        'duration_ms' => $log->generation_time_ms,
                        'tokens_used' => $log->tokens_used,
                        'word_count' => $wordCount,
                        'content_profile' => $profile->value,
                        'prompt_variant' => $promptVariant,
                        'readability_score' => $readabilityScore,
                        'plagiarism_score' => $plagiarismScore,
                        'quality_passed' => false,
                    ]);
                } catch (\Throwable) {
                }

                return null;
            }

            $post = $this->persistPost($content, $topic, $pool, $log, $profile);

            $log->forceFill([
                'status' => 'completed',
                'post_id' => $post->getKey(),
            ])->save();

            $topic->incrementUsage();

            $cooldownHours = (int) config('article-generator.topic_cooldown_hours', 0);
            if ($cooldownHours > 0) {
                Cache::put("ai_article_topic:cooldown:{$topic->getKey()}", 1, now()->addHours($cooldownHours));
            }

            if (($pool?->auto_publish ?? false) === true && $post->status !== 'published') {
                $post->forceFill([
                    'status' => 'published',
                    'published_at' => $post->published_at ?? now(),
                ])->save();
            }

            try {
                Log::channel('article_metrics')->info('generation_completed', [
                    'status' => 'completed',
                    'topic_id' => $topic->getKey(),
                    'pool_id' => $pool?->getKey(),
                    'log_id' => $log->getKey(),
                    'post_id' => $post->getKey(),
                    'duration_ms' => $log->generation_time_ms,
                    'tokens_used' => $log->tokens_used,
                    'word_count' => $wordCount,
                    'content_profile' => $profile->value,
                    'prompt_variant' => $promptVariant,
                    'readability_score' => $readabilityScore,
                    'plagiarism_score' => $plagiarismScore ?? null,
                    'quality_passed' => true,
                ]);
            } catch (\Throwable) {
            }

            return $post;
        } catch (\Throwable $e) {
            $log->forceFill([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'generation_time_ms' => (int) ((microtime(true) - $startedAt) * 1000),
            ])->save();

            Log::error('ArticleGenerator: generation failed', [
                'topic_id' => $topic->getKey(),
                'error' => $e->getMessage(),
            ]);

            try {
                Log::channel('article_metrics')->error('generation_failed', [
                    'topic_id' => $topic->getKey(),
                    'pool_id' => $pool?->getKey(),
                    'log_id' => $log->getKey(),
                    'duration_ms' => $log->generation_time_ms,
                    'content_profile' => $profile->value,
                    'prompt_variant' => $promptVariant,
                    'error' => $e->getMessage(),
                ]);
            } catch (\Throwable) {
            }

            throw $e;
        }
    }

    /**
     * Persist Markdown content as a Post, inside a DB transaction.
     */
    protected function persistPost(
        string $content,
        ArticleTopic $topic,
        ?ArticlePool $pool,
        ArticleGenerationLog $log,
        ContentProfile $profile,
    ): Post {
        return DB::transaction(function () use ($content, $topic, $log, $profile): Post {
            $title = $this->parser->parseTitle($content) ?: $topic->title;
            $slug = PostSlug::uniqueFromTitle($title);

            $bodyHtml = Purifier::clean($this->markdownToHtml($content), 'filament_rich_html');

            $post = new Post;
            $post->forceFill([
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $this->parser->parseAbstract($content),
                'body' => $bodyHtml,
                'status' => config('article-generator.defaults.status', 'draft'),
                'published_at' => null,
                'author_id' => config('article-generator.defaults.author_user_id'),
                'source_type' => 'auto_generated',
                'article_topic_id' => $topic->getKey(),
                'generation_log_id' => $log->getKey(),
                'ai_disclosure' => true,
            ]);

            if ($category = $this->resolveCategoryByType($topic->article_type, $profile)) {
                // Category assignment deferred until after save so pivot exists.
            }

            $post->save();

            if ($category ?? null) {
                $post->categories()->syncWithoutDetaching([$category->getKey()]);
            }

            return $post;
        });
    }

    protected function markdownToHtml(string $markdown): string
    {
        if (class_exists(CommonMarkConverter::class)) {
            return (new CommonMarkConverter(['html_input' => 'strip']))->convert($markdown)->getContent();
        }

        // Minimal fallback — Filament RichEditor is HTML-first anyway.
        return nl2br(e($markdown));
    }

    protected function resolveCategoryByType(string $articleType, ContentProfile $profile): ?Category
    {
        if ($profile === ContentProfile::MemberPractical) {
            $slugs = (array) config('article-generator.member_practical_category_slugs', ['panduan-tips-anggota']);
            foreach ($slugs as $slug) {
                if ($cat = Category::query()->where('slug', $slug)->first()) {
                    return $cat;
                }
            }
        }

        return Category::query()->where('slug', 'artikel')->orWhere('slug', 'umum')->first();
    }

    /**
     * @return list<string>
     */
    protected function recentAutoPostTitles(): array
    {
        $days = (int) config('article-generator.member_practical_prompt.recent_title_lookback_days', 14);
        $max = (int) config('article-generator.member_practical_prompt.recent_title_max', 18);

        return Post::query()
            ->where('source_type', 'auto_generated')
            ->where('created_at', '>=', CarbonImmutable::now()->subDays(max(1, $days)))
            ->orderByDesc('created_at')
            ->limit(max(1, $max))
            ->pluck('title')
            ->all();
    }
}
