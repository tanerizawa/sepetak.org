<?php

namespace App\Observers;

use App\Models\AgrarianCase;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\AgrarianCaseStatusChanged;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AgrarianCaseObserver
{
    /**
     * Dipanggil setiap kali AgrarianCase ter-update.
     *
     * Saat kolom `status` benar-benar berubah, kirim email ke:
     *  - Penanggung jawab (leadUser) bila ada.
     *  - Semua user aktif ber-role superadmin/admin/operator.
     *  - Alamat contact_email dari SiteSetting bila tidak duplikat.
     *
     * Gagal kirim tidak boleh membatalkan transaksi — di-log saja.
     */
    public function updated(AgrarianCase $case): void
    {
        if (! $case->wasChanged('status')) {
            return;
        }

        $previous = $case->getOriginal('status');
        $new = $case->status;

        try {
            $notification = new AgrarianCaseStatusChanged($case, $previous, $new);

            $internalUsers = User::query()
                ->where('is_active', true)
                ->whereHas('roles', fn ($q) => $q->whereIn('name', ['superadmin', 'admin', 'operator']))
                ->get();

            if ($case->leadUser && $case->leadUser->is_active && ! empty($case->leadUser->email)) {
                if ($internalUsers->where('id', $case->leadUser->id)->isEmpty()) {
                    Notification::send($case->leadUser, $notification);
                }
            }

            if ($internalUsers->isNotEmpty()) {
                Notification::send($internalUsers, $notification);
            }

            $contactEmail = SiteSetting::getValue('contact_email');
            if (! empty($contactEmail)
                && $internalUsers->where('email', $contactEmail)->isEmpty()
                && (! $case->leadUser || $case->leadUser->email !== $contactEmail)
            ) {
                Notification::route('mail', $contactEmail)->notify($notification);
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim notifikasi perubahan status kasus: '.$e->getMessage(), [
                'case_id' => $case->id,
            ]);
        }
    }
}
