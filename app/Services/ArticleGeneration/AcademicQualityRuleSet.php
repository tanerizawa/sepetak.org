<?php

namespace App\Services\ArticleGeneration;

use App\Services\ArticleGeneration\Contracts\QualityRuleSetInterface;
use App\Services\ResponseParser;

/**
 * Validasi khusus output artikel ilmiah / pillar — tidak memakai ambang materi harian.
 */
final class AcademicQualityRuleSet implements QualityRuleSetInterface
{
    /** @var list<string> */
    private array $errors = [];

    /** @var list<string> */
    private array $warnings = [];

    public function validate(ResponseParser $parser, string $content): bool
    {
        $this->errors = [];
        $this->warnings = [];

        $cfg = config('article-generator.content_profiles.pillar', []);
        $minWords = (int) ($cfg['min_word_count'] ?? config('article-generator.defaults.min_word_count', 1500));
        $minCitations = (int) ($cfg['min_inline_citations'] ?? $cfg['min_references'] ?? 5);

        $wordCount = $parser->countWords($content);
        if ($wordCount < $minWords) {
            $this->errors[] = "Jumlah kata ({$wordCount}) di bawah minimum akademik ({$minWords}).";
        }

        if (! $parser->hasReferencesSection($content)) {
            $this->errors[] = 'Tidak ditemukan bagian Daftar Pustaka/Referensi.';
        }

        $citations = $parser->countInlineCitations($content);
        if ($citations < $minCitations) {
            $this->errors[] = "Jumlah sitasi inline ({$citations}) di bawah minimum akademik ({$minCitations}).";
        }

        if (! preg_match('/##\s*Abstrak\b/i', $content)) {
            $this->errors[] = 'Jalur akademik mewajibkan heading `## Abstrak` (bukan ringkasan praktis).';
        }

        if (! preg_match('/##\s*Kesimpulan\b/i', $content)) {
            $this->errors[] = 'Jalur akademik mewajibkan heading `## Kesimpulan`.';
        }

        if (mb_strlen(trim($content)) < 500) {
            $this->errors[] = 'Konten terlalu pendek (< 500 karakter).';
        }

        if (! $parser->hasTableOfContents($content)) {
            $this->warnings[] = 'Tidak ditemukan Daftar Isi.';
        }

        if (! $parser->hasKeywords($content)) {
            $this->warnings[] = 'Tidak ditemukan Kata Kunci.';
        }

        $h2Count = preg_match_all('/^##\s+/m', $content);
        if ($h2Count < 5) {
            $this->warnings[] = "Jumlah heading utama ({$h2Count}) kurang dari 5 (tunjukkan struktur pillar).";
        }

        return $this->errors === [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function warnings(): array
    {
        return $this->warnings;
    }
}
