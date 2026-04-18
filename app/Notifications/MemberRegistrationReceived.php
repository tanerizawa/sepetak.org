<?php

namespace App\Notifications;

use App\Models\Member;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi untuk calon anggota setelah mengirim formulir pendaftaran.
 *
 * Diimplementasikan dengan ShouldQueue agar pengiriman SMTP tidak memblokir
 * request; menggunakan fallback sinkron jika queue worker belum berjalan.
 */
class MemberRegistrationReceived extends Notification implements ShouldQueue
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
        $contact  = SiteSetting::getValue('contact_email', 'info@sepetak.org');

        return (new MailMessage())
            ->subject('Pendaftaran Anggota Diterima — ' . $siteName)
            ->greeting('Salam juang, ' . $this->member->full_name . '!')
            ->line('Terima kasih telah mendaftar sebagai calon anggota ' . $siteName . '.')
            ->line('Kode pendaftaran Anda: **' . $this->member->member_code . '**')
            ->line('Formulir Anda sedang dalam proses verifikasi oleh Departemen Internal. Kami akan menghubungi Anda melalui email atau telepon setelah verifikasi selesai dan penempatan Pokja di wilayah Anda siap.')
            ->line('Jika ada pertanyaan, silakan balas email ini atau hubungi kami di ' . $contact . '.')
            ->salutation('Salam,' . "\n" . 'Sekretariat ' . $siteName);
    }
}
