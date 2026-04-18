@props([
    'href' => null,
    'variant' => 'solid', // solid | ghost | red
    'type' => 'button',
])

@php
    $classes = match ($variant) {
        'ghost' => 'btn-rev btn-rev-ghost',
        'red' => 'btn-rev btn-rev-red',
        default => 'btn-rev btn-rev-solid',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
