<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Slug artikel publik: panjang terbatas, unik, konsisten dengan judul yang disimpan.
 */
final class PostSlug
{
    /** Selaras dengan migrasi kolom `posts.slug`. */
    public const MAX_SLUG_LENGTH = 255;

    /** Ruang untuk sufiks numerik `-2`, `-3`, … */
    private const SUFFIX_RESERVE = 20;

    /** Panjang maksimum segmen dasar sebelum sufiks unik ditambahkan. */
    private const BASE_MAX = self::MAX_SLUG_LENGTH - self::SUFFIX_RESERVE;

    /**
     * Judul dari baris H1 Markdown: rapatkan spasi, buang penekanan `*` / backtick.
     */
    public static function normalizeHeadingText(string $raw): string
    {
        $t = trim(preg_replace('/\s+/u', ' ', $raw) ?? $raw);
        $t = preg_replace('/\*+|`+/u', '', $t) ?? $t;

        return trim($t);
    }

    /**
     * Saran slug (pratinjau form admin) — tanpa cek DB; dipotong agar aman untuk kolom.
     */
    public static function suggestFromTitle(string $title): string
    {
        $normalized = self::normalizeHeadingText($title);
        $slug = Str::slug($normalized);

        if ($slug === '') {
            $slug = 'artikel-'.Str::lower(Str::random(8));
        }

        return self::truncateSlugBase($slug, self::BASE_MAX);
    }

    /**
     * Slug unik untuk penyimpanan (generator AI, duplikasi, dll.).
     * Menggunakan DB lock untuk mencegah race condition saat concurrent requests.
     */
    public static function uniqueFromTitle(string $title, ?int $ignorePostId = null): string
    {
        $base = self::suggestFromTitle($title);
        $candidate = $base;
        $counter = 1;

        return DB::transaction(function () use (&$candidate, &$counter, $base, $ignorePostId) {
            while (self::slugTakenWithLock($candidate, $ignorePostId)) {
                $suffix = '-'.$counter;
                $trimmedBase = self::truncateSlugBase($base, self::MAX_SLUG_LENGTH - strlen($suffix));
                $candidate = $trimmedBase.$suffix;
                $counter++;
                if ($counter > 50_000) {
                    $candidate = self::truncateSlugBase($base, self::MAX_SLUG_LENGTH - 8).'-'.Str::lower(Str::random(6));
                    if (! self::slugTakenWithLock($candidate, $ignorePostId)) {
                        break;
                    }
                }
            }

            return $candidate;
        });
    }

    /**
     * Potong di batas panjang; utamakan potong di tanda hubung agar tidak memotong kata tengah.
     */
    public static function truncateSlugBase(string $slug, int $maxLength): string
    {
        $slug = rtrim($slug, '-');
        if (strlen($slug) <= $maxLength) {
            return $slug;
        }

        $chopped = substr($slug, 0, $maxLength);
        $lastHyphen = strrpos($chopped, '-');
        if ($lastHyphen !== false && $lastHyphen >= 24) {
            $chopped = substr($chopped, 0, $lastHyphen);
        }

        return rtrim($chopped, '-');
    }

    private static function slugTakenWithLock(string $slug, ?int $ignorePostId): bool
    {
        return Post::withTrashed()
            ->when($ignorePostId !== null, fn ($q) => $q->where('id', '!=', $ignorePostId))
            ->where('slug', $slug)
            ->exists();
    }
}
