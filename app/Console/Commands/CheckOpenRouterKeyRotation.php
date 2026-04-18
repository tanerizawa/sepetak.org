<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOpenRouterKeyRotation extends Command
{
    protected $signature = 'ai:check-openrouter-key-rotation {--days=90}';

    protected $description = 'Cek apakah OpenRouter API key sudah melewati batas rotasi yang direkomendasikan.';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $raw = trim((string) config('article-generator.openrouter.key_rotated_at', ''));

        if ($raw === '') {
            Log::warning('OpenRouter API key rotation date is not configured.');
            $this->warn('OPENROUTER_API_KEY_ROTATED_AT belum di-set.');

            return 2;
        }

        try {
            $rotatedAt = CarbonImmutable::parse($raw);
        } catch (\Throwable) {
            Log::warning('OpenRouter API key rotation date is invalid.', ['value' => $raw]);
            $this->error('OPENROUTER_API_KEY_ROTATED_AT tidak valid.');

            return 2;
        }

        $age = $rotatedAt->diffInDays(CarbonImmutable::now());

        if ($age > $days) {
            Log::warning('OpenRouter API key rotation is overdue.', ['age_days' => $age, 'limit_days' => $days]);
            $this->error("Rotasi OpenRouter API key overdue ({$age} hari, batas {$days} hari).");

            return 3;
        }

        $this->info("Rotasi OpenRouter API key OK ({$age} hari, batas {$days} hari).");

        return 0;
    }
}

