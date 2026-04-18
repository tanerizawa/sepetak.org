<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Support\PostSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_normalize_heading_strips_markdown_emphasis(): void
    {
        $this->assertSame(
            'Dokumentasi Rapat Pokja',
            PostSlug::normalizeHeadingText('**Dokumentasi** Rapat `Pokja`')
        );
    }

    public function test_suggest_truncates_long_slug_under_base_max(): void
    {
        $long = str_repeat('bagian ', 120).'zulu';
        $s = PostSlug::suggestFromTitle($long);

        $this->assertLessThanOrEqual(PostSlug::MAX_SLUG_LENGTH - 20, strlen($s));
        $this->assertMatchesRegularExpression('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $s);
    }

    public function test_unique_from_title_appends_suffix_on_collision(): void
    {
        Post::create([
            'title' => 'Judul Bentrok',
            'slug' => 'judul-bentrok',
            'body' => '<p>x</p>',
            'status' => 'draft',
            'author_id' => null,
            'source_type' => 'manual',
        ]);

        $second = PostSlug::uniqueFromTitle('Judul Bentrok');

        $this->assertSame('judul-bentrok-1', $second);
    }
}
