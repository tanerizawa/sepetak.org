<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgrarianCase;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function membersPdf(Request $request)
    {
        $this->authorizeAdmin($request);

        $members = Member::query()
            ->with('address')
            ->orderBy('member_code')
            ->get();

        $summary = [
            'total'    => $members->count(),
            'active'   => $members->where('status', 'active')->count(),
            'pending'  => $members->where('status', 'pending')->count(),
            'inactive' => $members->where('status', 'inactive')->count(),
            'male'     => $members->where('gender', 'male')->count(),
            'female'   => $members->where('gender', 'female')->count(),
        ];

        $pdf = Pdf::loadView('exports.pdf.members', [
            'members'     => $members,
            'summary'     => $summary,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekap-anggota-sepetak-' . now()->format('Ymd-His') . '.pdf');
    }

    public function agrarianCasesPdf(Request $request)
    {
        $this->authorizeAdmin($request);

        $cases = AgrarianCase::query()
            ->with('leadUser')
            ->orderBy('case_code')
            ->get();

        $summary = [
            'total'         => $cases->count(),
            'reported'      => $cases->where('status', 'reported')->count(),
            'under_review'  => $cases->where('status', 'under_review')->count(),
            'mediation'     => $cases->where('status', 'mediation')->count(),
            'legal_process' => $cases->where('status', 'legal_process')->count(),
            'resolved'      => $cases->where('status', 'resolved')->count(),
            'closed'        => $cases->where('status', 'closed')->count(),
        ];

        $pdf = Pdf::loadView('exports.pdf.agrarian-cases', [
            'cases'       => $cases,
            'summary'     => $summary,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekap-kasus-agraria-sepetak-' . now()->format('Ymd-His') . '.pdf');
    }

    private function authorizeAdmin(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['superadmin', 'admin', 'operator']),
            403,
            'Anda tidak memiliki akses untuk mengunduh rekap ini.'
        );
    }
}
