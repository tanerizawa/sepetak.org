<?php

namespace App\Services;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\ContentProfile;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Pilih topik aktif berbobot untuk satu kali run generator.
 *
 * Untuk pool member_practical, topik yang sudah completed HARI INI (timezone config)
 * dikecualikan supaya tidak menghasilkan duplikat pada satu pool.
 *
 * Opsional: `article-generator.topic_cooldown_hours` (fragmen recovery) mengecualikan
 * topik yang baru-baru ini sukses digenerate di mana pun, agar tidak berulang terlalu cepat.
 */
class TopicPicker
{
    public function pick(?ArticlePool $pool = null): ?ArticleTopic
    {
        $query = ArticleTopic::query()
            ->where('is_active', true)
            ->whereNull('deleted_at');

        // Restrict to topics linked to the pool when present.
        if ($pool !== null) {
            $query->whereHas('pools', fn ($q) => $q->whereKey($pool->getKey()));
        }

        $topics = $query->get();

        if ($topics->isEmpty()) {
            return null;
        }

        // Filter exhausted (max_uses reached) and inactive.
        $topics = $topics->filter(fn (ArticleTopic $t) => $t->isAvailable());

        $topics = $this->excludeTopicsUnderCooldown($topics);

        // Daily cap per pool for member_practical profile.
        if ($pool !== null && ContentProfile::fromPool($pool) === ContentProfile::MemberPractical) {
            $topics = $this->excludeTopicsCompletedTodayInPool($topics, $pool);
        }

        if ($topics->isEmpty()) {
            return null;
        }

        return $this->weightedRandom($topics);
    }

    /**
     * @param  Collection<int, ArticleTopic>  $topics
     * @return Collection<int, ArticleTopic>
     */
    private function excludeTopicsCompletedTodayInPool(Collection $topics, ArticlePool $pool): Collection
    {
        $tz = config('article-generator.schedule_timezone', 'Asia/Jakarta');
        $start = now($tz)->startOfDay()->utc();
        $end = now($tz)->startOfDay()->addDay()->utc();

        $completedTopicIds = ArticleGenerationLog::query()
            ->where('article_pool_id', $pool->getKey())
            ->whereIn('status', ['completed', 'generating', 'queued'])
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->pluck('article_topic_id')
            ->filter()
            ->unique()
            ->all();

        return $topics->reject(fn (ArticleTopic $t) => in_array($t->getKey(), $completedTopicIds, true));
    }

    /**
     * @param  Collection<int, ArticleTopic>  $topics
     * @return Collection<int, ArticleTopic>
     */
    private function excludeTopicsUnderCooldown(Collection $topics): Collection
    {
        $hours = (int) config('article-generator.topic_cooldown_hours', 0);
        if ($hours <= 0) {
            return $topics;
        }

        $cutoff = CarbonImmutable::now()->subHours(max(1, $hours));

        $recent = ArticleGenerationLog::query()
            ->select('article_topic_id', DB::raw('MAX(created_at) as last_completed_at'))
            ->where('status', 'completed')
            ->whereNotNull('article_topic_id')
            ->where('created_at', '>=', $cutoff)
            ->groupBy('article_topic_id')
            ->get();

        foreach ($recent as $row) {
            $topicId = (int) $row->article_topic_id;
            if ($topicId <= 0) {
                continue;
            }

            $last = $row->last_completed_at ? CarbonImmutable::parse($row->last_completed_at) : null;
            if (! $last) {
                continue;
            }

            $until = $last->addHours($hours);
            $remaining = CarbonImmutable::now()->diffInSeconds($until, false);
            if ($remaining > 0) {
                Cache::put($this->cooldownCacheKey($topicId), 1, $remaining);
            }
        }

        return $topics->reject(function (ArticleTopic $t): bool {
            $id = (int) $t->getKey();
            if ($id <= 0) {
                return false;
            }

            return Cache::has($this->cooldownCacheKey($id));
        });
    }

    private function cooldownCacheKey(int $topicId): string
    {
        return "ai_article_topic:cooldown:{$topicId}";
    }

    /**
     * @param  Collection<int, ArticleTopic>  $topics
     */
    private function weightedRandom(Collection $topics): ArticleTopic
    {
        $totalWeight = (int) $topics->sum(fn (ArticleTopic $t) => max(1, (int) $t->weight));
        $roll = random_int(1, max(1, $totalWeight));
        $running = 0;
        foreach ($topics as $topic) {
            $running += max(1, (int) $topic->weight);
            if ($roll <= $running) {
                return $topic;
            }
        }

        return $topics->first();
    }
}
