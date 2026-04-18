<?php

namespace App\Services;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\AcademicPromptStrategy;
use App\Services\ArticleGeneration\MemberPracticalPromptStrategy;
use App\Services\ArticleGeneration\PromptComposer;

/**
 * @deprecated Fasad tipis di atas {@see PromptComposer}. Prefer injeksi PromptComposer di kode baru.
 */
class PromptBuilder
{
    public function __construct(
        private readonly PromptComposer $composer,
    ) {}

    public static function makeDefault(): self
    {
        return new self(new PromptComposer(
            new AcademicPromptStrategy,
            new MemberPracticalPromptStrategy,
        ));
    }

    public function getSystemPrompt(?ArticlePool $pool = null, ?ArticleTopic $topic = null): string
    {
        return $this->composer->systemPrompt($pool, $topic);
    }

    /**
     * @param  list<string>  $recentTitles
     */
    public function buildUserPrompt(ArticleTopic $topic, ?ArticlePool $pool = null, array $recentTitles = []): string
    {
        return $this->composer->buildUserPrompt($pool, $topic, $recentTitles);
    }
}
