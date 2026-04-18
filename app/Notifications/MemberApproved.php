<?php

namespace App\Notifications;

use App\Models\Member;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Member $member) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $siteName = SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang');

        return (new MailMessage)
            ->subject('Keanggotaan Anda Disetujui — '.$siteName)
            ->greeting('Salam juang, '.$this->member->full_name.'!')
            ->line('Selamat! Permohonan keanggotaan Anda telah disetujui oleh Sekretariat '.$siteName.'.')
            ->line('Kode Anggota Anda: **'.$this->member->member_code.'**')
            ->line('Mulai hari ini Anda resmi terdaftar sebagai anggota aktif. Informasi penempatan Pokja dan agenda organisasi akan disampaikan menyusul.')
            ->salutation('Salam juang,'."\n".'Sekretariat '.$siteName);
    }
}
