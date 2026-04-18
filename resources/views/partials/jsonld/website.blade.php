@php
    $siteName        = \App\Models\SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang');
    $siteDescription = \App\Models\SiteSetting::getValue('site_description', 'SEPETAK adalah organisasi massa pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.');

    $website = [
        '@context'    => 'https://schema.org',
        '@type'       => 'WebSite',
        '@id'         => url('/#website'),
        'url'         => url('/'),
        'name'        => $siteName,
        'description' => $siteDescription,
        'inLanguage'  => 'id-ID',
        'publisher'   => [
            '@id' => url('/#organization'),
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($website, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
