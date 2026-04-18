<?php

namespace App\Services;

use App\DTOs\PlagiarismResult;
use App\Models\Post;
use Carbon\CarbonImmutable;

final class ArticlePlagiarismChecker
{
    public function checkMarkdown(string $markdown): PlagiarismResult
    {
        if (! (bool) config('article-generator.quality.plagiarism.enabled', true)) {
            return new PlagiarismResult(0.0, null);
        }

        $candidateLimit = max(1, (int) config('article-generator.quality.plagiarism.candidate_limit', 200));
        $lookbackDays = max(1, (int) config('article-generator.quality.plagiarism.lookback_days', 730));

        $generatedText = $this->normalizeText($this->plainTextFromMarkdown($markdown));
        $generatedShingles = $this->shingles($generatedText, 5);

        if (count($generatedShingles) < 40) {
            return new PlagiarismResult(0.0, null);
        }

        $generatedSet = array_fill_keys($generatedShingles, true);
        $generatedCount = count($generatedSet);

        $cutoff = CarbonImmutable::now()->subDays($lookbackDays);
        $candidates = Post::query()
            ->select(['id', 'body', 'created_at'])
            ->where('created_at', '>=', $cutoff)
            ->orderByDesc('created_at')
            ->limit($candidateLimit)
            ->get();

        $best = 0.0;
        $bestId = null;

        foreach ($candidates as $post) {
            $plain = $this->normalizeText($this->plainTextFromHtml((string) $post->body));
            $candidateShingles = $this->shingles($plain, 5);
            if ($candidateShingles === []) {
                continue;
            }

            $candidateSet = array_fill_keys($candidateShingles, true);
            $candidateCount = count($candidateSet);

            $intersection = 0;
            foreach ($candidateSet as $hash => $_) {
                if (isset($generatedSet[$hash])) {
                    $intersection++;
                }
            }

            $union = $generatedCount + $candidateCount - $intersection;
            if ($union <= 0) {
                continue;
            }

            $score = $intersection / $union;
            if ($score > $best) {
                $best = $score;
                $bestId = (int) $post->id;
            }
        }

        return new PlagiarismResult($best, $bestId);
    }

    private function normalizeText(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text) ?? '';
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';

        return trim($text);
    }

    private function shingles(string $normalizedText, int $n): array
    {
        $words = preg_split('/\s+/u', $normalizedText, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $count = count($words);

        if ($count < $n) {
            return [];
        }

        $out = [];
        $limit = min($count - $n + 1, 2500);
        for ($i = 0; $i < $limit; $i++) {
            $chunk = array_slice($words, $i, $n);
            $out[] = sha1(implode(' ', $chunk));
        }

        return $out;
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

    private function plainTextFromHtml(string $html): string
    {
        $s = strip_tags($html);
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5);

        return $s;
    }
}

