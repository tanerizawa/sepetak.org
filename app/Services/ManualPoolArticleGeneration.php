<?php

namespace App\Services;

use App\Models\ArticleGenerationLog;
use App\Models\ArticlePool;
use Illuminate\Support\Facades\Artisan;

/**
 * Memicu `articles:generate` untuk satu pool (sinkron), dipakai panel admin / CLI internal.
 */
final class ManualPoolArticleGeneration
{
    /**
     * @return array{status: string, title: string, body: string}
     */
    public function run(ArticlePool $pool, bool $ignoreDailyCap): array
    {
        if (! config('article-generator.enabled', false)) {
            return [
                'status' => 'warning',
                'title' => 'Generator nonaktif',
                'body' => 'Set `ARTICLE_GENERATOR_ENABLED=true` di `.env`, lalu jalankan `php artisan config:clear`.',
            ];
        }

        Artisan::call('articles:generate', [
            '--pool' => (string) $pool->getKey(),
            '--sync' => true,
            '--force' => $ignoreDailyCap,
        ]);

        $output = trim(Artisan::output());
        $body = $output !== '' ? $output : '(Tidak ada keluaran konsol.)';
        $body = $this->appendLatestPoolLogDetail($body, $pool);

        if (str_contains($output, 'Daily limit reached')) {
            return [
                'status' => 'warning',
                'title' => 'Batas harian tercapai',
                'body' => $body."\n\nAktifkan “Abaikan batas artikel per hari” atau naikkan `ARTICLE_MAX_PER_DAY` di `.env`.",
            ];
        }

        if (str_contains($output, 'No available topics')) {
            return [
                'status' => 'warning',
                'title' => 'Tidak ada topik tersedia',
                'body' => $body,
            ];
        }

        if (str_contains($output, 'Created post #')) {
            return [
                'status' => 'success',
                'title' => 'Artikel berhasil dibuat',
                'body' => $body,
            ];
        }

        if (str_contains($output, 'Generation failed')) {
            return [
                'status' => 'danger',
                'title' => 'Generasi gagal',
                'body' => $body,
            ];
        }

        return [
            'status' => 'info',
            'title' => 'Selesai',
            'body' => $body,
        ];
    }

    private function appendLatestPoolLogDetail(string $body, ArticlePool $pool): string
    {
        if (str_contains($body, 'Created post #')) {
            return $body;
        }

        $detail = ArticleGenerationLog::query()
            ->where('article_pool_id', $pool->getKey())
            ->whereIn('status', ['rejected', 'failed'])
            ->orderByDesc('id')
            ->value('error_message');

        if (! is_string($detail) || $detail === '' || str_contains($body, $detail)) {
            return $body;
        }

        return $body."\n\n**Detail log:**\n".$detail;
    }
}
