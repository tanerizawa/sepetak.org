<?php

namespace Tests\Unit\ArticleGenerator;

use App\Services\ArticleQualityValidator;
use Tests\TestCase;

class ArticleQualityValidatorTest extends TestCase
{
    public function test_member_practical_accepts_shorter_article(): void
    {
        $validator = app(ArticleQualityValidator::class);
        $body = $this->minimalMarkdownBody(wordBlock: str_repeat('word ', 520));

        $this->assertTrue($validator->validate($body, 'member_practical'), $validator->errorsAsString());
    }

    public function test_pillar_profile_rejects_short_article(): void
    {
        $validator = app(ArticleQualityValidator::class);
        $body = $this->minimalMarkdownBody(wordBlock: str_repeat('word ', 520));

        $this->assertFalse($validator->validate($body, 'pillar'));
        $this->assertNotEmpty($validator->errors());
    }

    /**
     * Struktur minimal mirip keluaran AI (bukan pillar panjang).
     */
    private function minimalMarkdownBody(string $wordBlock): string
    {
        return <<<MD
# Judul Uji Validasi

**Kata Kunci:** a, b, c, d, e
**Waktu Baca:** ± 4 menit

## Daftar Isi
1. Ringkasan praktis
2. Bagian utama
3. Penutup
4. Daftar Pustaka

## Ringkasan praktis

{$wordBlock}
Ringkasan dengan sitasi (KPA, 2023).

## Bagian utama

{$wordBlock}
Paragraf dengan sitasi (BPN, 2020) dan kutipan lanjutan (Walhi, 2021).

## Penutup

{$wordBlock}
Penutup singkat.

## Daftar Pustaka

- KPA. (2023). *Laporan*. KPA.
- BPN. (2020). *Panduan*. BPN.
MD;
    }
}
