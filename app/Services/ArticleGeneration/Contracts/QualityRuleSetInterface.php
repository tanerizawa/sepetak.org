<?php

namespace App\Services\ArticleGeneration\Contracts;

use App\Services\ResponseParser;

/**
 * Aturan validasi output Markdown per jalur (ambang kata, sitasi, struktur).
 */
interface QualityRuleSetInterface
{
    public function validate(ResponseParser $parser, string $content): bool;

    /** @return list<string> */
    public function errors(): array;

    /** @return list<string> */
    public function warnings(): array;
}
