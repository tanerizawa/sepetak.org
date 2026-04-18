<?php

namespace App\Services\ArticleGeneration;

final class PromptSanitizer
{
    public static function sanitizeNullable(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $sanitized = self::sanitize($value);

        return $sanitized !== '' ? $sanitized : null;
    }

    public static function sanitize(string $value): string
    {
        $value = str_replace("\r\n", "\n", $value);
        $value = str_replace("\r", "\n", $value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = trim($value);

        $max = (int) config('article-generator.prompt_template_max_chars', 5000);
        if ($max > 0 && mb_strlen($value) > $max) {
            $value = mb_substr($value, 0, $max);
        }

        return $value;
    }
}

