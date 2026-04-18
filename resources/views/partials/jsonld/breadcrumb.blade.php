@props(['items' => []])

@php
    // items shape: [['name' => 'Label', 'url' => 'https://...'], ...]
    $normalized = collect($items)->values()->map(function ($item, $i) {
        return [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $item['name'] ?? '',
            'item' => $item['url'] ?? '',
        ];
    })->all();

    $ld = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $normalized,
    ];
@endphp

@if(count($normalized))
<script type="application/ld+json">
{!! json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
