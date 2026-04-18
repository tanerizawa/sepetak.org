<?php

namespace Tests\Unit\Waha;

use App\Services\Waha\WahaClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WahaClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('waha.enabled', true);
        Config::set('waha.base_url', 'http://waha.test');
        Config::set('waha.api_key', 'secret-key');
        Config::set('waha.session', 'default');
        Config::set('waha.timeout', 5);
        Config::set('waha.verify_ssl', false);
    }

    public function test_get_session_parses_json(): void
    {
        Http::fake([
            'http://waha.test/api/sessions/default' => Http::response(['name' => 'default', 'status' => 'WORKING'], 200),
        ]);

        $client = new WahaClient;
        $data = $client->getSession('default');

        $this->assertSame('WORKING', $data['status'] ?? null);
    }

    public function test_send_text_posts_expected_body(): void
    {
        Http::fake([
            'http://waha.test/api/sendText' => Http::response(['ok' => true], 200),
        ]);

        $client = new WahaClient;
        $client->sendText('628123@c.us', 'Halo');

        Http::assertSent(function ($request) {
            return $request->url() === 'http://waha.test/api/sendText'
                && $request->header('X-Api-Key')[0] === 'secret-key'
                && $request['session'] === 'default'
                && $request['chatId'] === '628123@c.us'
                && $request['text'] === 'Halo';
        });
    }
}
