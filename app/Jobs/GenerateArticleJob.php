<?php

namespace App\Jobs;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateArticleJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 300;

    public int $uniqueFor = 300; // 5 minutes - allows rapid retry on failure within tolerance

    public function __construct(
        public ArticleTopic $topic,
        public ?ArticlePool $pool = null,
        public string $triggeredBy = 'scheduler',
    ) {
        $queueName = config('article-generator.queue.name', 'default');
        $this->onQueue($queueName);

        $connection = config('article-generator.queue.connection');
        if ($connection) {
            $this->onConnection($connection);
        }
    }

    /**
     * Unique ID per topic to prevent duplicate jobs for the same topic.
     */
    public function uniqueId(): string
    {
        return 'generate-article-topic-'.$this->topic->id;
    }

    public function handle(ArticleGeneratorService $service): void
    {
        if (! config('article-generator.enabled', false)) {
            Log::info('ArticleGenerator: Disabled, skipping job.');

            return;
        }

        $service->generate($this->topic, $this->pool, $this->triggeredBy);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateArticleJob failed permanently', [
            'topic_id' => $this->topic->id,
            'pool_id' => $this->pool?->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
