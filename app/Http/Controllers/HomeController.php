<?php

namespace App\Http\Controllers;

use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /** @var string Bump when struktur array statistik berubah (hindari 500 dari cache bentuk lama). */
    private const STATS_CACHE_KEY = 'homepage.stats.v2';

    public function index()
    {
        $latestPosts = collect();
        $stats = $this->normalizeHomepageStats([]);

        try {
            $latestPosts = Post::published()->latest('published_at')->limit(3)->get();

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

        return view('home', compact('latestPosts', 'stats'));
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
