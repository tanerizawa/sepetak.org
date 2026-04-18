<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleImageService
{
    private const CIRCUIT_BREAKER_PREFIX = 'img_circuit_';
    private const CACHE_PREFIX = 'img_search_';
    private const CIRCUIT_THRESHOLD = 5;
    private const CIRCUIT_RESET_SECONDS = 300;
    /**
     * Known figures → Wikimedia Commons filenames for exact matches.
     */
    protected array $knownFigures = [
        'karl marx' => 'Karl Marx 001.jpg',
        'marx' => 'Karl Marx 001.jpg',
        'antonio gramsci' => 'Gramsci.png',
        'gramsci' => 'Gramsci.png',
        'david harvey' => 'David Harvey2.jpg',
        'james c. scott' => 'James C Scott 2016.jpg',
        'james scott' => 'James C Scott 2016.jpg',
        'rosa luxemburg' => 'Rosa Luxemburg.jpg',
        'luxemburg' => 'Rosa Luxemburg.jpg',
        'frantz fanon' => 'Frantz Fanon.jpg',
        'fanon' => 'Frantz Fanon.jpg',
        'paulo freire' => 'Paulo Freire.jpg',
        'freire' => 'Paulo Freire.jpg',
        'lenin' => 'Bundesarchiv Bild 183-71043-0003, Wladimir Iljitsch Lenin.jpg',
        'friedrich engels' => 'Friedrich Engels portrait (cropped).jpg',
        'engels' => 'Friedrich Engels portrait (cropped).jpg',
        'che guevara' => 'CheHigh.jpg',
        'mao zedong' => 'Mao Zedong portrait.jpg',
        'ho chi minh' => 'Ho Chi Minh 1946.jpg',
        'sukarno' => 'Presiden Sukarno.jpg',
        'soekarno' => 'Presiden Sukarno.jpg',
    ];

    /**
     * Topic-specific Wikimedia search terms for contextual accuracy.
     */
    protected array $topicQueries = [
        // Agrarian / farming
        'agraria' => ['rice paddy Indonesia', 'Javanese farmer', 'sawah Jawa', 'rice field Java'],
        'petani' => ['Indonesian farmer', 'rice farmer Java', 'peasant Indonesia'],
        'tani' => ['rice paddy Indonesia', 'farmer Java', 'agriculture Indonesia'],
        'sawah' => ['rice paddy Java', 'sawah Indonesia'],
        'tanah' => ['land reform Indonesia', 'agrarian reform'],
        'reforma' => ['land reform', 'agrarian reform Indonesia'],

        // Political economy
        'kapital' => ['capitalism illustration', 'Das Kapital', 'factory workers'],
        'eksploitasi' => ['labor exploitation', 'workers protest', 'factory labor'],
        'buruh' => ['labor movement Indonesia', 'Indonesian workers', 'labor protest'],
        'akumulasi' => ['primitive accumulation', 'enclosure movement'],
        'perampasan' => ['land grabbing', 'displacement protest', 'eviction protest'],
        'hegemoni' => ['cultural hegemony', 'Antonio Gramsci', 'political protest'],
        'perlawanan' => ['peasant resistance', 'social protest', 'demonstration Indonesia'],
        'resistensi' => ['resistance movement', 'protest rally', 'social movement'],

        // General
        'indonesia' => ['Indonesia rural', 'Java village', 'Indonesian countryside'],
        'jawa barat' => ['West Java landscape', 'Karawang rice field', 'Jawa Barat'],
        'karawang' => ['Karawang', 'rice field Karawang'],
    ];

    /**
     * Find and attach a relevant cover image to a post.
     * Tries providers in order: Wikimedia Commons → Pexels → Unsplash.
     */
    public function attachCoverImage(Post $post, ?string $searchQuery = null): bool
    {
        $figureImage = $this->matchKnownFigure($post);
        if ($figureImage) {
            Log::info('ArticleImageService: Matched known figure', [
                'post_id' => $post->id,
                'figure' => $figureImage['photographer'],
            ]);

            return $this->downloadAndAttach(
                $post,
                $figureImage['url'],
                $figureImage['photographer'],
                $figureImage['photographer_url'],
                'wikimedia'
            );
        }

        $queries = $searchQuery
            ? [$searchQuery]
            : $this->buildSearchQueries($post);

        $providers = $this->getOrderedProviders();

        foreach ($providers as [$provider, $apiKey]) {
            if ($this->isCircuitOpen($provider)) {
                continue;
            }

            foreach ($queries as $query) {
                $cacheKey = self::CACHE_PREFIX.md5("{$provider}:{$query}");
                $cached = Cache::get($cacheKey);
                if ($cached !== null) {
                    if ($cached === 'FAILED') {
                        continue;
                    }
                    Log::info('ArticleImageService: Cache hit', [
                        'post_id' => $post->id,
                        'provider' => $provider,
                        'query' => $query,
                    ]);
                    $result = $this->downloadAndAttach($post, $cached['url'], $cached['photographer'], $cached['photographer_url'], $provider);
                    if ($result) {
                        return true;
                    }
                    continue;
                }

                try {
                    $imageData = match ($provider) {
                        'wikimedia' => $this->searchWikimedia($query),
                        'pexels' => $this->searchPexels($query, $apiKey),
                        'unsplash' => $this->searchUnsplash($query, $apiKey),
                        default => null,
                    };

                    if ($imageData) {
                        Log::info('ArticleImageService: Found image', [
                            'post_id' => $post->id,
                            'provider' => $provider,
                            'query' => $query,
                        ]);
                        Cache::put($cacheKey, $imageData, now()->addHours(24));
                        $result = $this->downloadAndAttach($post, $imageData['url'], $imageData['photographer'], $imageData['photographer_url'], $provider);
                        if ($result) {
                            return true;
                        }
                    } else {
                        Cache::put($cacheKey, 'FAILED', now()->addMinutes(30));
                    }
                } catch (\Throwable $e) {
                    $this->recordFailure($provider);
                    Log::warning("ArticleImageService: {$provider} failed", [
                        'post_id' => $post->id,
                        'query' => $query,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        Log::info('ArticleImageService: No image found from any provider', [
            'post_id' => $post->id,
            'queries' => $queries,
        ]);

        return false;
    }

    private function isCircuitOpen(string $provider): bool
    {
        $key = self::CIRCUIT_BREAKER_PREFIX.$provider;
        return (int) Cache::get($key, 0) >= self::CIRCUIT_THRESHOLD;
    }

    private function recordFailure(string $provider): void
    {
        $key = self::CIRCUIT_BREAKER_PREFIX.$provider;
        $count = (int) Cache::get($key, 0) + 1;
        Cache::put($key, $count, now()->addSeconds(self::CIRCUIT_RESET_SECONDS));
    }

    /**
     * Check if the article title/topic mentions a known figure.
     */
    protected function matchKnownFigure(Post $post): ?array
    {
        $haystack = mb_strtolower($post->title.' '.($post->articleTopic?->title ?? ''));

        foreach ($this->knownFigures as $name => $filename) {
            if (str_contains($haystack, $name)) {
                $url = $this->getWikimediaFileUrl($filename);
                if ($url) {
                    return [
                        'url' => $url,
                        'photographer' => 'Wikimedia Commons',
                        'photographer_url' => 'https://commons.wikimedia.org/wiki/File:'.str_replace(' ', '_', $filename),
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Get direct image URL from Wikimedia Commons filename.
     */
    protected function getWikimediaFileUrl(string $filename): ?string
    {
        $response = Http::timeout(10)
            ->withHeaders(['User-Agent' => 'SepekatBot/1.0 (https://sepetak.org; redaksi@sepetak.org)'])
            ->get('https://commons.wikimedia.org/w/api.php', [
                'action' => 'query',
                'titles' => 'File:'.$filename,
                'prop' => 'imageinfo',
                'iiprop' => 'url|size',
                'iiurlwidth' => 1200,
                'format' => 'json',
            ]);

        if (! $response->successful()) {
            return null;
        }

        $pages = $response->json('query.pages', []);
        foreach ($pages as $page) {
            $info = $page['imageinfo'][0] ?? null;
            if ($info) {
                return $info['thumburl'] ?? $info['url'] ?? null;
            }
        }

        return null;
    }

    /**
     * Build multiple search queries ordered by specificity.
     */
    protected function buildSearchQueries(Post $post): array
    {
        $queries = [];
        $title = mb_strtolower($post->title);
        $topicTitle = mb_strtolower($post->articleTopic?->title ?? '');
        $combined = $title.' '.$topicTitle;

        // 1. Topic-specific queries from keyword matching
        foreach ($this->topicQueries as $keyword => $searchTerms) {
            if (str_contains($combined, $keyword)) {
                $queries[] = $searchTerms[array_rand($searchTerms)];
            }
        }

        // 2. Framework-based contextual query
        $topic = $post->articleTopic;
        if ($topic) {
            $frameworkQuery = match ($topic->thinking_framework) {
                'marxist' => 'Marxism workers labor movement',
                'neo_marxian' => 'social theory critical analysis protest',
                'critical_theory' => 'Frankfurt School critical theory',
                'ecopolitics' => 'environmental activism agriculture ecology',
                'human_rights' => 'human rights protest demonstration',
                'agrarian_political_economy' => 'peasant farmer agriculture rural',
                'postmodern' => 'postmodern culture society',
                default => null,
            };
            if ($frameworkQuery) {
                $queries[] = $frameworkQuery;
            }

            $typeQuery = match ($topic->article_type) {
                'thinker_profile' => 'philosopher portrait book',
                'policy_analysis' => 'government policy document',
                'historical_review' => 'historical photograph archive',
                'essay' => null,
                default => null,
            };
            if ($typeQuery) {
                $queries[] = $typeQuery;
            }
        }

        // 3. Title keywords extraction as last resort
        $queries[] = $this->extractKeywords($title);

        // 4. Generic Indonesia agrarian fallback
        $queries[] = 'rice paddy farmer Indonesia';

        return array_values(array_unique(array_filter($queries)));
    }

    /**
     * Get providers in priority order: Wikimedia (free) → Pexels → Unsplash.
     */
    protected function getOrderedProviders(): array
    {
        $order = config('article-generator.image_provider_order', ['wikimedia', 'pexels', 'unsplash']);
        $order = array_values(array_unique(array_map(
            fn ($v) => mb_strtolower(trim((string) $v)),
            is_array($order) ? $order : [$order]
        )));

        if (! in_array('wikimedia', $order, true)) {
            array_unshift($order, 'wikimedia');
        }

        $providers = [];

        foreach ($order as $provider) {
            if ($provider === 'wikimedia') {
                $providers[] = ['wikimedia', null];
                continue;
            }

            if ($provider === 'pexels') {
                if (! config('article-generator.pexels.enabled', true)) {
                    continue;
                }
                $pexels = (string) config('article-generator.pexels.api_key');
                if ($pexels !== '') {
                    $providers[] = ['pexels', $pexels];
                }
                continue;
            }

            if ($provider === 'unsplash') {
                if (! config('article-generator.unsplash.enabled', true)) {
                    continue;
                }
                $unsplash = (string) config('article-generator.unsplash.access_key');
                if ($unsplash !== '') {
                    $providers[] = ['unsplash', $unsplash];
                }
                continue;
            }
        }

        return $providers;
    }

    protected function extractKeywords(string $title): string
    {
        $stopWords = ['dan', 'di', 'dari', 'yang', 'untuk', 'dengan', 'dalam', 'ke', 'pada',
            'oleh', 'sebagai', 'tentang', 'atas', 'ini', 'itu', 'terhadap', 'bagi', 'ala',
            'melalui', 'antara', 'serta', 'kondisi', 'konteks', 'analisis', 'relevansi',
            'strategi', 'teori', 'konsep', 'kontemporer', 'perspektif', 'realitas'];
        $words = preg_split('/[\s:,\-]+/', mb_strtolower($title));
        $keywords = array_diff($words, $stopWords);
        $keywords = array_filter($keywords, fn ($w) => mb_strlen($w) > 3);

        return implode(' ', array_slice(array_values($keywords), 0, 4));
    }

    // ── Provider: Wikimedia Commons ──

    protected function searchWikimedia(string $query): ?array
    {
        $response = Http::timeout(15)
            ->withHeaders(['User-Agent' => 'SepekatBot/1.0 (https://sepetak.org; redaksi@sepetak.org)'])
            ->get('https://commons.wikimedia.org/w/api.php', [
                'action' => 'query',
                'generator' => 'search',
                'gsrsearch' => $query.' filetype:bitmap',
                'gsrnamespace' => 6, // File namespace
                'gsrlimit' => 8,
                'prop' => 'imageinfo',
                'iiprop' => 'url|size|mime|extmetadata',
                'iiurlwidth' => 1200,
                'format' => 'json',
            ]);

        if (! $response->successful()) {
            Log::warning('ArticleImageService: Wikimedia API error', [
                'status' => $response->status(),
                'query' => $query,
            ]);

            return null;
        }

        $pages = $response->json('query.pages', []);
        if (empty($pages)) {
            return null;
        }

        // Filter for landscape images with reasonable size
        $candidates = [];
        foreach ($pages as $page) {
            $info = $page['imageinfo'][0] ?? null;
            if (! $info) {
                continue;
            }

            $mime = $info['mime'] ?? '';
            if (! str_starts_with($mime, 'image/')) {
                continue;
            }

            // Prefer landscape or square images
            $width = $info['width'] ?? 0;
            $height = $info['height'] ?? 0;
            if ($width < 400 || $height < 300) {
                continue;
            }

            $meta = $info['extmetadata'] ?? [];
            $artist = $meta['Artist']['value'] ?? 'Wikimedia Commons';
            $artist = strip_tags($artist);

            $candidates[] = [
                'url' => $info['thumburl'] ?? $info['url'],
                'photographer' => $artist,
                'photographer_url' => $info['descriptionurl'] ?? '',
                'ratio' => $width / max($height, 1),
            ];
        }

        if (empty($candidates)) {
            return null;
        }

        // Prefer landscape images (ratio > 1.2)
        usort($candidates, fn ($a, $b) => $b['ratio'] <=> $a['ratio']);

        return $candidates[0];
    }

    // ── Provider: Pexels ──

    protected function searchPexels(string $query, string $apiKey): ?array
    {
        $response = Http::timeout(15)
            ->withHeaders(['Authorization' => $apiKey])
            ->get('https://api.pexels.com/v1/search', [
                'query' => $query,
                'per_page' => 5,
                'orientation' => 'landscape',
                'size' => 'large',
            ]);

        if (! $response->successful()) {
            Log::warning('ArticleImageService: Pexels API error', [
                'status' => $response->status(),
                'query' => $query,
            ]);

            return null;
        }

        $photos = $response->json('photos', []);

        if (empty($photos)) {
            return null;
        }

        $photo = $photos[array_rand($photos)];

        return [
            'url' => $photo['src']['large2x'] ?? $photo['src']['large'] ?? $photo['src']['original'],
            'photographer' => $photo['photographer'] ?? 'Unknown',
            'photographer_url' => $photo['photographer_url'] ?? '',
        ];
    }

    // ── Provider: Unsplash ──

    protected function searchUnsplash(string $query, string $apiKey): ?array
    {
        $response = Http::timeout(15)
            ->withHeaders(['Authorization' => "Client-ID {$apiKey}"])
            ->get('https://api.unsplash.com/search/photos', [
                'query' => $query,
                'per_page' => 5,
                'orientation' => 'landscape',
                'content_filter' => 'high',
            ]);

        if (! $response->successful()) {
            Log::warning('ArticleImageService: Unsplash API error', [
                'status' => $response->status(),
                'query' => $query,
            ]);

            return null;
        }

        $results = $response->json('results', []);

        if (empty($results)) {
            return null;
        }

        $photo = $results[array_rand($results)];

        // Trigger download endpoint (required by Unsplash API guidelines)
        $downloadUrl = $photo['links']['download_location'] ?? null;
        if ($downloadUrl) {
            Http::withHeaders(['Authorization' => "Client-ID {$apiKey}"])
                ->get($downloadUrl);
        }

        return [
            'url' => $photo['urls']['regular'] ?? $photo['urls']['small'],
            'photographer' => $photo['user']['name'] ?? 'Unknown',
            'photographer_url' => $photo['user']['links']['html'] ?? '',
        ];
    }

    // ── Download & Attach ──

    protected function downloadAndAttach(Post $post, string $imageUrl, string $photographer, string $photographerUrl, string $source = 'wikimedia'): bool
    {
        // Reject non-HTTPS to avoid MITM of AI-generated URLs.
        if (! str_starts_with($imageUrl, 'https://')) {
            return false;
        }

        $response = Http::timeout(30)
            ->withHeaders(['User-Agent' => 'SepekatBot/1.0 (https://sepetak.org; redaksi@sepetak.org)'])
            ->get($imageUrl);

        if (! $response->successful()) {
            return false;
        }

        // S-8: validate MIME & size before persisting.
        $contentType = (string) $response->header('Content-Type');
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mimeMatch = false;
        foreach ($allowedMimes as $mime) {
            if (str_starts_with($contentType, $mime)) {
                $mimeMatch = true;
                break;
            }
        }
        if (! $mimeMatch) {
            return false;
        }

        $maxBytes = 10 * 1024 * 1024; // 10 MB
        if (strlen($response->body()) > $maxBytes) {
            return false;
        }

        $extension = 'jpg';
        if (str_contains($contentType, 'png')) {
            $extension = 'png';
        } elseif (str_contains($contentType, 'webp')) {
            $extension = 'webp';
        } elseif (str_contains($contentType, 'gif')) {
            $extension = 'gif';
        }

        $filename = 'cover-'.Str::slug($post->title).'-'.Str::random(6).'.'.$extension;
        $tmp = tempnam(sys_get_temp_dir(), 'sepetak-cover-');
        if ($tmp === false) {
            return false;
        }

        $tempPath = $tmp.'.'.$extension;
        @rename($tmp, $tempPath);
        file_put_contents($tempPath, $response->body());

        try {
            $post->addMedia($tempPath)
                ->usingFileName($filename)
                ->withCustomProperties([
                    'photographer' => $photographer,
                    'photographer_url' => $photographerUrl,
                    'source' => $source,
                ])
                ->toMediaCollection('cover');

            return true;
        } finally {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }
}
