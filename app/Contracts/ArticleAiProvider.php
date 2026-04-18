<?php

namespace App\Contracts;

use App\DTOs\ArticleAiResponse;

interface ArticleAiProvider
{
    public function generate(string $systemPrompt, string $userPrompt, array $options = []): ArticleAiResponse;

    public function name(): string;
}
