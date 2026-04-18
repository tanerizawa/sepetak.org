<?php

namespace Tests\Unit\Services;

use App\Models\Post;
use App\Services\ArticleImageService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TestableArticleImageService extends ArticleImageService
{
    public function orderedProviders(): array
    {
        return $this->getOrderedProviders();
    }
}

class ArticleImageServiceTest extends TestCase
{
    public function test_get_ordered_providers_uses_configured_order_and_keys(): void
    {
        config([
            'article-generator.image_provider_order' => ['unsplash', 'pexels', 'wikimedia'],
            'article-generator.unsplash.enabled' => true,
            'article-generator.unsplash.access_key' => 'u-key',
            'article-generator.pexels.enabled' => true,
            'article-generator.pexels.api_key' => 'p-key',
        ]);

        $service = new TestableArticleImageService();

        $this->assertSame([
            ['unsplash', 'u-key'],
            ['pexels', 'p-key'],
            ['wikimedia', null],
        ], $service->orderedProviders());
    }

    public function test_unsplash_disabled_does_not_disable_entire_cover_attachment(): void
    {
        Cache::flush();

        config(['logging.default' => 'null']);

        config([
            'article-generator.unsplash.enabled' => false,
            'article-generator.unsplash.access_key' => 'u-key',
            'article-generator.pexels.enabled' => false,
            'article-generator.pexels.api_key' => 'p-key',
            'article-generator.image_provider_order' => ['unsplash', 'wikimedia', 'pexels'],
        ]);

        $service = new class extends ArticleImageService {
            protected function searchWikimedia(string $query): ?array
            {
                return [
                    'url' => 'https://example.test/img.jpg',
                    'photographer' => 'Wikimedia Commons',
                    'photographer_url' => 'https://commons.wikimedia.org/wiki/File:Example.jpg',
                ];
            }

            protected function downloadAndAttach(Post $post, string $imageUrl, string $photographer, string $photographerUrl, string $source = 'wikimedia'): bool
            {
                return $source === 'wikimedia';
            }
        };

        $post = new Post([
            'title' => 'Judul Uji',
            'status' => 'draft',
        ]);

        $this->assertTrue($service->attachCoverImage($post, 'uji'));
    }
}
