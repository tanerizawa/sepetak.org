<?php

namespace App\Http\Controllers;

use App\Models\AdvocacyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdvocacyProgramPublicController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $programs = AdvocacyProgram::query()
            ->orderByRaw("CASE status WHEN 'active' THEN 0 WHEN 'planned' THEN 1 WHEN 'paused' THEN 2 ELSE 3 END")
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
        if ($programs->isEmpty() && $programs->currentPage() > 1) {
            return redirect()->to($programs->url($programs->lastPage()));
        }

        return view('advocacy-programs.index', [
            'programs' => $programs,
            'statusLabels' => self::statusLabels(),
            'statusCounts' => self::publicStatusCounts(),
        ]);
    }

    public function show(string $program_code): View
    {
        $program = AdvocacyProgram::query()
            ->where('program_code', $program_code)
            ->with([
                'actions' => fn ($q) => $q->orderByDesc('action_date')->orderByDesc('id')->limit(50),
            ])
            ->firstOrFail();

        return view('advocacy-programs.show', [
            'program' => $program,
            'statusLabels' => self::statusLabels(),
            'actionTypeLabels' => self::actionTypeLabels(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            'planned' => 'Direncanakan',
            'active' => 'Aktif',
            'paused' => 'Ditangguhkan',
            'completed' => 'Selesai',
        ];
    }

    /**
     * @return array<string, int>
     */
    public static function publicStatusCounts(): array
    {
        $aggregates = AdvocacyProgram::query()
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $out = [];
        foreach (array_keys(self::statusLabels()) as $status) {
            $out[$status] = (int) ($aggregates[$status] ?? 0);
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function actionTypeLabels(): array
    {
        return [
            'meeting' => 'Rapat',
            'training' => 'Pelatihan',
            'campaign' => 'Kampanye',
            'field_visit' => 'Kunjungan lapangan',
            'legal' => 'Proses hukum',
            'other' => 'Lainnya',
        ];
    }
}
