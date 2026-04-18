<?php

namespace App\Console\Commands;

use App\Jobs\GenerateArticleJob;
use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneratorService;
use App\Services\TopicPicker;
use Illuminate\Console\Command;

class GenerateScheduledArticles extends Command
{
    protected $signature = 'articles:generate
        {--pool= : ID atau slug pool spesifik}
        {--topic= : ID topik spesifik (bypass pool)}
        {--force : Abaikan jadwal, generate sekarang}
        {--sync : Jalankan langsung tanpa queue}';

    protected $description = 'Generate artikel otomatis berdasarkan pool terjadwal';

    public function handle(TopicPicker $picker): int
    {
        if (! config('article-generator.enabled', false)) {
            $this->warn('Article generator is disabled. Set ARTICLE_GENERATOR_ENABLED=true in .env');

            return self::SUCCESS;
        }

        // Rate limit check
        $todayCount = ArticleGenerationLog::whereDate('created_at', today())
            ->whereIn('status', ['queued', 'generating', 'completed'])
            ->count();
        $maxPerDay = config('article-generator.limits.max_per_day', 5);

        if ($todayCount >= $maxPerDay && ! $this->option('force')) {
            $this->info("Daily limit reached ({$todayCount}/{$maxPerDay}). Use --force to override.");

            return self::SUCCESS;
        }

        // Single topic mode
        if ($topicId = $this->option('topic')) {
            return $this->generateForTopic((int) $topicId);
        }

        // Specific pool mode
        if ($poolRef = $this->option('pool')) {
            $pool = is_numeric($poolRef)
                ? ArticlePool::find($poolRef)
                : ArticlePool::where('slug', $poolRef)->first();

            if (! $pool) {
                $this->error("Pool not found: {$poolRef}");

                return self::FAILURE;
            }

            return $this->generateForPool($pool, $picker);
        }

        // Scheduled mode: check all active pools
        $now = now();
        $pools = ArticlePool::where('is_active', true)->get();
        $dispatched = 0;

        foreach ($pools as $pool) {
            if (! $this->option('force') && ! $pool->isDueAt($now)) {
                continue;
            }

            $count = $this->generateForPool($pool, $picker);
            if ($count === self::SUCCESS) {
                $dispatched++;
            }
        }

        $this->info("Pools processed: {$dispatched}");

        return self::SUCCESS;
    }

    protected function generateForPool(ArticlePool $pool, TopicPicker $picker): int
    {
        $this->info("Processing pool: {$pool->name}");

        $count = $pool->articles_per_run;
        $dispatched = 0;

        for ($i = 0; $i < $count; $i++) {
            $topic = $picker->pick($pool);

            if (! $topic) {
                $this->warn("No available topics in pool: {$pool->name}");
                break;
            }

            $this->info("  → Topic: {$topic->title}");

            if ($this->option('sync')) {
                $service = app(ArticleGeneratorService::class);
                $post = $service->generate($topic, $pool, 'manual');
                if ($post) {
                    $this->info("  ✓ Created post #{$post->id}: {$post->title}");
                } else {
                    $this->error($this->syncGenerationFailureMessage($pool, $topic));
                }
            } else {
                GenerateArticleJob::dispatch($topic, $pool, 'scheduler');
                $this->info('  → Dispatched to queue.');
            }

            $dispatched++;
        }

        $this->info("  Dispatched: {$dispatched}/{$count}");

        return self::SUCCESS;
    }

    protected function generateForTopic(int $topicId): int
    {
        $topic = ArticleTopic::find($topicId);
        if (! $topic) {
            $this->error("Topic not found: {$topicId}");

            return self::FAILURE;
        }

        $this->info("Generating for topic: {$topic->title}");

        if ($this->option('sync')) {
            $service = app(ArticleGeneratorService::class);
            $post = $service->generate($topic, null, 'manual');
            if ($post) {
                $this->info("✓ Created post #{$post->id}: {$post->title}");
            } else {
                $this->error($this->syncGenerationFailureMessage(null, $topic));
            }
        } else {
            GenerateArticleJob::dispatch($topic, null, 'manual');
            $this->info('Dispatched to queue.');
        }

        return self::SUCCESS;
    }

    /**
     * Ambil `error_message` dari log penolakan/gagal terbaru agar CLI & Filament tidak buta.
     */
    private function syncGenerationFailureMessage(?ArticlePool $pool, ArticleTopic $topic): string
    {
        $q = ArticleGenerationLog::query()
            ->where('article_topic_id', $topic->getKey())
            ->whereIn('status', ['rejected', 'failed']);

        if ($pool !== null) {
            $q->where('article_pool_id', $pool->getKey());
        }

        $detail = $q->orderByDesc('id')->value('error_message');

        if (is_string($detail) && $detail !== '') {
            return '  ✗ '.$detail;
        }

        return '  ✗ Generation failed. Check logs.';
    }
}
