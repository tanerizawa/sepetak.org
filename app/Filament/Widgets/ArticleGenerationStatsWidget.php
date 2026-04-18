<?php

namespace App\Filament\Widgets;

use App\Models\ArticleGenerationLog;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArticleGenerationStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $thisMonth = now()->startOfMonth();

        $completed = ArticleGenerationLog::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->count();

        $failed = ArticleGenerationLog::where('status', 'failed')
            ->where('created_at', '>=', $thisMonth)
            ->count();

        $tokens = ArticleGenerationLog::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('tokens_used');

        $pendingReview = Post::where('source_type', 'auto_generated')
            ->where('status', 'draft')
            ->count();

        return [
            Stat::make('Artikel AI (Bulan Ini)', $completed)
                ->description($failed > 0 ? "{$failed} gagal" : 'Semua sukses')
                ->descriptionIcon($failed > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle')
                ->color($failed > 0 ? 'warning' : 'success'),

            Stat::make('Token Terpakai', number_format($tokens))
                ->description('Bulan ini')
                ->icon('heroicon-o-cpu-chip'),

            Stat::make('Menunggu Review', $pendingReview)
                ->description('Artikel AI draft')
                ->color($pendingReview > 0 ? 'warning' : 'gray')
                ->icon('heroicon-o-clock'),
        ];
    }
}
