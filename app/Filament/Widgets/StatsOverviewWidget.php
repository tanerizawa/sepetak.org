<?php

namespace App\Filament\Widgets;

use App\Models\AdvocacyProgram;
use App\Models\AgrarianCase;
use App\Models\Member;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $counts = Cache::remember('admin.stats.overview', 300, fn () => [
            'members_active' => Member::where('status', 'active')->count(),
            'cases_active' => AgrarianCase::whereNotIn('status', ['resolved', 'closed'])->count(),
            'programs_active' => AdvocacyProgram::where('status', 'active')->count(),
            'posts_published' => Post::where('status', 'published')->count(),
        ]);

        return [
            Stat::make('Total Anggota Aktif', $counts['members_active'])
                ->description('Anggota dengan status aktif')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Total Kasus Aktif', $counts['cases_active'])
                ->description('Kasus yang sedang berjalan')
                ->icon('heroicon-o-scale')
                ->color('warning'),

            Stat::make('Program Advokasi Aktif', $counts['programs_active'])
                ->description('Program yang sedang aktif')
                ->icon('heroicon-o-megaphone')
                ->color('info'),

            Stat::make('Artikel Dipublikasikan', $counts['posts_published'])
                ->description('Artikel yang sudah dipublikasikan')
                ->icon('heroicon-o-newspaper')
                ->color('primary'),
        ];
    }
}
