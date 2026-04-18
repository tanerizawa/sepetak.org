<?php

namespace App\Services\Waha;

use Illuminate\Http\Client\RequestException;

final class WahaException extends \RuntimeException
{
    public static function fromResponse(RequestException $e): self
    {
        $body = $e->response?->body() ?? '';
        $status = $e->response?->status() ?? 0;
        $message = trim("WAHA HTTP {$status}: ".($body !== '' ? $body : $e->getMessage()));

        return new self($message, (int) $status, $e);
    }

    public static function notConfigured(): self
    {
        return new self('WAHA tidak diaktifkan atau WAHA_BASE_URL / WAHA_API_KEY belum diisi.');
    }

    public static function invalidPhone(string $detail = ''): self
    {
        return new self('Nomor tidak valid untuk chatId WhatsApp. '.$detail);
    }
}
