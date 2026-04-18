<?php

namespace Tests\Unit\ArticleGenerator;

use App\Models\ArticleGenerationLog;
use App\Models\ArticleTopic;
use App\Services\TopicPicker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicPickerCooldownCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_cooldown_excludes_recently_completed_topics_without_per_topic_db_queries(): void
    {
        config(['article-generator.topic_cooldown_hours' => 6]);

        $t1 = ArticleTopic::create([
            'title' => 'Topik Satu',
            'slug' => 'topik-satu',
            'description' => 'd',
            'thinking_framework' => 'human_rights',
            'article_type' => 'pillar',
            'prompt_template' => '',
            'weight' => 50,
            'is_active' => true,
        ]);

        $t2 = ArticleTopic::create([
            'title' => 'Topik Dua',
            'slug' => 'topik-dua',
            'description' => 'd',
            'thinking_framework' => 'human_rights',
            'article_type' => 'pillar',
            'prompt_template' => '',
            'weight' => 50,
            'is_active' => true,
        ]);

        ArticleGenerationLog::create([
            'article_topic_id' => $t1->id,
            'status' => 'completed',
            'ai_provider' => 'fake',
            'triggered_by' => 'test',
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        $picked = app(TopicPicker::class)->pick();

        $this->assertNotNull($picked);
        $this->assertSame($t2->id, $picked->id);
    }
}

