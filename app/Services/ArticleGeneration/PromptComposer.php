<?php

namespace App\Services\ArticleGeneration;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\Contracts\PromptStrategyInterface;

/**
 * Memilih strategi prompt berdasarkan profil konten (pool + topik), selaras dengan
 * {@see ContentProfile::forArticleGeneration} dan validator.
 */
final class PromptComposer
{
    public function __construct(
        private readonly AcademicPromptStrategy $academic,
        private readonly MemberPracticalPromptStrategy $memberPractical,
    ) {}

    public function resolve(?ArticlePool $pool, ?ArticleTopic $topic = null): PromptStrategyInterface
    {
        if ($topic !== null) {
            return ContentProfile::forArticleGeneration($pool, $topic) === ContentProfile::MemberPractical
                ? $this->memberPractical
                : $this->academic;
        }

        return ContentProfile::fromPool($pool) === ContentProfile::MemberPractical
            ? $this->memberPractical
            : $this->academic;
    }

    public function systemPrompt(?ArticlePool $pool, ?ArticleTopic $topic = null): string
    {
        return $this->resolve($pool, $topic)->systemPrompt();
    }

    /**
     * @param  list<string>  $recentAutoPostTitles
     */
    public function buildUserPrompt(?ArticlePool $pool, ArticleTopic $topic, array $recentAutoPostTitles = []): string
    {
        return $this->buildUserPromptWithMeta($pool, $topic, $recentAutoPostTitles)['prompt'];
    }

    /**
     * @param  list<string>  $recentAutoPostTitles
     * @return array{prompt:string,variant:?string}
     */
    public function buildUserPromptWithMeta(?ArticlePool $pool, ArticleTopic $topic, array $recentAutoPostTitles = []): array
    {
        $strategy = $this->resolve($pool, $topic);

        $profile = ContentProfile::forArticleGeneration($pool, $topic);

        if ($strategy instanceof MemberPracticalPromptStrategy) {
            $prompt = $strategy->buildUserPrompt($topic, $recentAutoPostTitles);
        } else {
            $prompt = $strategy->buildUserPrompt($topic, []);
        }

        [$variantKey, $variantText] = $this->resolveVariant($profile, $topic);
        if ($variantText !== null && $variantText !== '') {
            $prompt .= "\n\n**Variasi Prompt:**\n{$variantText}";
        }

        return [
            'prompt' => $prompt,
            'variant' => $variantKey,
        ];
    }

    /**
     * @return array{0:?string,1:?string}
     */
    private function resolveVariant(ContentProfile $profile, ArticleTopic $topic): array
    {
        $cfg = (array) config('article-generator.prompt_variants.'.$profile->value, []);
        if (! (bool) ($cfg['enabled'] ?? false)) {
            return [null, null];
        }

        $variants = $cfg['variants'] ?? [];
        if (! is_array($variants) || $variants === []) {
            return [null, null];
        }

        $keys = array_values(array_filter(array_map(
            fn ($k) => is_string($k) ? trim($k) : '',
            array_keys($variants),
        )));
        if ($keys === []) {
            return [null, null];
        }

        $selection = (string) ($cfg['selection'] ?? 'topic_id_mod');
        $idx = 0;

        if ($selection === 'random') {
            $idx = random_int(0, count($keys) - 1);
        } else {
            $seed = (int) $topic->getKey();
            $idx = abs($seed) % count($keys);
        }

        $key = $keys[$idx] ?? null;
        if ($key === null) {
            return [null, null];
        }

        $text = $variants[$key] ?? null;
        if (! is_string($text) || trim($text) === '') {
            return [$key, null];
        }

        return [$key, PromptSanitizer::sanitize($text)];
    }
}
