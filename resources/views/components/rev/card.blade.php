@props([
    'href' => null,
    'image' => null,
    'imageAlt' => '',
    'meta' => null,
    'title' => null,
    'excerpt' => null,
])

@php
    $wrapperTag = $href ? 'a' : 'article';
    $wrapperAttrs = $href ? 'href="' . e($href) . '"' : '';
@endphp

<{{ $wrapperTag }}
    {!! $wrapperAttrs !!}
    {{ $attributes->class('card-poster group block overflow-hidden') }}
>
    @if ($image)
        <div class="relative aspect-[5/3] overflow-hidden border-b-4 border-ink-900">
            <img
                src="{{ $image }}"
                alt="{{ $imageAlt }}"
                class="h-full w-full object-cover grayscale transition duration-300 group-hover:grayscale-0"
                loading="lazy"
            />
            {{-- Overlay duotone merah untuk kesan poster lama --}}
            <div class="pointer-events-none absolute inset-0 bg-flag-500/30 mix-blend-multiply transition group-hover:bg-flag-500/10"></div>
        </div>
    @endif

    <div class="p-5">
        @if ($meta)
            <div class="meta-stamp mb-2 flex items-center gap-2">
                <span class="inline-block h-1.5 w-1.5 bg-flag-500"></span>
                {{ $meta }}
            </div>
        @endif

        @if ($title)
            <h3 class="font-display text-2xl leading-tight text-ink-900 group-hover:text-flag-600">
                {{ $title }}
            </h3>
        @endif

        @if ($excerpt)
            <p class="mt-3 text-sm leading-relaxed text-ink-700">{{ $excerpt }}</p>
        @endif

        {{ $slot }}
    </div>
</{{ $wrapperTag }}>
