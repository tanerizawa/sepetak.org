<?php

namespace Tests\Unit\Services;

use App\DTOs\ArticleAiResponse;
use App\Services\AiProviders\OpenRouterProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class OpenRouterProviderHardeningTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_retries_on_transient_error_and_succeeds(): void
    {
        config([
            'article-generator.openrouter.retry.attempts' => 2,
            'article-generator.openrouter.retry.base_sleep_ms' => 0,
            'article-generator.openrouter.retry.max_sleep_ms' => 0,
            'article-generator.openrouter.circuit_breaker.enabled' => false,
        ]);

        Http::fake([
            'openrouter.ai/api/v1/chat/completions' => Http::sequence()
                ->push(['error' => ['message' => 'server error']], 500)
                ->push([
                    'choices' => [['message' => ['content' => 'OK']]],
                    'model' => 'anthropic/claude-3.7-sonnet',
                    'usage' => ['prompt_tokens' => 1, 'completion_tokens' => 2],
                ], 200),
        ]);

        $provider = new OpenRouterProvider(
            apiKey: 'sk-test',
            baseUrl: 'https://openrouter.ai/api/v1',
            model: 'anthropic/claude-3.7-sonnet',
            maxTokens: 100,
            temperature: 0.5,
            siteUrl: 'https://example.test',
            siteName: 'Test',
            fallbackModel: null,
        );

        $out = $provider->generate('sys', 'user');

        $this->assertInstanceOf(ArticleAiResponse::class, $out);
        $this->assertSame('OK', $out->content);
        Http::assertSentCount(2);
    }

    public function test_circuit_breaker_opens_after_threshold_and_fails_fast(): void
    {
        config([
            'article-generator.openrouter.retry.attempts' => 1,
            'article-generator.openrouter.retry.base_sleep_ms' => 0,
            'article-generator.openrouter.retry.max_sleep_ms' => 0,
            'article-generator.openrouter.circuit_breaker.enabled' => true,
            'article-generator.openrouter.circuit_breaker.failure_threshold' => 2,
            'article-generator.openrouter.circuit_breaker.window_seconds' => 60,
            'article-generator.openrouter.circuit_breaker.open_seconds' => 300,
            'article-generator.openrouter.circuit_breaker.cache_key_prefix' => 'test-openrouter:circuit',
        ]);

        Http::fake([
            'openrouter.ai/api/v1/chat/completions' => Http::response(['error' => ['message' => 'server error']], 500),
        ]);

        $provider = new OpenRouterProvider(
            apiKey: 'sk-test',
            baseUrl: 'https://openrouter.ai/api/v1',
            model: 'anthropic/claude-3.7-sonnet',
            maxTokens: 100,
            temperature: 0.5,
            siteUrl: 'https://example.test',
            siteName: 'Test',
            fallbackModel: null,
        );

        for ($i = 0; $i < 2; $i++) {
            try {
                $provider->generate('sys', 'user');
                $this->fail('Expected exception not thrown.');
            } catch (RuntimeException $e) {
                $this->assertStringContainsString('OpenRouter', $e->getMessage());
            }
        }

        try {
            $provider->generate('sys', 'user');
            $this->fail('Expected circuit breaker exception not thrown.');
        } catch (RuntimeException $e) {
            $this->assertSame('OpenRouter circuit breaker is open.', $e->getMessage());
        }

        Http::assertSentCount(2);
    }
}

