<?php

namespace App\Services\Waha;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Klien HTTP tipis untuk [WAHA](https://github.com/devlikeapro/waha) — sesi, kirim teks.
 */
final class WahaClient
{
    public function isConfigured(): bool
    {
        return config('waha.enabled', false)
            && filled(config('waha.base_url'))
            && filled(config('waha.api_key'));
    }

    /**
     * @return array<string, mixed>
     */
    public function listSessions(): array
    {
        return $this->getJson('/api/sessions');
    }

    /**
     * @return array<string, mixed>
     */
    public function getSession(?string $name = null): array
    {
        $session = $name ?? (string) config('waha.session', 'default');

        return $this->getJson('/api/sessions/'.rawurlencode($session));
    }

    /**
     * @return array<string, mixed>
     */
    public function sendText(string $chatId, string $text, ?string $session = null): array
    {
        $session = $session ?? (string) config('waha.session', 'default');

        return $this->postJson('/api/sendText', [
            'session' => $session,
            'chatId' => $chatId,
            'text' => $text,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getJson(string $path): array
    {
        try {
            $response = $this->http()->get($this->url($path));

            $response->throw();

            /** @var array<string, mixed> */
            return $response->json() ?? [];
        } catch (RequestException $e) {
            throw WahaException::fromResponse($e);
        } catch (Throwable $e) {
            throw new WahaException('WAHA request failed: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function postJson(string $path, array $body): array
    {
        try {
            $response = $this->http()->post($this->url($path), $body);

            $response->throw();

            /** @var array<string, mixed> */
            return $response->json() ?? [];
        } catch (RequestException $e) {
            throw WahaException::fromResponse($e);
        } catch (Throwable $e) {
            throw new WahaException('WAHA request failed: '.$e->getMessage(), 0, $e);
        }
    }

    private function url(string $path): string
    {
        return rtrim((string) config('waha.base_url'), '/').$path;
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Api-Key' => (string) config('waha.api_key'),
        ])
            ->timeout((int) config('waha.timeout', 30))
            ->withOptions([
                'verify' => (bool) config('waha.verify_ssl', true),
            ]);
    }
}
