<?php

namespace Tests\Unit\Services;

use App\Services\ArticleReadabilityScorer;
use Tests\TestCase;

class ArticleReadabilityScorerTest extends TestCase
{
    public function test_scores_short_sentences_higher_than_very_long_sentences(): void
    {
        $scorer = app(ArticleReadabilityScorer::class);

        $short = "# Judul\n\nKalimat pendek. Kalimat pendek. Kalimat pendek.\n\n## Isi\n\nKalimat pendek. Kalimat pendek.";
        $long = "# Judul\n\nIni adalah satu kalimat yang sangat panjang dan terus menerus ditambahkan kata demi kata tanpa jeda yang jelas sehingga rata-rata kata per kalimat menjadi besar dan skor keterbacaan harus turun drastis dibandingkan contoh yang lebih ringkas.\n\n## Isi\n\nKalimat yang panjang ini juga ditulis dengan banyak kata agar semakin berat dibaca oleh pembaca umum dan menghasilkan penalti tambahan.";

        $s1 = $scorer->scoreMarkdown($short);
        $s2 = $scorer->scoreMarkdown($long);

        $this->assertGreaterThan($s2, $s1);
    }
}

