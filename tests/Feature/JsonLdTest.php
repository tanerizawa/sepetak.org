<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JsonLdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ekstrak seluruh blok JSON-LD dari HTML response dan decode.
     *
     * @return array<int, array<string, mixed>>
     */
    private function extractJsonLd(string $html): array
    {
        preg_match_all(
            '#<script type="application/ld\+json">\s*(.*?)\s*</script>#s',
            $html,
            $matches
        );

        $blocks = [];
        foreach ($matches[1] as $raw) {
            $decoded = json_decode($raw, true);
            $this->assertSame(
                JSON_ERROR_NONE,
                json_last_error(),
                'Invalid JSON-LD block: ' . json_last_error_msg() . "\n---\n" . $raw
            );
            $blocks[] = $decoded;
        }

        return $blocks;
    }

    public function test_homepage_contains_organization_and_website_jsonld(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        $blocks = $this->extractJsonLd($response->getContent());
        $types  = array_column($blocks, '@type');

        $this->assertContains('Organization', $types, 'Organization JSON-LD harus tersedia di beranda.');
        $this->assertContains('WebSite', $types, 'WebSite JSON-LD harus tersedia di beranda.');

        $org = collect($blocks)->firstWhere('@type', 'Organization');
        $this->assertSame('https://schema.org', $org['@context']);
        $this->assertNotEmpty($org['name']);
        $this->assertSame('2007-12-10', $org['foundingDate']);
        $this->assertContains('SEPETAK', $org['alternateName']);
        $this->assertContains('Serikat Pekerja Tani Karawang', $org['alternateName']);
    }

    public function test_post_show_contains_news_article_and_breadcrumb_jsonld(): void
    {
        $author = User::factory()->create(['name' => 'Penulis Uji']);

        $post = Post::create([
            'title'        => 'Post JSON-LD Uji',
            'slug'         => 'post-jsonld-uji',
            'excerpt'      => 'Ringkasan uji coba JSON-LD.',
            'body'         => '<p>Body uji.</p>',
            'status'       => 'published',
            'published_at' => now()->subHour(),
            'author_id'    => $author->id,
        ]);

        $response = $this->get(route('posts.show', $post->slug));
        $response->assertOk();

        $blocks = $this->extractJsonLd($response->getContent());
        $types  = array_column($blocks, '@type');

        $this->assertContains('Organization', $types);
        $this->assertContains('NewsArticle', $types);
        $this->assertContains('BreadcrumbList', $types);

        $article = collect($blocks)->firstWhere('@type', 'NewsArticle');
        $this->assertSame('Post JSON-LD Uji', $article['headline']);
        $this->assertSame('Ringkasan uji coba JSON-LD.', $article['description']);
        $this->assertSame('Penulis Uji', $article['author']['name']);
        $this->assertNotEmpty($article['datePublished']);
        $this->assertNotEmpty($article['dateModified']);

        $breadcrumb = collect($blocks)->firstWhere('@type', 'BreadcrumbList');
        $this->assertCount(3, $breadcrumb['itemListElement']);
        $this->assertSame('Beranda', $breadcrumb['itemListElement'][0]['name']);
        $this->assertSame('Artikel', $breadcrumb['itemListElement'][1]['name']);
        $this->assertSame($post->title, $breadcrumb['itemListElement'][2]['name']);
    }
}
