@props([
    'items' => [],
    'tone' => 'red', // red | ink | paper
])

@php
    $toneClass = match ($tone) {
        'ink' => 'bg-ink-900 text-paper-50 border-y-flag-500',
        'paper' => 'bg-paper-100 text-ink-900 border-y-ink-900',
        default => 'bg-flag-500 text-paper-50 border-y-ink-900',
    };

    // Double list supaya marquee loop terasa kontinu.
    $loop = array_merge($items, $items);
@endphp

<div {{ $attributes->class('w-full overflow-hidden border-y-4 ' . $toneClass) }} role="marquee" aria-label="Slogan Tani Merah">
    <div class="ticker-track whitespace-nowrap py-3">
        @foreach ($loop as $text)
            <span class="inline-flex items-center gap-6 font-display text-xl tracking-widest uppercase">
                <span class="inline-block h-2 w-2 bg-current"></span>
                {{ $text }}
            </span>
        @endforeach
    </div>
</div>
