<?php

namespace Tests\Unit;

use App\Models\ArticlePool;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlePoolScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_multi_slot_is_due_only_on_configured_minutes(): void
    {
        config(['article-generator.schedule_timezone' => 'Asia/Jakarta']);

        $pool = ArticlePool::create([
            'name' => 'Uji slot',
            'slug' => 'uji-slot',
            'schedule_frequency' => 'daily',
            'schedule_time' => '07:00',
            'schedule_times' => ['04:45', '12:10'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);

        $this->assertTrue($pool->isDueAt(Carbon::parse('2026-06-01 04:45', 'Asia/Jakarta')));
        $this->assertFalse($pool->isDueAt(Carbon::parse('2026-06-01 04:46', 'Asia/Jakarta')));
        $this->assertTrue($pool->isDueAt(Carbon::parse('2026-06-01 12:10', 'Asia/Jakarta')));
    }

    public function test_get_next_run_at_advances_across_slots(): void
    {
        config(['article-generator.schedule_timezone' => 'Asia/Jakarta']);

        $pool = ArticlePool::create([
            'name' => 'Uji next',
            'slug' => 'uji-next',
            'schedule_frequency' => 'daily',
            'schedule_time' => '07:00',
            'schedule_times' => ['10:00', '14:00'],
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
            'content_profile' => 'pillar',
        ]);

        $from = Carbon::parse('2026-06-01 09:00', 'Asia/Jakarta');
        $next = $pool->getNextRunAt($from);
        $this->assertSame('2026-06-01 10:00', $next->timezone('Asia/Jakarta')->format('Y-m-d H:i'));

        $from2 = Carbon::parse('2026-06-01 15:00', 'Asia/Jakarta');
        $next2 = $pool->getNextRunAt($from2);
        $this->assertSame('2026-06-02 10:00', $next2->timezone('Asia/Jakarta')->format('Y-m-d H:i'));
    }
}
