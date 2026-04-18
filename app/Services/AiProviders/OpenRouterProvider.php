<?php

namespace App\Services\AiProviders;

use App\Contracts\ArticleAiProvider;
use App\DTOs\ArticleAiResponse;
use Illuminate\Http\Client\Response;
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
        protected ?string $fallbackModel = null,
    ) {}

    public static function fromConfig(): static
    {
        $cfg = config('article-generator.openrouter');
        $fallback = trim((string) ($cfg['fallback_model'] ?? ''));

        return new static(
            apiKey: $cfg['api_key'] ?? '',
            baseUrl: (string) ($cfg['base_url'] ?? 'https://openrouter.ai/api/v1'),
            model: (string) ($cfg['model'] ?? 'anthropic/claude-3.7-sonnet'),
            maxTokens: (int) ($cfg['max_tokens'] ?? 8000),
            temperature: (float) ($cfg['temperature'] ?? 0.7),
            siteUrl: (string) ($cfg['site_url'] ?? $cfg['referer'] ?? ''),
            siteName: (string) ($cfg['site_name'] ?? $cfg['app_title'] ?? ''),
            fallbackModel: $fallback !== '' ? $fallback : null,
        );
    }

    public function generate(string $systemPrompt, string $userPrompt, array $options = []): ArticleAiResponse
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('OpenRouter API key is not configured.');
        }

        $model = (string) ($options['model'] ?? $this->model);
        $maxTokens = (int) ($options['max_tokens'] ?? $this->maxTokens);
        $temperature = (float) ($options['temperature'] ?? $this->temperature);

        $response = $this->chatCompletion($model, $systemPrompt, $userPrompt, $maxTokens, $temperature);

        if ($response->failed()) {
            $error = $response->json('error.message', $response->body());
            $fallback = $this->effectiveFallbackModel($model);
            if ($fallback !== null && $this->isNoEndpointsFoundError($error)) {
                $response = $this->chatCompletion($fallback, $systemPrompt, $userPrompt, $maxTokens, $temperature);
                $model = $fallback;
            }
        }

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

    private function chatCompletion(
        string $model,
        string $systemPrompt,
        string $userPrompt,
        int $maxTokens,
        float $temperature,
    ): Response {
        return Http::timeout(180)
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
    }

    private function effectiveFallbackModel(string $primaryModel): ?string
    {
        if ($this->fallbackModel === null || $this->fallbackModel === '') {
            return null;
        }

        return $this->fallbackModel !== $primaryModel ? $this->fallbackModel : null;
    }

    private function isNoEndpointsFoundError(string $message): bool
    {
        return str_contains(strtolower($message), 'no endpoints found');
    }
}
