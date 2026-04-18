@php
    /** @var \App\Models\Post $post */
    $siteName = \App\Models\SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang');

    $coverUrl = null;
    if (method_exists($post, 'getFirstMediaUrl')) {
        try {
            $coverUrl = $post->getFirstMediaUrl('cover') ?: null;
        } catch (\Throwable $e) {
            $coverUrl = null;
        }
    }

    $description = $post->excerpt
        ? $post->excerpt
        : \Illuminate\Support\Str::limit(strip_tags((string) $post->body), 200);

    $article = [
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => route('posts.show', $post->slug),
        ],
        'headline'        => $post->title,
        'description'     => $description,
        'inLanguage'      => 'id-ID',
        'datePublished'   => optional($post->published_at)->toIso8601String(),
        'dateModified'    => optional($post->updated_at ?: $post->published_at)->toIso8601String(),
        'author'          => [
            '@type' => 'Person',
            'name'  => optional($post->author)->name ?? $siteName,
        ],
        'publisher'       => [
            '@type' => 'Organization',
            '@id'   => url('/#organization'),
            'name'  => $siteName,
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => url('/favicon.ico'),
            ],
        ],
    ];

    if ($coverUrl) {
        $article['image'] = [$coverUrl];
    }

    $article = array_filter($article, fn($v) => !is_null($v));

    $breadcrumb = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda',  'item' => route('beranda')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Berita',   'item' => route('posts.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('posts.show', $post->slug)],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
