<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MembersChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran Anggota (12 Bulan Terakhir)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '260px';

    /**
     * Warna disamakan dengan palet "Tani Merah":
     * - Pendaftaran masuk   → flag (merah brand)
     * - Disetujui/aktif     → ink (netral tegas) agar tidak bertabrakan semantik
     *                          "positif" dengan warna danger di badge tabel.
     * Referensi token: tailwind.config.js (flag-500 #C8102E, ink-700 #2B2B2B).
     */
    protected function getData(): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(11);
        $end = Carbon::now()->endOfMonth();

        $labels = [];
        $approvedSeries = [];
        $registeredSeries = [];

        $cursor = $start->copy();
        while ($cursor <= $end) {
            $month = $cursor->copy();
            $labels[] = $month->translatedFormat('M Y');

            $registered = Member::query()
                ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->count();

            $approved = Member::query()
                ->whereBetween('approved_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->count();

            $registeredSeries[] = $registered;
            $approvedSeries[] = $approved;

            $cursor->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendaftaran',
                    'data' => $registeredSeries,
                    'backgroundColor' => 'rgba(200, 16, 46, 0.18)', // flag-500 @ 18%
                    'borderColor' => '#C8102E',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => '#C8102E',
                ],
                [
                    'label' => 'Disetujui',
                    'data' => $approvedSeries,
                    'backgroundColor' => 'rgba(43, 43, 43, 0.12)', // ink-700 @ 12%
                    'borderColor' => '#2B2B2B',
                    'borderWidth' => 2,
                    'borderDash' => [4, 3],
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 3,
                    'pointBackgroundColor' => '#2B2B2B',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
