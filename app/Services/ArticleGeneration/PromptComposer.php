<?php

namespace App\Services\ArticleGeneration;

use App\Models\ArticlePool;
use App\Services\ArticleGeneration\Contracts\PromptStrategyInterface;

/**
 * Memilih strategi prompt berdasarkan profil pool — tanpa if/else panjang di satu kelas.
 */
final class PromptComposer
{
    public function __construct(
        private readonly AcademicPromptStrategy $academic,
        private readonly MemberPracticalPromptStrategy $memberPractical,
    ) {}

    public function resolve(?ArticlePool $pool): PromptStrategyInterface
    {
        return ContentProfile::fromPool($pool) === ContentProfile::MemberPractical
            ? $this->memberPractical
            : $this->academic;
    }

    public function systemPrompt(?ArticlePool $pool): string
    {
        return $this->resolve($pool)->systemPrompt();
    }

    /**
     * @param  list<string>  $recentAutoPostTitles
     */
    public function buildUserPrompt(?ArticlePool $pool, \App\Models\ArticleTopic $topic, array $recentAutoPostTitles = []): string
    {
        $strategy = $this->resolve($pool);

        if ($strategy instanceof MemberPracticalPromptStrategy) {
            return $strategy->buildUserPrompt($topic, $recentAutoPostTitles);
        }

        return $strategy->buildUserPrompt($topic, []);
    }
}
