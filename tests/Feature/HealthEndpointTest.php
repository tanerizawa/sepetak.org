<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Endpoint `/health` dipanggil oleh uptime monitor eksternal. Tidak boleh
 * memerlukan autentikasi dan harus tetap cepat (single SELECT 1 + cache
 * roundtrip).
 */
class HealthEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_returns_ok_when_services_up(): void
    {
        $response = $this->getJson('/health');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('checks.database.status', 'ok')
            ->assertJsonPath('checks.cache.status', 'ok')
            ->assertJsonStructure([
                'status',
                'timestamp',
                'checks' => [
                    'database' => ['status'],
                    'cache' => ['status'],
                ],
            ]);
    }

    public function test_health_is_publicly_accessible(): void
    {
        $response = $this->get('/health');

        $response->assertOk();
        $this->assertSame('application/json', $response->headers->get('content-type'));
    }
}
