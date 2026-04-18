<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;

class MemberCardController extends Controller
{
    /**
     * Render preview Kartu Tanda Anggota (KTA) sebagai halaman HTML
     * dengan CSS print-ready (ukuran kartu ATM 85.6 × 54 mm).
     *
     * Authorisasi dilakukan oleh middleware `can:manage-members` di route.
     */
    public function show(Member $member)
    {
        abort_unless(
            auth()->check() && auth()->user()->hasAnyRole(['superadmin', 'admin', 'secretary']),
            403
        );

        return view('admin.members.card', [
            'member' => $member->load('address', 'approvedBy'),
        ]);
    }
}
