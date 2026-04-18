<?php

namespace App\Services\ArticleGeneration;

use App\Services\ArticleGeneration\Contracts\QualityRuleSetInterface;
use App\Services\ResponseParser;

/**
 * Validasi khusus artikel harian ringkas anggota — ambang lebih rendah, penutup boleh `## Penutup`.
 */
final class MemberPracticalQualityRuleSet implements QualityRuleSetInterface
{
    /** @var list<string> */
    private array $errors = [];

    /** @var list<string> */
    private array $warnings = [];

    public function validate(ResponseParser $parser, string $content): bool
    {
        $this->errors = [];
        $this->warnings = [];

        $cfg = config('article-generator.content_profiles.member_practical', []);
        $minWords = (int) ($cfg['min_word_count'] ?? 450);
        $minCitations = (int) ($cfg['min_inline_citations'] ?? $cfg['min_references'] ?? 2);

        $wordCount = $parser->countWords($content);
        if ($wordCount < $minWords) {
            $this->errors[] = "Jumlah kata ({$wordCount}) di bawah minimum materi praktis ({$minWords}).";
        }

        if (! $parser->hasReferencesSection($content)) {
            $this->errors[] = 'Tidak ditemukan bagian Daftar Pustaka/Referensi.';
        }

        $citations = $parser->countInlineCitations($content);
        if ($citations < $minCitations) {
            $this->errors[] = "Jumlah sitasi inline ({$citations}) di bawah minimum materi praktis ({$minCitations}).";
        }

        if (! $parser->hasAbstract($content)) {
            $this->errors[] = 'Tidak ditemukan bagian Ringkasan praktis atau Abstrak.';
        }

        if (! $parser->hasConclusion($content)) {
            $this->errors[] = 'Tidak ditemukan bagian Penutup/Kesimpulan.';
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
        if ($h2Count < 4) {
            $this->warnings[] = "Jumlah heading utama ({$h2Count}) kurang dari 4.";
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
