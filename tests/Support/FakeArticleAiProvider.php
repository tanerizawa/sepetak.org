<?php

namespace Tests\Support;

use App\Contracts\ArticleAiProvider;
use App\DTOs\ArticleAiResponse;

/**
 * Provider AI palsu untuk uji integrasi pipeline generator (tanpa OpenRouter).
 */
final class FakeArticleAiProvider implements ArticleAiProvider
{
    public function __construct(
        private readonly string $markdown = '',
    ) {}

    public function generate(string $systemPrompt, string $userPrompt, array $options = []): ArticleAiResponse
    {
        $body = $this->markdown !== '' ? $this->markdown : self::minimalMemberGuideMarkdown();

        return new ArticleAiResponse(
            content: $body,
            model: 'fake-test-model',
            tokensUsed: 120,
        );
    }

    public function name(): string
    {
        return 'fake';
    }

    /**
     * Markdown yang memenuhi validator profil member_practical (kata + sitasi + struktur).
     */
    public static function minimalMemberGuideMarkdown(): string
    {
        $filler = str_repeat(
            'Anggota SEPETAK membutuhkan panduan praktis berorganisasi di desa dan basis tanpa naskah akademik panjang. ',
            35
        );

        return <<<MD
# Panduan Uji Otomatis untuk Anggota SEPETAK

**Kata Kunci:** organisasi, desa, anggota, SEPETAK, praktis
**Waktu Baca:** ± 6 menit

## Daftar Isi
1. Ringkasan praktis
2. Langkah operasional
3. Catatan hukum ringkas
4. Penutup
5. Daftar Pustaka

## Ringkasan praktis

{$filler}
Ringkasan ini merangkum langkah ringkas yang dapat diterapkan di tingkat pokja (KPA, 2023).

## Langkah operasional

{$filler}
Gunakan daftar hadir musyawarah dan mandat tertulis sebelum koordinasi dengan DPTD (BPN, 2020).

> **Poin Penting:** Dokumentasi ringkas mengurangi miskomunikasi antaranggota.

## Catatan hukum ringkas

{$filler}
Rujuk peraturan desa dan perundangan agraria yang relevan; hindari interpretasi yuridis mendalam di lapangan tanpa pendamping (Walhi, 2021).

## Penutup

{$filler}
Koordinasikan dengan pengurus basis bila isu menyangkut sengketa atau pemeriksaan instansi.

## Daftar Pustaka

- Konsorsium Pembaruan Agraria. (2023). *Catatan tahunan agraria*. KPA.
- Badan Pertanahan Nasional. (2020). *Materi sosialisasi sertifikat*. BPN.
- Wahana Lingkungan Hidup Indonesia. (2021). *Panduan advokasi komunitas*. Walhi.
MD;
    }
}
