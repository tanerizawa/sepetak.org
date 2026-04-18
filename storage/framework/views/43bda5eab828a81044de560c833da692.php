<?php
    $siteName        = \App\Models\SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang');
    $siteDescription = \App\Models\SiteSetting::getValue('site_description', 'SEPETAK adalah organisasi massa pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.');
    $siteTagline     = \App\Models\SiteSetting::getValue('site_tagline', 'Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian');
    $contactEmail    = \App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org');
    $contactAddress  = \App\Models\SiteSetting::getValue('contact_address', 'Kabupaten Karawang, Jawa Barat, Indonesia');
    $socialFacebook  = \App\Models\SiteSetting::getValue('social_facebook');
    $socialInstagram = \App\Models\SiteSetting::getValue('social_instagram');
    $socialTwitter   = \App\Models\SiteSetting::getValue('social_twitter');

    $sameAs = array_values(array_filter([$socialFacebook, $socialInstagram, $socialTwitter]));

    $organization = [
        '@context'        => 'https://schema.org',
        '@type'           => 'Organization',
        '@id'             => url('/#organization'),
        'name'            => $siteName,
        'alternateName'   => ['SEPETAK', 'Serikat Pekerja Tani Karawang'],
        'url'             => url('/'),
        'logo'            => [
            '@type' => 'ImageObject',
            'url'   => url('/favicon.ico'),
        ],
        'description'     => $siteDescription,
        'slogan'          => $siteTagline,
        'foundingDate'    => '2007-12-10',
        'foundingLocation' => [
            '@type'   => 'Place',
            'name'    => 'Karawang, Jawa Barat, Indonesia',
            'address' => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Karawang',
                'addressRegion'   => 'Jawa Barat',
                'addressCountry'  => 'ID',
            ],
        ],
        'areaServed' => [
            '@type' => 'AdministrativeArea',
            'name'  => 'Kabupaten Karawang, Jawa Barat, Indonesia',
        ],
        'contactPoint' => [
            '@type'        => 'ContactPoint',
            'contactType'  => 'Sekretariat',
            'email'        => $contactEmail,
            'areaServed'   => 'ID',
            'availableLanguage' => ['Indonesian', 'id'],
        ],
        'address' => [
            '@type'          => 'PostalAddress',
            'streetAddress'  => $contactAddress,
            'addressLocality' => 'Karawang',
            'addressRegion'  => 'Jawa Barat',
            'addressCountry' => 'ID',
        ],
    ];

    if (!empty($sameAs)) {
        $organization['sameAs'] = $sameAs;
    }
?>
<script type="application/ld+json">
<?php echo json_encode($organization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

</script>
<?php /**PATH /home/sepetak.org/resources/views/partials/jsonld/organization.blade.php ENDPATH**/ ?>