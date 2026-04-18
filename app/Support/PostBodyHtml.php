<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

/**
 * Normalisasi HTML isi artikel untuk tampilan publik (hindari judul ganda,
 * perbaikan tampilan teks rapat setelah </strong> dari Markdown AI),
 * serta id anchor + daftar isi untuk navigasi samping.
 */
final class PostBodyHtml
{
    /**
     * @return array{html: string, toc: list<array{id: string, text: string, level: int}>}
     */
    public static function articlePresentation(string $bodyHtml, string $postTitle): array
    {
        $html = self::forPublicDisplay($bodyHtml, $postTitle);

        return self::withHeadingAnchorsAndToc($html);
    }

    /**
     * Siapkan body HTML untuk dirender di halaman artikel.
     */
    public static function forPublicDisplay(string $bodyHtml, string $postTitle): string
    {
        $html = $bodyHtml;
        $html = self::stripLeadingTitleHeading($html, $postTitle);
        $html = self::stripLeadingTitleParagraph($html, $postTitle);
        $html = self::insertSpaceAfterStrongBeforeWord($html);

        return $html;
    }

    /**
     * Tambahkan atribut id pada H2/H3, bangun entri TOC (kecuali judul "Daftar Isi").
     *
     * @return array{html: string, toc: list<array{id: string, text: string, level: int}>}
     */
    public static function withHeadingAnchorsAndToc(string $html): array
    {
        $trimmed = trim($html);
        if ($trimmed === '') {
            return ['html' => $html, 'toc' => []];
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrapped = '<?xml encoding="UTF-8">'."\n".'<div id="__post_root">'.$trimmed.'</div>';
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $dom->getElementById('__post_root');
        if (! $root) {
            return ['html' => $html, 'toc' => []];
        }

        self::removeFirstInlineDaftarIsiSection($root);

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('.//h2 | .//h3 | .//h4', $root);
        $toc = [];
        $usedIds = [];

        if ($nodes !== false) {
            foreach ($nodes as $node) {
                if (! $node instanceof DOMElement) {
                    continue;
                }
                $tag = strtolower($node->tagName);
                $level = match ($tag) {
                    'h4' => 4,
                    'h3' => 3,
                    default => 2,
                };
                $text = trim(preg_replace('/\s+/u', ' ', $node->textContent ?? '') ?? '');
                if ($text === '') {
                    continue;
                }

                $base = Str::slug($text);
                if ($base === '') {
                    $base = 'bagian';
                }
                $id = $base;
                $n = 2;
                while (isset($usedIds[$id])) {
                    $id = $base.'-'.$n;
                    $n++;
                }
                $usedIds[$id] = true;
                $node->setAttribute('id', $id);

                if (preg_match('/^daftar isi$/iu', $text) === 1) {
                    continue;
                }

                $toc[] = [
                    'id' => $id,
                    'text' => $text,
                    'level' => $level,
                ];
            }
        }

        $htmlOut = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $htmlOut .= $dom->saveHTML($child);
        }

        return ['html' => $htmlOut, 'toc' => $toc];
    }

    private static function normalizePlain(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? '';

        return $value;
    }

    private static function titlesMatch(string $a, string $b): bool
    {
        return mb_strtolower(self::normalizePlain($a)) === mb_strtolower(self::normalizePlain($b));
    }

    private static function stripLeadingTitleHeading(string $html, string $postTitle): string
    {
        if ($postTitle === '' || ! preg_match('/^\s*<h1\b[^>]*>(.*?)<\/h1>/is', $html, $m)) {
            return $html;
        }

        if (! self::titlesMatch($m[1], $postTitle)) {
            return $html;
        }

        return (string) preg_replace('/^\s*<h1\b[^>]*>.*?<\/h1>\s*/is', '', $html, 1);
    }

    private static function stripLeadingTitleParagraph(string $html, string $postTitle): string
    {
        if ($postTitle === '' || ! preg_match('/^\s*<p\b[^>]*>(.*?)<\/p>/is', $html, $m)) {
            return $html;
        }

        if (! self::titlesMatch($m[1], $postTitle)) {
            return $html;
        }

        return (string) preg_replace('/^\s*<p\b[^>]*>.*?<\/p>\s*/is', '', $html, 1);
    }

    /**
     * AI sering mengeluarkan `**Langkah 1:**Teks` → </strong>Teks tanpa spasi.
     */
    private static function insertSpaceAfterStrongBeforeWord(string $html): string
    {
        $html = (string) preg_replace('/<\/strong>(?=[\p{L}])/u', '</strong> ', $html);

        return (string) preg_replace('/<\/b>(?=[\p{L}])/u', '</b> ', $html);
    }

    /**
     * Hapus blok "Daftar Isi" bawaan Markdown di isi (judul h2 + isi sampai h2 berikutnya),
     * karena navigasi sudah di panel TOC samping.
     */
    private static function removeFirstInlineDaftarIsiSection(DOMElement $root): void
    {
        $doc = $root->ownerDocument;
        if ($doc === null) {
            return;
        }

        $xpath = new DOMXPath($doc);
        $h2Nodes = $xpath->query('.//h2', $root);
        if ($h2Nodes === false) {
            return;
        }

        foreach ($h2Nodes as $h2) {
            if (! $h2 instanceof DOMElement) {
                continue;
            }
            $label = trim(preg_replace('/\s+/u', ' ', $h2->textContent ?? '') ?? '');
            if (preg_match('/^daftar\s+isi$/iu', $label) !== 1) {
                continue;
            }

            self::removeNodeAndFollowingUntilNextH2($h2);

            return;
        }
    }

    private static function removeNodeAndFollowingUntilNextH2(DOMElement $start): void
    {
        $parent = $start->parentNode;
        if (! $parent) {
            return;
        }

        $node = $start->nextSibling;
        $parent->removeChild($start);

        while ($node) {
            if ($node instanceof DOMElement && strtolower($node->tagName) === 'h2') {
                break;
            }
            $next = $node->nextSibling;
            $parent->removeChild($node);
            $node = $next;
        }
    }
}
