<?php

namespace App\Notifications;

use App\Models\AgrarianCase;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Email ke penanggung jawab (leadUser) saat status kasus agraria berubah.
 *
 * Digunakan juga untuk notifikasi internal ke admin/superadmin agar semua
 * perubahan status kasus agraria ter-track di inbox — analog dengan
 * pola notifikasi pendaftaran anggota.
 */
class AgrarianCaseStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly AgrarianCase $case,
        public readonly ?string $previousStatus,
        public readonly string $newStatus,
        public readonly ?string $note = null,
    ) {
    }

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $siteName = SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang');

        $prev = $this->previousStatus ? $this->humanize($this->previousStatus) : '—';
        $next = $this->humanize($this->newStatus);

        $mail = (new MailMessage())
            ->subject('[Kasus Agraria] Status berubah: '.$this->case->case_code.' — '.$next)
            ->greeting('Salam juang')
            ->line('Terdapat perubahan status pada kasus agraria yang Anda tangani:')
            ->line('**Kode Kasus:** '.$this->case->case_code)
            ->line('**Judul:** '.$this->case->title)
            ->line('**Lokasi:** '.($this->case->location_text ?: '-'))
            ->line('**Status sebelumnya:** '.$prev)
            ->line('**Status terbaru:** '.$next);

        if (! empty($this->note)) {
            $mail->line('**Catatan:** '.$this->note);
        }

        return $mail
            ->action('Buka Kasus di Panel Admin', url('/admin/agrarian-cases/'.$this->case->getKey().'/edit'))
            ->line('Harap lakukan tindak lanjut sesuai SOP Departemen Advokasi '.$siteName.'.')
            ->salutation('Sekretariat '.$siteName);
    }

    private function humanize(string $status): string
    {
        return match ($status) {
            'reported' => 'Dilaporkan',
            'under_review' => 'Ditinjau',
            'mediation' => 'Mediasi',
            'legal_process' => 'Proses Hukum',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
            default => $status,
        };
    }
}
