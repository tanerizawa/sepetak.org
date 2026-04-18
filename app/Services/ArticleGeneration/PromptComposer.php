<?php

namespace App\Services\ArticleGeneration;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\Contracts\PromptStrategyInterface;

/**
 * Memilih strategi prompt berdasarkan profil konten (pool + topik), selaras dengan
 * {@see ContentProfile::forArticleGeneration} dan validator.
 */
final class PromptComposer
{
    public function __construct(
        private readonly AcademicPromptStrategy $academic,
        private readonly MemberPracticalPromptStrategy $memberPractical,
    ) {}

    public function resolve(?ArticlePool $pool, ?ArticleTopic $topic = null): PromptStrategyInterface
    {
        if ($topic !== null) {
            return ContentProfile::forArticleGeneration($pool, $topic) === ContentProfile::MemberPractical
                ? $this->memberPractical
                : $this->academic;
        }

        return ContentProfile::fromPool($pool) === ContentProfile::MemberPractical
            ? $this->memberPractical
            : $this->academic;
    }

    public function systemPrompt(?ArticlePool $pool, ?ArticleTopic $topic = null): string
    {
        return $this->resolve($pool, $topic)->systemPrompt();
    }

    /**
     * @param  list<string>  $recentAutoPostTitles
     */
    public function buildUserPrompt(?ArticlePool $pool, ArticleTopic $topic, array $recentAutoPostTitles = []): string
    {
        $strategy = $this->resolve($pool, $topic);

        if ($strategy instanceof MemberPracticalPromptStrategy) {
            return $strategy->buildUserPrompt($topic, $recentAutoPostTitles);
        }

        return $strategy->buildUserPrompt($topic, []);
    }
}
