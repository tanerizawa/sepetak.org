<?php

namespace App\Services\Waha;

/**
 * Normalisasi nomor Indonesia → chatId WAHA (`digits@c.us`).
 *
 * @see https://waha.devlike.pro/docs/how-to/send-messages/
 */
final class MemberPhone
{
    public static function toChatId(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '8') && ! str_starts_with($digits, '62')) {
            $digits = '62'.$digits;
        }

        if (strlen($digits) < 10 || strlen($digits) > 15) {
            return null;
        }

        return $digits.'@c.us';
    }
}
