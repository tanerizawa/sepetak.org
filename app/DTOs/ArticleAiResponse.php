<?php

namespace App\DTOs;

class ArticleAiResponse
{
    public function __construct(
        public readonly string $content,
        public readonly string $model,
        public readonly int $tokensUsed,
        public readonly ?string $finishReason = null,
    ) {}
}
