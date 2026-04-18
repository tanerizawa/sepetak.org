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
use Illuminate\Support\Facades\Log;
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

        $systemPrompt = $this->composer->systemPrompt($pool);
        $userPrompt = $this->composer->buildUserPrompt($pool, $topic, $recentTitles);

        $log = ArticleGenerationLog::query()->create([
            'article_topic_id' => $topic->getKey(),
            'article_pool_id' => $pool?->getKey(),
            'status' => 'generating',
            'ai_provider' => $this->provider->name(),
            'ai_model' => (string) config('article-generator.openrouter.model', ''),
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

            if (! $this->validator->validate($content, $profile->value)) {
                $log->forceFill([
                    'status' => 'rejected',
                    'error_message' => 'Validator gagal: '.$this->validator->allIssuesAsString(),
                ])->save();
                Log::warning('ArticleGenerator: content rejected by validator', [
                    'topic_id' => $topic->getKey(),
                    'issues' => $this->validator->allIssuesAsString(),
                ]);

                return null;
            }

            $post = $this->persistPost($content, $topic, $pool, $log, $profile);

            $log->forceFill([
                'status' => 'completed',
                'post_id' => $post->getKey(),
            ])->save();

            $topic->incrementUsage();

            if (($pool?->auto_publish ?? false) === true && $post->status !== 'published') {
                $post->forceFill([
                    'status' => 'published',
                    'published_at' => $post->published_at ?? now(),
                ])->save();
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
        return DB::transaction(function () use ($content, $topic, $pool, $log, $profile): Post {
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
        if (class_exists(\League\CommonMark\CommonMarkConverter::class)) {
            return (new \League\CommonMark\CommonMarkConverter(['html_input' => 'strip']))->convert($markdown)->getContent();
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
