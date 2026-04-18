<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mews\Purifier\Facades\Purifier;
use Tests\TestCase;

/**
 * Regresi: konfigurasi Purifier "default" tidak memuat h1–h6 sehingga heading
 * RichEditor diubah menjadi <p>. PageObserver/PostObserver memakai filament_rich_html.
 */
class HtmlSanitizerRichContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_rich_html_preserves_multiple_headings(): void
    {
        $dirty = '<h2>Pertama</h2><p>Teks</p><h2>Kedua</h2><h3>Sub</h3><blockquote>Kutip</blockquote>';
        $clean = Purifier::clean($dirty, 'filament_rich_html');

        $this->assertStringContainsString('<h2>Pertama</h2>', $clean);
        $this->assertStringContainsString('<h2>Kedua</h2>', $clean);
        $this->assertStringContainsString('<h3>Sub</h3>', $clean);
        $this->assertStringContainsString('<blockquote>Kutip</blockquote>', $clean);
    }

    public function test_page_observer_keeps_headings_on_save(): void
    {
        $user = User::factory()->create();

        $page = Page::create([
            'title' => 'Uji',
            'slug' => 'uji-purifier',
            'body' => '<h2>A</h2><p>x</p><h2>B</h2>',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => $user->id,
        ]);

        $this->assertSame(2, substr_count($page->fresh()->body, '<h2>'));
    }

    public function test_post_observer_keeps_headings_on_save_for_manual_posts(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'title' => 'Uji',
            'slug' => 'uji-post-purifier',
            'body' => '<h2>Satu</h2><h2>Dua</h2>',
            'excerpt' => null,
            'status' => 'draft',
            'author_id' => $user->id,
            'source_type' => 'manual',
        ]);

        $this->assertSame(2, substr_count($post->fresh()->body, '<h2>'));
    }
}
