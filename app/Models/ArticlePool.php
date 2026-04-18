<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticlePool extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'schedule_frequency',
        'schedule_day',
        'schedule_time',
        'schedule_times',
        'content_profile',
        'articles_per_run',
        'is_active',
        'auto_publish',
    ];

    protected function casts(): array
    {
        return [
            'schedule_times' => 'array',
            'articles_per_run' => 'integer',
            'is_active' => 'boolean',
            'auto_publish' => 'boolean',
        ];
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(ArticleTopic::class, 'article_pool_topic');
    }

    public function generationLogs(): HasMany
    {
        return $this->hasMany(ArticleGenerationLog::class, 'article_pool_id');
    }

    /**
     * Zona waktu penjadwalan (WIB) — harus konsisten dengan `articles:generate` / TopicPicker.
     */
    protected function scheduleTimezone(): string
    {
        return (string) config('article-generator.schedule_timezone', 'Asia/Jakarta');
    }

    /**
     * Slot harian HH:MM dari kolom JSON `schedule_times` (sudah dinormalisasi).
     *
     * @return list<string>
     */
    public function normalizedScheduleSlots(): array
    {
        $raw = $this->schedule_times;
        if (! is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $t) {
            $t = is_string($t) ? trim($t) : trim((string) $t);
            if ($t === '' || ! preg_match('/^\d{2}:\d{2}$/', $t)) {
                continue;
            }
            $out[] = $t;
        }

        return array_values(array_unique($out));
    }

    /**
     * Satu jam baku dari `schedule_time` (kolom time DB → string "HH:MM:SS" / Carbon).
     */
    public function normalizedSingleTime(): ?string
    {
        $t = $this->schedule_time;
        if ($t === null || $t === '') {
            return null;
        }
        if ($t instanceof DateTimeInterface) {
            return Carbon::instance($t)->timezone($this->scheduleTimezone())->format('H:i');
        }
        $s = trim((string) $t);
        if (strlen($s) >= 5) {
            return substr($s, 0, 5);
        }

        return null;
    }

    /**
     * Apakah waktu `$now` (sembarang timezone) jatuh pada slot jadwal pool ini.
     * Dipakai oleh `php artisan articles:generate` (mode terjadwal).
     */
    public function isDueAt(DateTimeInterface|CarbonInterface $now): bool
    {
        $tz = $this->scheduleTimezone();
        $nowTz = Carbon::instance($now)->timezone($tz);

        return match ($this->schedule_frequency) {
            'daily' => $this->isDueDailyAt($nowTz),
            'weekly' => $this->isDueWeeklyAt($nowTz),
            'biweekly' => $this->isDueBiweeklyAt($nowTz),
            'monthly' => $this->isDueMonthlyAt($nowTz),
            default => false,
        };
    }

    /**
     * Perkiraan eksekusi berikutnya setelah `$from` (untuk dokumentasi / UI).
     */
    public function getNextRunAt(DateTimeInterface|CarbonInterface $from): Carbon
    {
        $tz = $this->scheduleTimezone();
        $fromTz = Carbon::instance($from)->timezone($tz);

        return match ($this->schedule_frequency) {
            'daily' => $this->nextDailyRunAfter($fromTz),
            'weekly' => $this->nextWeeklyRunAfter($fromTz),
            'biweekly' => $this->nextBiweeklyRunAfter($fromTz),
            'monthly' => $this->nextMonthlyRunAfter($fromTz),
            default => $fromTz->copy()->addMinute(),
        };
    }

    /**
     * Harian: jendela antar slot (WIB). Tetap “jatuh tempo” sampai ada percobaan sukses
     * antrean/generasi di jendela itu — supaya cron yang meleset satu menit tidak kehilangan slot.
     *
     * @param  list<string>  $sortedSlots
     * @return array{0: Carbon, 1: Carbon}|null
     */
    private function currentDailyCatchupWindow(Carbon $nowTz, array $sortedSlots): ?array
    {
        $sortedSlots = array_values($sortedSlots);
        sort($sortedSlots);
        $n = count($sortedSlots);
        if ($n === 0) {
            return null;
        }
        $day = $nowTz->copy()->startOfDay();

        $tailStart = $this->combineLocalDateTime($day->copy()->subDay(), $sortedSlots[$n - 1]);
        $tailEnd = $this->combineLocalDateTime($day, $sortedSlots[0]);
        if ($nowTz->greaterThanOrEqualTo($tailStart) && $nowTz->lessThan($tailEnd)) {
            return [$tailStart, $tailEnd];
        }

        for ($i = 0; $i < $n - 1; $i++) {
            $start = $this->combineLocalDateTime($day, $sortedSlots[$i]);
            $end = $this->combineLocalDateTime($day, $sortedSlots[$i + 1]);
            if ($nowTz->greaterThanOrEqualTo($start) && $nowTz->lessThan($end)) {
                return [$start, $end];
            }
        }

        $startLast = $this->combineLocalDateTime($day, $sortedSlots[$n - 1]);
        $endLast = $this->combineLocalDateTime($day->copy()->addDay(), $sortedSlots[0]);
        if ($nowTz->greaterThanOrEqualTo($startLast) && $nowTz->lessThan($endLast)) {
            return [$startLast, $endLast];
        }

        return null;
    }

    private function hasPoolGenerationAttemptInWindow(Carbon $start, Carbon $end): bool
    {
        if ($this->getKey() === null) {
            return false;
        }

        return ArticleGenerationLog::query()
            ->where('article_pool_id', $this->getKey())
            ->whereIn('status', ['queued', 'generating', 'completed'])
            ->where('created_at', '>=', $start->copy()->utc())
            ->where('created_at', '<', $end->copy()->utc())
            ->exists();
    }

    private function isDueDailyAt(Carbon $nowTz): bool
    {
        $slots = $this->normalizedScheduleSlots();
        if ($slots === []) {
            $single = $this->normalizedSingleTime();
            if ($single === null) {
                return false;
            }
            $slots = [$single];
        }

        $window = $this->currentDailyCatchupWindow($nowTz, $slots);
        if ($window === null) {
            return false;
        }

        return ! $this->hasPoolGenerationAttemptInWindow($window[0], $window[1]);
    }

    /**
     * 0 = Minggu … 6 = Sabtu (selaras {@see Carbon::dayOfWeek}).
     */
    private function normalizedWeeklyDayOfWeek(): ?int
    {
        $raw = $this->schedule_day;
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_numeric($raw)) {
            $i = (int) $raw;

            return $i >= 0 && $i <= 6 ? $i : null;
        }
        $key = strtolower(trim((string) $raw));
        static $map = [
            'sunday' => 0, 'sun' => 0,
            'monday' => 1, 'mon' => 1,
            'tuesday' => 2, 'tue' => 2, 'tues' => 2,
            'wednesday' => 3, 'wed' => 3,
            'thursday' => 4, 'thu' => 4, 'thur' => 4, 'thurs' => 4,
            'friday' => 5, 'fri' => 5,
            'saturday' => 6, 'sat' => 6,
        ];

        return $map[$key] ?? null;
    }

    private function normalizedMonthlyDayOfMonth(): ?int
    {
        $raw = $this->schedule_day;
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_numeric($raw)) {
            $d = (int) $raw;

            return $d >= 1 && $d <= 31 ? $d : null;
        }

        return null;
    }

    private function isBiweeklyWeekAlignedWithAnchor(Carbon $dayInTz): bool
    {
        $tz = $this->scheduleTimezone();
        $anchor = Carbon::parse((string) ($this->created_at ?? $dayInTz))->timezone($tz)->startOfWeek();
        $weekStart = $dayInTz->copy()->startOfWeek();

        return abs((int) $anchor->diffInWeeks($weekStart, false)) % 2 === 0;
    }

    private function isDueWeeklyAt(Carbon $nowTz): bool
    {
        $single = $this->normalizedSingleTime();
        if ($single === null) {
            return false;
        }
        $dow = $this->normalizedWeeklyDayOfWeek();
        if ($dow === null || (int) $nowTz->dayOfWeek !== $dow) {
            return false;
        }

        return $nowTz->format('H:i') === $single;
    }

    private function isDueBiweeklyAt(Carbon $nowTz): bool
    {
        $single = $this->normalizedSingleTime();
        if ($single === null) {
            return false;
        }
        $dow = $this->normalizedWeeklyDayOfWeek();
        if ($dow === null || (int) $nowTz->dayOfWeek !== $dow) {
            return false;
        }
        if ($nowTz->format('H:i') !== $single) {
            return false;
        }

        return $this->isBiweeklyWeekAlignedWithAnchor($nowTz);
    }

    private function isDueMonthlyAt(Carbon $nowTz): bool
    {
        $single = $this->normalizedSingleTime();
        if ($single === null) {
            return false;
        }
        $dom = $this->normalizedMonthlyDayOfMonth();
        if ($dom === null) {
            return false;
        }
        if ((int) $nowTz->day !== $dom) {
            return false;
        }

        return $nowTz->format('H:i') === $single;
    }

    private function combineLocalDateTime(Carbon $dateLocal, string $hiMm): Carbon
    {
        $tz = $this->scheduleTimezone();

        return Carbon::parse($dateLocal->format('Y-m-d').' '.$hiMm.':00', $tz);
    }

    private function nextDailyRunAfter(Carbon $fromTz): Carbon
    {
        $slots = $this->normalizedScheduleSlots();
        if ($slots !== []) {
            sort($slots);
            $day = $fromTz->copy()->startOfDay();
            foreach ($slots as $slot) {
                $candidate = $this->combineLocalDateTime($day, $slot);
                if ($candidate->greaterThan($fromTz)) {
                    return $candidate;
                }
            }

            $first = $slots[0];

            return $this->combineLocalDateTime($day->copy()->addDay(), $first);
        }

        $single = $this->normalizedSingleTime();
        if ($single === null) {
            return $fromTz->copy()->addDay()->startOfDay();
        }
        $candidate = $this->combineLocalDateTime($fromTz->copy()->startOfDay(), $single);
        if ($candidate->lessThanOrEqualTo($fromTz)) {
            $candidate = $this->combineLocalDateTime($fromTz->copy()->startOfDay()->addDay(), $single);
        }

        return $candidate;
    }

    private function nextWeeklyRunAfter(Carbon $fromTz): Carbon
    {
        $single = $this->normalizedSingleTime() ?? '00:00';
        $targetDow = $this->normalizedWeeklyDayOfWeek();
        if ($targetDow === null) {
            return $fromTz->copy()->addWeek();
        }
        $cursor = $fromTz->copy()->startOfDay();
        for ($i = 0; $i < 370; $i++) {
            if ((int) $cursor->dayOfWeek === $targetDow) {
                $candidate = $this->combineLocalDateTime($cursor, $single);
                if ($candidate->greaterThan($fromTz)) {
                    return $candidate;
                }
            }
            $cursor->addDay();
        }

        return $fromTz->copy()->addWeek();
    }

    private function nextBiweeklyRunAfter(Carbon $fromTz): Carbon
    {
        $single = $this->normalizedSingleTime() ?? '00:00';
        $targetDow = $this->normalizedWeeklyDayOfWeek();
        if ($targetDow === null) {
            return $fromTz->copy()->addWeeks(2);
        }
        $cursor = $fromTz->copy()->startOfDay();
        for ($i = 0; $i < 800; $i++) {
            if ((int) $cursor->dayOfWeek === $targetDow && $this->isBiweeklyWeekAlignedWithAnchor($cursor)) {
                $candidate = $this->combineLocalDateTime($cursor, $single);
                if ($candidate->greaterThan($fromTz)) {
                    return $candidate;
                }
            }
            $cursor->addDay();
        }

        return $fromTz->copy()->addWeeks(2);
    }

    private function nextMonthlyRunAfter(Carbon $fromTz): Carbon
    {
        $single = $this->normalizedSingleTime() ?? '00:00';
        $targetDom = $this->normalizedMonthlyDayOfMonth();
        if ($targetDom === null) {
            return $fromTz->copy()->addMonth();
        }
        $targetDom = max(1, min(31, $targetDom));
        $month = $fromTz->copy()->startOfMonth();
        for ($i = 0; $i < 48; $i++) {
            $dom = min($targetDom, (int) $month->daysInMonth);
            $candidate = $this->combineLocalDateTime($month->copy()->day($dom), $single);
            if ($candidate->greaterThan($fromTz)) {
                return $candidate;
            }
            $month->addMonthNoOverflow()->startOfMonth();
        }

        return $fromTz->copy()->addMonth();
    }
}
