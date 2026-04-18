<?php

namespace App\Http\Controllers;

use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\Category;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /** @var string Bump when struktur array statistik berubah (hindari 500 dari cache bentuk lama). */
    private const STATS_CACHE_KEY = 'homepage.stats.v2';
    private const CATEGORIES_CACHE_KEY = 'homepage.article_categories.v2';

    public function index(Request $request)
    {
        $abEnabled = (bool) config('sepetak.homepage_ab_enabled', false);
        $forced = trim((string) $request->query('ab', ''));
        $cookieVariant = trim((string) $request->cookie('home_ab_variant', ''));
        $defaultVariant = (string) config('sepetak.homepage_variant', 'legacy');
        $variant = $forced !== ''
            ? $forced
            : ($abEnabled && $cookieVariant !== '' ? $cookieVariant : $defaultVariant);

        if (! in_array($variant, ['modern', 'legacy'], true)) {
            $variant = 'legacy';
        }

        if ($abEnabled && $forced === '' && $cookieVariant === '') {
            $variant = random_int(0, 1) === 0 ? 'modern' : 'legacy';
        }

        $articleCategories = collect();
        $articlePage = null;
        $latestPosts = collect();
        $stats = $this->normalizeHomepageStats([]);
        $activeHomeCategory = trim((string) $request->query('category', ''));

        try {
            $latestPosts = Post::published()->latest('published_at')->limit(3)->get();

            $articleCategories = Cache::remember(self::CATEGORIES_CACHE_KEY, 600, function () {
                $top = Category::query()
                    ->whereHas('posts', fn ($q) => $q->published())
                    ->withCount(['posts as published_posts_count' => fn ($q) => $q->published()])
                    ->orderByDesc('published_posts_count')
                    ->limit(6)
                    ->get()
                    ->keyBy('slug');

                $extra = Category::query()
                    ->where('slug', 'kajian-ilmiah')
                    ->withCount(['posts as published_posts_count' => fn ($q) => $q->published()])
                    ->first();

                if ($extra) {
                    $top->put($extra->slug, $extra);
                }

                return $top->values();
            });

            $articleQuery = Post::published()
                ->with(['categories:id,name,slug', 'media'])
                ->latest('published_at');

            if ($activeHomeCategory !== '') {
                $articleQuery->whereHas('categories', fn ($q) => $q->where('slug', $activeHomeCategory));
            }

            $articlePage = $articleQuery->paginate(6)->withQueryString();

            $raw = Cache::remember(self::STATS_CACHE_KEY, 300, function () {
                return [
                    'member_count' => Member::where('status', 'active')->count(),
                    'cases_active' => AgrarianCase::whereNotIn('status', ['resolved', 'closed'])->count(),
                    'cases_total' => AgrarianCase::count(),
                    /** @deprecated Gunakan cases_active; dipertahankan untuk kompatibilitas */
                    'case_count' => AgrarianCase::whereNotIn('status', ['resolved', 'closed'])->count(),
                    'program_count' => AdvocacyProgram::where('status', 'active')->count(),
                ];
            });

            $stats = $this->normalizeHomepageStats(is_array($raw) ? $raw : []);
        } catch (\Throwable $e) {
            report($e);
        }

        if (! config('sepetak.use_real_member_count_on_homepage')) {
            $stats['member_count'] = (int) config('sepetak.homepage_member_count_display');
        }

        $view = $variant === 'legacy' ? 'home_legacy' : 'home';

        $response = response()->view($view, [
            'stats' => $stats,
            'latestPosts' => $latestPosts,
            'articleCategories' => $articleCategories,
            'articlePage' => $articlePage,
            'activeHomeCategory' => $activeHomeCategory,
        ]);

        if ($abEnabled || $forced !== '') {
            $response->cookie('home_ab_variant', $variant, 60 * 24 * 14);
        }

        return $response;
    }

    public function articles(Request $request)
    {
        $category = trim((string) $request->query('category', ''));
        $perPage = 6;

        $query = Post::published()
            ->with(['categories:id,name,slug', 'media'])
            ->latest('published_at');

        if ($category !== '') {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
        }

        $page = $query->paginate($perPage);

        $html = view('partials.home-article-cards', [
            'posts' => collect($page->items()),
        ])->render();

        return response()->json([
            'html' => $html,
            'next_page_url' => $page->nextPageUrl(),
            'current_page' => $page->currentPage(),
            'last_page' => $page->lastPage(),
            'total' => $page->total(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $cached
     * @return array{member_count: int, cases_active: int, cases_total: int, case_count: int, program_count: int}
     */
    private function normalizeHomepageStats(array $cached): array
    {
        $defaults = [
            'member_count' => 0,
            'cases_active' => 0,
            'cases_total' => 0,
            'case_count' => 0,
            'program_count' => 0,
        ];

        $stats = array_merge($defaults, $cached);

        if (! array_key_exists('cases_active', $cached) && array_key_exists('case_count', $cached)) {
            $stats['cases_active'] = (int) $cached['case_count'];
        }

        if (! array_key_exists('cases_total', $cached)) {
            $stats['cases_total'] = max(
                (int) $stats['cases_total'],
                (int) $stats['cases_active'],
                (int) $stats['case_count']
            );
        }

        $stats['case_count'] = (int) $stats['cases_active'];

        foreach (['member_count', 'cases_active', 'cases_total', 'case_count', 'program_count'] as $k) {
            $stats[$k] = (int) ($stats[$k] ?? 0);
        }

        return $stats;
    }
}
