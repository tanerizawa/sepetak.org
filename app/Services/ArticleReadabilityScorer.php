<?php

namespace App\Services;

final class ArticleReadabilityScorer
{
    public function scoreMarkdown(string $markdown): int
    {
        $text = $this->plainTextFromMarkdown($markdown);
        $text = trim($text);

        if ($text === '') {
            return 0;
        }

        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $wordCount = count($words);

        if ($wordCount === 0) {
            return 0;
        }

        $sentences = preg_split('/[.!?]+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $sentenceCount = max(1, count(array_filter(array_map('trim', $sentences))));

        $avgWordsPerSentence = $wordCount / $sentenceCount;

        $longWords = 0;
        foreach ($words as $w) {
            if (mb_strlen($w) >= 13) {
                $longWords++;
            }
        }
        $longWordRatio = $longWords / $wordCount;

        $score = 100.0;
        $score -= max(0.0, $avgWordsPerSentence - 18.0) * 3.0;
        $score -= $longWordRatio * 40.0;

        $score = max(0.0, min(100.0, $score));

        return (int) round($score);
    }

    private function plainTextFromMarkdown(string $markdown): string
    {
        $s = preg_replace('/```[\s\S]*?```/u', ' ', $markdown) ?? '';
        $s = preg_replace('/`[^`]+`/u', ' ', $s) ?? '';
        $s = preg_replace('/\[(.*?)\]\((.*?)\)/u', '$1', $s) ?? '';
        $s = preg_replace('/^#{1,6}\s+/m', '', $s) ?? '';
        $s = preg_replace('/[*_>#+=-]/u', ' ', $s) ?? '';
        $s = preg_replace('/\s+/u', ' ', $s) ?? '';

        return $s;
    }
}

