@props([
    'value' => '0',
    'label' => '',
    'prefix' => null,
    'suffix' => null,
])

<div {{ $attributes->class('flex flex-col gap-2') }}>
    <div class="flex items-baseline gap-1">
        @if ($prefix)
            <span class="font-display text-3xl text-ink-900">{{ $prefix }}</span>
        @endif
        <span class="stat-number">{{ $value }}</span>
        @if ($suffix)
            <span class="font-display text-3xl text-ink-900">{{ $suffix }}</span>
        @endif
    </div>
    <span class="stat-label">{{ $label }}</span>
</div>
