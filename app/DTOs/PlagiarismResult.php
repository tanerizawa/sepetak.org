<?php

namespace App\DTOs;

final class PlagiarismResult
{
    public function __construct(
        public readonly float $maxSimilarity,
        public readonly ?int $matchedPostId = null,
    ) {}

    public function isTooSimilar(float $threshold): bool
    {
        return $this->maxSimilarity >= $threshold;
    }
}

