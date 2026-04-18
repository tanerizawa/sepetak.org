<?php

namespace Tests\Unit\ArticleGenerator;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\TopicPicker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicPickerMemberPoolTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_pool_skips_topics_already_completed_today_in_same_pool(): void
    {
        config(['article-generator.schedule_timezone' => 'Asia/Jakarta']);

        $pool = ArticlePool::create([
            'name' => 'Pool Uji',
            'slug' => 'pool-uji-picker',
            'schedule_frequency' => 'daily',
            'schedule_time' => '07:00',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'member_practical',
        ]);

        $t1 = ArticleTopic::create([
            'title' => 'Topik Alpha',
            'slug' => 'topik-alpha',
            'description' => 'd',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 50,
            'is_active' => true,
        ]);
        $t2 = ArticleTopic::create([
            'title' => 'Topik Beta',
            'slug' => 'topik-beta',
            'description' => 'd',
            'thinking_framework' => 'human_rights',
            'article_type' => 'member_guide',
            'prompt_template' => '',
            'weight' => 50,
            'is_active' => true,
        ]);
        $pool->topics()->sync([$t1->id, $t2->id]);

        ArticleGenerationLog::create([
            'article_topic_id' => $t1->id,
            'article_pool_id' => $pool->id,
            'status' => 'completed',
            'ai_provider' => 'fake',
            'triggered_by' => 'test',
        ]);

        $picked = app(TopicPicker::class)->pick($pool);

        $this->assertNotNull($picked);
        $this->assertSame($t2->id, $picked->id);
    }
}
