<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Angka anggota di beranda
    |--------------------------------------------------------------------------
    |
    | Selama data anggota belum lengkap, beranda memakai nilai placeholder.
    | Setelah data siap, set HOMEPAGE_USE_REAL_MEMBER_COUNT=true di .env
    | (atau jalankan: php artisan config:clear jika memakai config:cache).
    |
    */
    'use_real_member_count_on_homepage' => env('HOMEPAGE_USE_REAL_MEMBER_COUNT') !== null
        ? filter_var(env('HOMEPAGE_USE_REAL_MEMBER_COUNT'), FILTER_VALIDATE_BOOLEAN)
        : env('APP_ENV') === 'testing',

    'homepage_member_count_display' => (int) env('HOMEPAGE_MEMBER_COUNT_DISPLAY', 7862),

    'homepage_variant' => env('HOMEPAGE_VARIANT', 'modern'),

    'homepage_ab_enabled' => env('HOMEPAGE_AB_ENABLED') !== null
        ? filter_var(env('HOMEPAGE_AB_ENABLED'), FILTER_VALIDATE_BOOLEAN)
        : false,

    /*
    |--------------------------------------------------------------------------
    | Theme (public)
    |--------------------------------------------------------------------------
    |
    | Varian default untuk desain publik. Bisa dioverride sementara via query:
    | ?theme=tani-soft|kopi-kertas|senja-modern
    |
    */
    'theme_default' => env('SEPETAK_THEME', 'tani-soft'),

    'vite_hmr_enabled' => env('SEPETAK_VITE_HMR_ENABLED') !== null
        ? filter_var(env('SEPETAK_VITE_HMR_ENABLED'), FILTER_VALIDATE_BOOLEAN)
        : false,

    /*
    |--------------------------------------------------------------------------
    | Logo (public/img/logo)
    |--------------------------------------------------------------------------
    |
    | Naikkan nilai ini setelah mengganti berkas PNG di public/img/logo agar
    | peramban memuat ulang aset (cache bust).
    |
    */
    'logo_asset_version' => env('LOGO_ASSET_VERSION', '3'),
];
