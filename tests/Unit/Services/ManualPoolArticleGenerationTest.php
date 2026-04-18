<?php

namespace Tests\Unit\Services;

use App\Models\ArticlePool;
use App\Services\ManualPoolArticleGeneration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualPoolArticleGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_warning_when_generator_disabled(): void
    {
        config(['article-generator.enabled' => false]);

        $pool = ArticlePool::create([
            'name' => 'P',
            'slug' => 'p',
            'schedule_frequency' => 'daily',
            'schedule_time' => '09:00',
            'schedule_times' => null,
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);

        $result = app(ManualPoolArticleGeneration::class)->run($pool, false);

        $this->assertSame('warning', $result['status']);
        $this->assertStringContainsString('nonaktif', $result['title']);
    }
}
