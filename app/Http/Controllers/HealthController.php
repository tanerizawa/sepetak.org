<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Endpoint sederhana `/health` untuk uptime monitor eksternal
 * (Uptime Robot / StatusCake / BetterStack).
 *
 * - 200 JSON bila database & cache responsif.
 * - 503 JSON bila salah satu gagal. Status individual tetap dikembalikan
 *   supaya dashboard monitoring bisa menampilkan komponen mana yang down.
 *
 * Catatan: endpoint sengaja tidak menyertakan detail sensitif (versi
 * paket, nama host, dsb.) agar aman dibuka di jaringan publik.
 */
class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
        ];

        $ok = collect($checks)->every(fn (array $c) => $c['status'] === 'ok');

        return response()->json([
            'status' => $ok ? 'ok' : 'degraded',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $ok ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->select('select 1');

            return ['status' => 'ok'];
        } catch (\Throwable $e) {
            return ['status' => 'fail', 'error' => class_basename($e)];
        }
    }

    private function checkCache(): array
    {
        try {
            $probe = 'health:probe:'.bin2hex(random_bytes(4));
            Cache::put($probe, 1, now()->addSeconds(5));
            $got = Cache::pull($probe);
            if ($got !== 1) {
                return ['status' => 'fail', 'error' => 'roundtrip_mismatch'];
            }

            return ['status' => 'ok'];
        } catch (\Throwable $e) {
            return ['status' => 'fail', 'error' => class_basename($e)];
        }
    }
}
