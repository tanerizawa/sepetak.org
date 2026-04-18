<?php

namespace App\Services\AiProviders;

use App\Contracts\ArticleAiProvider;
use App\DTOs\ArticleAiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
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

        $this->throwIfCircuitOpen();

        $model = (string) ($options['model'] ?? $this->model);
        $maxTokens = (int) ($options['max_tokens'] ?? $this->maxTokens);
        $temperature = (float) ($options['temperature'] ?? $this->temperature);

        $attempts = max(1, (int) config('article-generator.openrouter.retry.attempts', 2));
        $baseSleepMs = max(0, (int) config('article-generator.openrouter.retry.base_sleep_ms', 350));
        $maxSleepMs = max(0, (int) config('article-generator.openrouter.retry.max_sleep_ms', 4000));

        $response = null;
        $lastException = null;
        $fallback = $this->effectiveFallbackModel($model);
        $primaryModel = $model;

        for ($i = 1; $i <= $attempts; $i++) {
            try {
                $response = $this->chatCompletion($model, $systemPrompt, $userPrompt, $maxTokens, $temperature);
                $lastException = null;
            } catch (\Throwable $e) {
                $lastException = $e;
                $response = null;

                if (! $this->isRetryableException($e) || $i >= $attempts) {
                    break;
                }

                $this->sleepWithBackoff($i, $baseSleepMs, $maxSleepMs);
                continue;
            }

            if ($response->successful()) {
                break;
            }

            $error = (string) $response->json('error.message', $response->body());

            if ($fallback !== null && $model === $primaryModel && $this->isNoEndpointsFoundError($error)) {
                $model = $fallback;
                $fallback = null;

                continue;
            }

            if ($this->isRetryableResponse($response) && $i < $attempts) {
                $this->sleepWithBackoff($i, $baseSleepMs, $maxSleepMs);
                continue;
            }

            break;
        }

        if ($response === null) {
            $this->recordFailureAndMaybeOpenCircuit($lastException?->getMessage() ?? 'Unknown OpenRouter failure.');
            throw new RuntimeException('OpenRouter request failed.', previous: $lastException);
        }

        if ($response->failed()) {
            $error = (string) $response->json('error.message', $response->body());
            $this->recordFailureAndMaybeOpenCircuit($error);
            throw new RuntimeException("OpenRouter API error: {$error}");
        }

        $this->resetCircuitBreaker();

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

    private function isRetryableResponse(Response $response): bool
    {
        $status = $response->status();

        if ($status === 408 || $status === 429) {
            return true;
        }

        return $status >= 500 && $status <= 599;
    }

    private function isRetryableException(\Throwable $e): bool
    {
        return $e instanceof ConnectionException;
    }

    private function sleepWithBackoff(int $attempt, int $baseSleepMs, int $maxSleepMs): void
    {
        if ($baseSleepMs <= 0) {
            return;
        }

        $multiplier = max(1, $attempt);
        $sleepMs = min($maxSleepMs, $baseSleepMs * (2 ** ($multiplier - 1)));
        $jitter = random_int(0, max(0, (int) round($sleepMs * 0.15)));
        $sleepMs = max(0, $sleepMs + $jitter);

        if ($sleepMs <= 0) {
            return;
        }

        usleep($sleepMs * 1000);
    }

    private function circuitBreakerEnabled(): bool
    {
        return (bool) config('article-generator.openrouter.circuit_breaker.enabled', true);
    }

    private function circuitBreakerKeyPrefix(): string
    {
        return (string) config('article-generator.openrouter.circuit_breaker.cache_key_prefix', 'openrouter:circuit');
    }

    private function throwIfCircuitOpen(): void
    {
        if (! $this->circuitBreakerEnabled()) {
            return;
        }

        $openUntil = Cache::get($this->circuitBreakerKeyPrefix().':open_until');
        if (! is_int($openUntil)) {
            return;
        }

        if (time() < $openUntil) {
            throw new RuntimeException('OpenRouter circuit breaker is open.');
        }
    }

    private function recordFailureAndMaybeOpenCircuit(string $errorMessage): void
    {
        if (! $this->circuitBreakerEnabled()) {
            return;
        }

        $threshold = max(1, (int) config('article-generator.openrouter.circuit_breaker.failure_threshold', 3));
        $windowSeconds = max(10, (int) config('article-generator.openrouter.circuit_breaker.window_seconds', 60));
        $openSeconds = max(30, (int) config('article-generator.openrouter.circuit_breaker.open_seconds', 300));
        $prefix = $this->circuitBreakerKeyPrefix();

        $failuresKey = $prefix.':failures';
        $count = (int) Cache::get($failuresKey, 0);
        $count++;
        Cache::put($failuresKey, $count, $windowSeconds);

        if ($count < $threshold) {
            return;
        }

        Cache::put($prefix.':open_until', CarbonImmutable::now()->addSeconds($openSeconds)->getTimestamp(), $openSeconds);
    }

    private function resetCircuitBreaker(): void
    {
        if (! $this->circuitBreakerEnabled()) {
            return;
        }

        $prefix = $this->circuitBreakerKeyPrefix();
        Cache::forget($prefix.':failures');
        Cache::forget($prefix.':open_until');
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
