<?php

namespace App\Http\Controllers;

use App\Models\AgrarianCase;
use Illuminate\View\View;

class AgrarianCasePublicController extends Controller
{
    public function index(): View
    {
        $cases = AgrarianCase::query()
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('agrarian-cases.index', [
            'cases' => $cases,
            'statusLabels' => self::statusLabels(),
        ]);
    }

    public function show(string $case_code): View
    {
        $case = AgrarianCase::query()->where('case_code', $case_code)->firstOrFail();

        return view('agrarian-cases.show', [
            'case' => $case,
            'statusLabels' => self::statusLabels(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            'reported' => 'Dilaporkan',
            'under_review' => 'Ditinjau',
            'mediation' => 'Mediasi',
            'legal_process' => 'Proses hukum',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];
    }
}
