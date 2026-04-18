<?php

namespace App\Notifications;

use App\Models\Member;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi internal ke admin/operator saat ada pendaftaran anggota baru.
 */
class AdminNewMemberRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Member $member)
    {
    }

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $siteName = SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang');

        return (new MailMessage())
            ->subject('[Admin] Pendaftaran Anggota Baru — ' . ($this->member->member_code ?? ''))
            ->greeting('Halo Admin ' . $siteName)
            ->line('Ada pendaftaran anggota baru yang menunggu verifikasi:')
            ->line('**Kode:** ' . ($this->member->member_code ?? '-'))
            ->line('**Nama:** ' . $this->member->full_name)
            ->line('**Email:** ' . ($this->member->email ?? '-'))
            ->line('**Telepon:** ' . ($this->member->phone ?? '-'))
            ->line('**Didaftarkan pada:** ' . optional($this->member->created_at)->translatedFormat('d F Y H:i'))
            ->action('Buka Panel Admin', url('/admin/members'))
            ->line('Harap lakukan verifikasi dan penempatan Pokja sesuai prosedur Departemen Internal.');
    }
}
