<?php

namespace App\Filament\Resources\ArticlePoolResource\Concerns;

use Illuminate\Validation\ValidationException;

trait ValidatesArticlePoolSchedule
{
    /**
     * @param  array<string, mixed>  $data
     */
    protected function assertArticlePoolScheduleValid(array $data): void
    {
        $slots = $data['schedule_times'] ?? [];
        $hasSlots = is_array($slots)
            && collect($slots)->contains(fn ($t) => trim((string) $t) !== '');

        if ($hasSlots && ($data['schedule_frequency'] ?? 'daily') !== 'daily') {
            throw ValidationException::withMessages([
                'schedule_frequency' => 'Beberapa slot jam harian hanya didukung jika frekuensi = Harian. Kosongkan slot dan pakai satu "Waktu (satu slot)" jika Anda ingin Mingguan/Bulanan.',
            ]);
        }

        if (! is_array($slots)) {
            return;
        }

        foreach ($slots as $t) {
            $t = trim((string) $t);
            if ($t === '') {
                continue;
            }
            if (! preg_match('/^\d{2}:\d{2}$/', $t)) {
                throw ValidationException::withMessages([
                    'schedule_times' => "Slot jam tidak valid: \"{$t}\". Gunakan format HH:MM (contoh 04:45).",
                ]);
            }
        }
    }
}
