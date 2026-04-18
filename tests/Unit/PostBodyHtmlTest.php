<?php

namespace Tests\Unit;

use App\Support\PostBodyHtml;
use PHPUnit\Framework\TestCase;

class PostBodyHtmlTest extends TestCase
{
    public function test_strips_leading_h1_when_text_matches_title(): void
    {
        $title = 'Membaca Jadwal Irigasi';
        $html = '<h1>Membaca Jadwal Irigasi</h1><p>Paragraf.</p>';
        $out = PostBodyHtml::forPublicDisplay($html, $title);

        $this->assertStringNotContainsString('<h1>', $out);
        $this->assertStringContainsString('Paragraf.', $out);
    }

    public function test_strips_leading_h1_case_insensitive_and_whitespace(): void
    {
        $title = "Membaca  Jadwal\nIrigasi";
        $html = "<h1>  membaca jadwal irigasi  </h1>\n<p>Isi.</p>";
        $out = PostBodyHtml::forPublicDisplay($html, $title);

        $this->assertSame('<p>Isi.</p>', trim($out));
    }

    public function test_keeps_h1_when_title_differs(): void
    {
        $html = '<h1>Judul Lain</h1><p>Teks.</p>';
        $out = PostBodyHtml::forPublicDisplay($html, 'Judul Resmi');

        $this->assertStringContainsString('<h1>Judul Lain</h1>', $out);
    }

    public function test_strips_leading_paragraph_duplicate_title(): void
    {
        $title = 'Halo Dunia';
        $html = '<p>Halo Dunia</p><h2>Bagian</h2>';
        $out = PostBodyHtml::forPublicDisplay($html, $title);

        $this->assertStringNotContainsString('<p>Halo Dunia</p>', $out);
        $this->assertStringContainsString('<h2>Bagian</h2>', $out);
    }

    public function test_inserts_space_after_strong_before_letter(): void
    {
        $html = '<p><strong>Langkah 1:</strong>Cari tahu.</p>';
        $out = PostBodyHtml::forPublicDisplay($html, 'x');

        $this->assertStringContainsString('</strong> Cari', $out);
    }

    public function test_article_presentation_adds_ids_and_toc(): void
    {
        $html = '<h2>Daftar Isi</h2><ol><li>Ringkasan</li><li>Isi</li></ol><h2>Bagian A</h2><p>teks</p><h3>Sub A</h3><h4>Detail</h4>';
        $out = PostBodyHtml::articlePresentation($html, 'Judul');

        $this->assertArrayHasKey('html', $out);
        $this->assertArrayHasKey('toc', $out);
        $this->assertStringNotContainsString('Daftar Isi', $out['html']);
        $this->assertStringNotContainsString('<li>Ringkasan</li>', $out['html']);
        $this->assertStringContainsString('id="bagian-a"', $out['html']);
        $this->assertStringContainsString('id="sub-a"', $out['html']);
        $this->assertStringContainsString('id="detail"', $out['html']);
        $ids = array_column($out['toc'], 'id');
        $this->assertContains('bagian-a', $ids);
        $this->assertContains('sub-a', $ids);
        $this->assertContains('detail', $ids);
        $this->assertNotContains('daftar-isi', $ids);
    }
}
