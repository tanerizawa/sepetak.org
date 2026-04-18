<?php

namespace App\Services;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\ContentProfile;
use Illuminate\Support\Collection;

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

        return $topics->filter(function (ArticleTopic $t) use ($hours): bool {
            $lastLog = $t->generationLogs()
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (! $lastLog) {
                return true;
            }

            return $lastLog->created_at->diffInHours(now()) >= $hours;
        });
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
