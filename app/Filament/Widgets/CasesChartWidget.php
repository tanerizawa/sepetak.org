<?php

namespace App\Filament\Widgets;

use App\Models\AgrarianCase;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class CasesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Kasus Agraria Dibuka (12 Bulan Terakhir)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '260px';

    /**
     * Dibuka  → flag-500 (merah brand, menandakan kasus masuk)
     * Ditutup → ochre-500 (emas bumi, aksen tani) agar tidak bentrok
     *           dengan badge "success" hijau pada tabel.
     */
    protected function getData(): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(11);
        $end = Carbon::now()->endOfMonth();

        $labels = [];
        $openedSeries = [];
        $closedSeries = [];

        $cursor = $start->copy();
        while ($cursor <= $end) {
            $month = $cursor->copy();
            $labels[] = $month->translatedFormat('M Y');

            $opened = AgrarianCase::query()
                ->whereBetween('start_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->count();

            $closed = AgrarianCase::query()
                ->whereBetween('closed_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->count();

            $openedSeries[] = $opened;
            $closedSeries[] = $closed;

            $cursor->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dibuka',
                    'data' => $openedSeries,
                    'backgroundColor' => 'rgba(200, 16, 46, 0.78)', // flag-500
                    'borderColor' => '#7E0A1E', // flag-700
                    'borderWidth' => 1.5,
                ],
                [
                    'label' => 'Ditutup / Selesai',
                    'data' => $closedSeries,
                    'backgroundColor' => 'rgba(201, 162, 39, 0.78)', // ochre-500 (#C9A227)
                    'borderColor' => '#7A5E11', // ochre-700
                    'borderWidth' => 1.5,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
