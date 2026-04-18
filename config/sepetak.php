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
