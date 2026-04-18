<?php

namespace App\Services\ArticleGeneration;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;

/**
 * Profil konten generator: jalur akademik (pillar) terpisah dari materi harian ringkas.
 */
enum ContentProfile: string
{
    case Pillar = 'pillar';
    case MemberPractical = 'member_practical';

    /**
     * Profil untuk satu run generator.
     *
     * Urutan keputusan:
     * 1) Pool eksplisit bertipe `member_practical` → materi praktis.
     * 2) Topik jelas-jelas materi harian (`member_guide`, kategori panduan, …) → praktis
     *    walaupun pool yang di-dispatch pillar (sering: topik tips terpasang ke pool pillar
     *    atau Filament memilih pool aktif pertama yang salah).
     * 3) Pool pillar + topik akademik → pillar.
     * 4) Tanpa pool: pivot topik memuat pool praktis, lalu sinyal topik, lalu pillar.
     */
    public static function forArticleGeneration(?ArticlePool $pool, ArticleTopic $topic): self
    {
        if ($pool !== null) {
            return self::fromPool($pool);
        }

        if ($topic->getKey() === null) {
            return self::Pillar;
        }

        if ($topic->pools()
            ->where('article_pools.content_profile', self::MemberPractical->value)
            ->exists()) {
            return self::MemberPractical;
        }

        if (self::topicSignalsMemberPractical($topic)) {
            return self::MemberPractical;
        }

        return self::Pillar;
    }

    /**
     * Topik "materi harian / panduan anggota" tanpa melihat pool yang sedang di-dispatch.
     */
    private static function topicSignalsMemberPractical(ArticleTopic $topic): bool
    {
        $practicalTypes = config('article-generator.member_practical_article_types', ['member_guide']);
        if (in_array((string) $topic->article_type, $practicalTypes, true)) {
            return true;
        }

        if ($topic->getKey() === null) {
            return false;
        }

        $practicalCategorySlugs = config('article-generator.member_practical_category_slugs', ['panduan-tips-anggota']);
        if ($topic->category_id !== null && $practicalCategorySlugs !== []) {
            $categorySlug = Category::query()->whereKey($topic->category_id)->value('slug');
            if ($categorySlug !== null && in_array($categorySlug, $practicalCategorySlugs, true)) {
                return true;
            }
        }

        return false;
    }

    public static function fromPool(?ArticlePool $pool): self
    {
        if ($pool !== null && ($pool->content_profile ?? '') === self::MemberPractical->value) {
            return self::MemberPractical;
        }

        return self::Pillar;
    }

    public static function fromNullableString(?string $value): self
    {
        return $value === self::MemberPractical->value
            ? self::MemberPractical
            : self::Pillar;
    }

    public function isMemberPractical(): bool
    {
        return $this === self::MemberPractical;
    }
}
