<?php

namespace App\Services\AiProviders;

use App\Contracts\ArticleAiProvider;
use App\DTOs\ArticleAiResponse;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenRouterProvider implements ArticleAiProvider
{
    public function __construct(
        protected string $apiKey,
        protected string $baseUrl,
        protected string $model,
        protected int $maxTokens,
        protected float $temperature,
        protected string $siteUrl,
        protected string $siteName,
    ) {}

    public static function fromConfig(): static
    {
        $cfg = config('article-generator.openrouter');

        return new static(
            apiKey: $cfg['api_key'] ?? '',
            baseUrl: $cfg['base_url'],
            model: $cfg['model'],
            maxTokens: $cfg['max_tokens'],
            temperature: $cfg['temperature'],
            siteUrl: $cfg['site_url'],
            siteName: $cfg['site_name'],
        );
    }

    public function generate(string $systemPrompt, string $userPrompt, array $options = []): ArticleAiResponse
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('OpenRouter API key is not configured.');
        }

        $model = $options['model'] ?? $this->model;
        $maxTokens = $options['max_tokens'] ?? $this->maxTokens;
        $temperature = $options['temperature'] ?? $this->temperature;

        $response = Http::timeout(180)
            ->withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'HTTP-Referer' => $this->siteUrl,
                'X-Title' => $this->siteName,
                'Content-Type' => 'application/json',
            ])
            ->post($this->baseUrl.'/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

        if ($response->failed()) {
            $error = $response->json('error.message', $response->body());
            throw new RuntimeException("OpenRouter API error: {$error}");
        }

        $data = $response->json();

        $content = $data['choices'][0]['message']['content'] ?? '';
        $finishReason = $data['choices'][0]['finish_reason'] ?? null;
        $usage = $data['usage'] ?? [];
        $tokensUsed = ($usage['prompt_tokens'] ?? 0) + ($usage['completion_tokens'] ?? 0);
        $actualModel = $data['model'] ?? $model;

        if (empty($content)) {
            throw new RuntimeException('OpenRouter returned empty content.');
        }

        return new ArticleAiResponse(
            content: $content,
            model: $actualModel,
            tokensUsed: $tokensUsed,
            finishReason: $finishReason,
        );
    }

    public function name(): string
    {
        return 'openrouter';
    }
}
