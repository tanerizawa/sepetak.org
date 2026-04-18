<?php

namespace Tests\Unit\Services;

use App\DTOs\ArticleAiResponse;
use App\Services\AiProviders\OpenRouterProvider;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenRouterProviderFallbackTest extends TestCase
{
    public function test_retries_once_with_fallback_when_no_endpoints_found(): void
    {
        Http::fake([
            'openrouter.ai/api/v1/chat/completions' => Http::sequence()
                ->push(['error' => ['message' => 'No endpoints found for anthropic/claude-3.5-sonnet.']], 404)
                ->push([
                    'choices' => [['message' => ['content' => 'OK dari fallback']]],
                    'model' => 'google/gemini-2.0-flash-001',
                    'usage' => ['prompt_tokens' => 1, 'completion_tokens' => 2],
                ], 200),
        ]);

        $provider = new OpenRouterProvider(
            apiKey: 'sk-test',
            baseUrl: 'https://openrouter.ai/api/v1',
            model: 'anthropic/claude-3.5-sonnet',
            maxTokens: 100,
            temperature: 0.5,
            siteUrl: 'https://example.test',
            siteName: 'Test',
            fallbackModel: 'google/gemini-2.0-flash-001',
        );

        $out = $provider->generate('sys', 'user');

        $this->assertInstanceOf(ArticleAiResponse::class, $out);
        $this->assertSame('OK dari fallback', $out->content);
        Http::assertSentCount(2);
    }
}
