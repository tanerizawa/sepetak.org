<?php

namespace App\Services;

use App\Support\PostSlug;
use Illuminate\Support\Str;

/**
 * Parse hasil AI (Markdown) menjadi potongan terstruktur:
 * judul, abstrak, daftar pustaka, sitasi inline, dll.
 */
class ResponseParser
{
    public function parseTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $m)) {
            return PostSlug::normalizeHeadingText($m[1]);
        }

        return '';
    }

    public function parseAbstract(string $content): string
    {
        // "## Abstrak" atau "## Ringkasan …" (praktis, singkat, dsb.).
        if (preg_match('/##\s*(?:Abstrak|Ringkasan(?:\s+[^\n#]+)?)\s*\n+(.+?)(?=\n##\s|\z)/is', $content, $m)) {
            return trim($m[1]);
        }

        return '';
    }

    public function parseAbstractHtml(string $htmlContent): string
    {
        if (preg_match('/<(h2|h3)[^>]*id="(?:abstrak|ringkasan-praktis|ringkasan)"[^>]*>.*?<\/\1>\s*(.+?)(?=<h2|<h3|\z)/is', $htmlContent, $m)) {
            return trim($m[2]);
        }

        return '';
    }

    public function wrapAbstractHtml(string $html): string
    {
        if ($html === '') {
            return '';
        }

        return "<aside class=\"post-abstract\">{$html}</aside>";
    }

    public function parseReferences(string $content): array
    {
        if (! preg_match('/##\s*(?:Daftar\s+Pustaka|Referensi|Daftar\s+Referensi)\s*\n+(.+?)(?=\n##\s|\z)/is', $content, $m)) {
            return [];
        }

        $block = $m[1];
        $lines = preg_split('/\r?\n/', trim($block)) ?: [];
        $refs = [];
        foreach ($lines as $line) {
            $trimmed = trim(preg_replace('/^[\-\*\d\.\s]+/', '', $line) ?? '');
            if ($trimmed !== '') {
                $refs[] = $trimmed;
            }
        }

        return $refs;
    }

    public function countWords(string $content): int
    {
        $stripped = preg_replace('/[`#>*_\-\[\]\(\)]/', ' ', $content) ?? '';
        $stripped = preg_replace('/\s+/u', ' ', $stripped) ?? '';

        return str_word_count(trim($stripped), 0, 'àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄČĆĘÈÉÊËĖĮÌÍÎÏŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ðæøþ');
    }

    public function countInlineCitations(string $content): int
    {
        // APA ringkas: (Penulis, 2020); awal nama boleh huruf kecil / Unicode (mis. lembaga "kemenkumham").
        return (int) preg_match_all(
            '/\(\s*\p{L}[^\(\)\n]{0,180},\s*(?:(?:19|20)\d{2}[a-z]?|n\.d\.)\s*\)/u',
            $content,
        );
    }

    public function hasReferencesSection(string $content): bool
    {
        return (bool) preg_match('/##\s*(Daftar\s+Pustaka|Daftar\s+Referensi|Referensi|Pustaka)\b/i', $content);
    }

    public function hasTableOfContents(string $content): bool
    {
        return (bool) preg_match('/##\s*Daftar\s+Isi\b/i', $content);
    }

    public function hasKeywords(string $content): bool
    {
        return (bool) preg_match('/\*\*Kata\s+Kunci:\*\*/i', $content);
    }

    public function hasAbstract(string $content): bool
    {
        return (bool) preg_match('/##\s*(Abstrak|Ringkasan(?:\s+[^\n#]+)?)/i', $content);
    }

    public function hasConclusion(string $content): bool
    {
        return (bool) preg_match(
            '/##\s*(Kesimpulan|Penutup|Simpulan|Penutupan|Catatan\s+akhir|Tutup)\b/i',
            $content,
        );
    }

    /**
     * Extract H2 sections for table of contents / navigation.
     *
     * @return list<array{level:int,text:string,slug:string}>
     */
    public function parseHeadings(string $content): array
    {
        $out = [];
        if (preg_match_all('/^(#{2,3})\s+(.+)$/m', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $text = PostSlug::normalizeHeadingText($m[2]);
                $out[] = [
                    'level' => strlen($m[1]),
                    'text' => $text,
                    'slug' => Str::slug($text),
                ];
            }
        }

        return $out;
    }
}
