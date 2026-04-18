@props([
    'href' => null,
    'image' => null,
    'imageAlt' => '',
    'category' => null,
    'readingTime' => null,
    'date' => null,
    'title' => null,
    'excerpt' => null,
])

@php
    $wrapperTag = $href ? 'a' : 'article';
    $wrapperAttrs = $href ? 'href="' . e($href) . '"' : '';
@endphp

<{{ $wrapperTag }}
    {!! $wrapperAttrs !!}
    {{ $attributes->class('group block overflow-hidden border-4 border-ink-900 bg-paper-50 shadow-[8px_8px_0_hsl(var(--flag-500))] hover:shadow-[10px_10px_0_hsl(var(--flag-500))] transition duration-200 ease-out') }}
>
    <div class="relative aspect-video overflow-hidden bg-paper-100">
        @if ($image)
            <img
                src="{{ $image }}"
                alt="{{ $imageAlt }}"
                class="h-full w-full object-cover grayscale group-hover:grayscale-0 transition duration-200 ease-out"
                loading="lazy"
                decoding="async"
            />
        @endif
        @if ($category)
            <div class="absolute left-3 top-3 inline-flex items-center bg-paper-50 px-2 py-1 text-xs font-mono uppercase tracking-widest text-ink-900 border-2 border-ink-900">
                {{ $category }}
            </div>
        @endif
    </div>

    <div class="p-5">
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-ink-700 font-mono uppercase tracking-widest">
            @if ($date)
                <span>{{ $date }}</span>
            @endif
            @if ($readingTime)
                <span class="text-ink-700/60">•</span>
                <span>{{ $readingTime }}</span>
            @endif
        </div>

        @if ($title)
            <h3 class="mt-3 font-display text-2xl uppercase tracking-wide leading-tight text-ink-900 line-clamp-2">
                {{ $title }}
            </h3>
        @endif

        @if ($excerpt)
            <p class="mt-2 text-sm text-ink-700 leading-relaxed line-clamp-3">
                {{ $excerpt }}
            </p>
        @endif
    </div>
</{{ $wrapperTag }}>
