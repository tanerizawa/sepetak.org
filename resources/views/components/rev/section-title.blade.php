@props([
    'eyebrow' => null,
    'title' => '',
    'align' => 'left', // left | center
])

@php
    $alignCls = $align === 'center' ? 'text-center items-center' : 'text-left items-start';
@endphp

<div {{ $attributes->class('mb-8 flex flex-col gap-3 ' . $alignCls) }}>
    @if ($eyebrow)
        <div class="meta-stamp flex items-center gap-3">
            <span class="inline-block h-[3px] w-12 bg-flag-500"></span>
            <span>{{ $eyebrow }}</span>
            <span class="inline-block h-[3px] w-12 bg-flag-500"></span>
        </div>
    @endif
    <h2 class="font-display text-4xl leading-none sm:text-5xl md:text-6xl">{{ $title }}</h2>
    @isset ($slot)
        <div class="mt-1 max-w-2xl text-base leading-relaxed text-ink-700">{{ $slot }}</div>
    @endisset
</div>
