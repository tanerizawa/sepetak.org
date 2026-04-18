<?php

namespace Tests\Feature;

use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_responds_and_shows_published_post(): void
    {
        $author = User::factory()->create();

        Post::create([
            'title' => 'Draft Tersembunyi',
            'slug' => 'draft-tersembunyi',
            'body' => '<p>draft</p>',
            'status' => 'draft',
            'author_id' => $author->id,
        ]);

        Post::create([
            'title' => 'Artikel Publik Uji',
            'slug' => 'artikel-publik-uji',
            'excerpt' => 'Kutipan',
            'body' => '<p>publik</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
            'author_id' => $author->id,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Artikel Publik Uji');
        $response->assertDontSee('Draft Tersembunyi');
        $response->assertSee('Daftar kasus agraria', false);
        $response->assertSee('Daftar program advokasi', false);
        $response->assertSee('Informasi pendaftaran anggota', false);
    }

    public function test_homepage_renders_when_stats_cache_missing_new_case_keys(): void
    {
        Cache::put('homepage.stats.v2', [
            'member_count' => 2,
            'case_count' => 7,
            'program_count' => 1,
        ], 600);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Daftar kasus agraria', false);
        $response->assertSee(route('agrarian-cases.index', [], false), false);
    }

    public function test_agrarian_cases_index_renders(): void
    {
        AgrarianCase::create([
            'case_code' => 'ORG-ADV-TEST',
            'title' => 'Kasus Uji Publik',
            'summary' => 'Ringkasan kasus uji.',
            'description' => 'Deskripsi lengkap kasus uji.',
            'location_text' => 'Karawang',
            'start_date' => now()->toDateString(),
            'status' => 'reported',
            'priority' => 'medium',
        ]);
        AgrarianCase::create([
            'case_code' => 'ORG-ADV-TEST-B',
            'title' => 'Kasus Tertutup Uji',
            'summary' => 'Ringkasan.',
            'description' => 'Deskripsi.',
            'location_text' => 'Karawang',
            'start_date' => now()->toDateString(),
            'status' => 'closed',
            'priority' => 'low',
        ]);

        $response = $this->get(route('agrarian-cases.index'));

        $response->assertOk();
        $response->assertSee('ORG-ADV-TEST');
        $response->assertSee('Kasus Uji Publik');
        $response->assertSee('Ringkasan publik', false);
        $response->assertSee('Kasus Tertutup Uji', false);
        $response->assertSee('Dilaporkan', false);
        $response->assertSee('Ditutup', false);
    }

    public function test_agrarian_case_show_renders_by_case_code(): void
    {
        AgrarianCase::create([
            'case_code' => 'ORG-ADV-DETAIL',
            'title' => 'Detail Kasus Uji',
            'summary' => 'Ringkasan.',
            'description' => 'Isi deskripsi.',
            'location_text' => 'Desa Uji',
            'start_date' => '2020-01-15',
            'status' => 'legal_process',
            'priority' => 'high',
        ]);

        $response = $this->get(route('agrarian-cases.show', 'ORG-ADV-DETAIL'));

        $response->assertOk();
        $response->assertSee('Detail Kasus Uji');
        $response->assertSee('Proses hukum');
    }

    public function test_advocacy_programs_index_renders(): void
    {
        AdvocacyProgram::create([
            'program_code' => 'ORG-PRG-TEST',
            'title' => 'Program Uji Publik',
            'description' => '<p>Deskripsi program uji.</p>',
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'location_text' => 'Karawang',
        ]);
        AdvocacyProgram::create([
            'program_code' => 'ORG-PRG-TEST2',
            'title' => 'Program Direncanakan',
            'description' => '<p>Isi.</p>',
            'status' => 'planned',
            'start_date' => now()->toDateString(),
        ]);

        $response = $this->get(route('advocacy-programs.index'));

        $response->assertOk();
        $response->assertSee('ORG-PRG-TEST');
        $response->assertSee('Program Uji Publik');
        $response->assertSee('Ringkasan status', false);
        $response->assertSee('2 program', false);
        $response->assertSee('Aktif', false);
        $response->assertSee('Direncanakan', false);
    }

    public function test_advocacy_program_show_renders(): void
    {
        AdvocacyProgram::create([
            'program_code' => 'ORG-PRG-DETAIL',
            'title' => 'Judul Program Detail',
            'description' => '<p>Paragraf deskripsi.</p>',
            'status' => 'completed',
            'start_date' => '2021-06-01',
            'end_date' => '2022-01-15',
            'location_text' => 'Basis uji',
        ]);

        $response = $this->get(route('advocacy-programs.show', 'ORG-PRG-DETAIL'));

        $response->assertOk();
        $response->assertSee('Judul Program Detail');
        $response->assertSee('Selesai');
    }

    public function test_posts_index_paginates_published_posts(): void
    {
        $author = User::factory()->create();
        for ($i = 1; $i <= 3; $i++) {
            Post::create([
                'title' => "Post {$i}",
                'slug' => "post-{$i}",
                'body' => "<p>{$i}</p>",
                'status' => 'published',
                'published_at' => now()->subDays($i),
                'author_id' => $author->id,
            ]);
        }

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee('Post 1');
        $response->assertSee('Post 3');
    }

    public function test_post_show_404_for_unpublished_slug(): void
    {
        $author = User::factory()->create();
        Post::create([
            'title' => 'Belum Tayang',
            'slug' => 'belum-tayang',
            'body' => '<p>x</p>',
            'status' => 'draft',
            'author_id' => $author->id,
        ]);

        $this->get(route('posts.show', 'belum-tayang'))->assertNotFound();
    }

    public function test_page_show_renders_published_page(): void
    {
        $author = User::factory()->create();
        Page::create([
            'title' => 'Tentang Uji',
            'slug' => 'tentang-uji',
            'body' => '<p>konten tentang</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
            'author_id' => $author->id,
        ]);

        $response = $this->get(route('pages.show', 'tentang-uji'));

        $response->assertOk();
        $response->assertSee('Tentang Uji');
        $response->assertSee('konten tentang', false);
    }

    public function test_sitemap_xml_renders(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
        $content = $response->getContent();
        $this->assertStringContainsString('kasus-agraria', $content);
        $this->assertStringContainsString('program-advokasi', $content);
    }

    public function test_rss_feed_renders(): void
    {
        $response = $this->get('/feed.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/rss+xml; charset=utf-8');
        $response->assertSee('<rss', false);
    }
}
