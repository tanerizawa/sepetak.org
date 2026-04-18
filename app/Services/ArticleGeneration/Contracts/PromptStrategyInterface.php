<?php

namespace App\Services\ArticleGeneration\Contracts;

use App\Models\ArticleTopic;

/**
 * Strategi prompt AI terpisah per jalur (akademik vs harian ringkas) — tanpa percabangan silang di satu teks.
 */
interface PromptStrategyInterface
{
    public function systemPrompt(): string;

    /**
     * @param  list<string>  $recentAutoPostTitles  Hanya dipakai jalur harian ringkas.
     */
    public function buildUserPrompt(ArticleTopic $topic, array $recentAutoPostTitles = []): string;
}
