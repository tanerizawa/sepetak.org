<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Master switch
    |--------------------------------------------------------------------------
    */
    'enabled' => (bool) env('ARTICLE_GENERATOR_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Provider
    |--------------------------------------------------------------------------
    */
    'provider' => env('ARTICLE_AI_PROVIDER', 'openrouter'),

    'openrouter' => [
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
        'api_key' => env('OPENROUTER_API_KEY'),
        'model' => env('OPENROUTER_MODEL', 'anthropic/claude-3.5-sonnet'),
        'temperature' => (float) env('OPENROUTER_TEMPERATURE', 0.7),
        'max_tokens' => (int) env('OPENROUTER_MAX_TOKENS', 8000),
        'referer' => env('APP_URL', 'https://sepetak.org'),
        'app_title' => env('APP_NAME', 'SEPETAK'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue routing
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'connection' => env('ARTICLE_QUEUE_CONNECTION'),
        'name' => env('ARTICLE_QUEUE_NAME', 'default'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Defaults for generated posts
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'status' => 'draft',
        'auto_publish' => false,
        'author_user_id' => env('ARTICLE_AUTHOR_USER_ID'),
        'min_word_count' => 1500,
        'min_references' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image provider preferences
    |--------------------------------------------------------------------------
    */
    'unsplash' => [
        'enabled' => (bool) env('UNSPLASH_ENABLED', true),
        'access_key' => env('UNSPLASH_ACCESS_KEY'),
    ],
    'pexels' => [
        'enabled' => (bool) env('PEXELS_ENABLED', true),
        'api_key' => env('PEXELS_API_KEY'),
    ],
    'image_provider_order' => array_values(array_filter(array_map(
        fn ($v) => trim($v),
        explode(',', (string) env('ARTICLE_IMAGE_PROVIDER_ORDER', 'wikimedia,pexels,unsplash'))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Zona waktu penjadwalan pool (Asia/Jakarta – WIB)
    |--------------------------------------------------------------------------
    */
    'schedule_timezone' => env('ARTICLE_SCHEDULE_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Profil konten AI (override batas kualitas & gaya prompt)
    |--------------------------------------------------------------------------
    */
    'content_profiles' => [
        'pillar' => [
            'min_word_count' => 1500,
            'min_references' => 5,
            'min_inline_citations' => 5,
        ],
        'member_practical' => [
            'min_word_count' => 450,
            'min_references' => 2,
            'min_inline_citations' => 2,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inferensi jalur materi praktis tanpa pool eksplisit
    |--------------------------------------------------------------------------
    */
    'member_practical_article_types' => [
        'member_guide',
    ],

    'member_practical_category_slugs' => [
        'panduan-tips-anggota',
    ],

    /*
    |--------------------------------------------------------------------------
    | Judul artikel otomatis terbaru yang ditampilkan ke user prompt
    |--------------------------------------------------------------------------
    */
    'member_practical_prompt' => [
        'recent_title_lookback_days' => (int) env('ARTICLE_MEMBER_RECENT_TITLE_DAYS', 14),
        'recent_title_max' => (int) env('ARTICLE_MEMBER_RECENT_TITLE_MAX', 18),
        'recent_title_max_chars' => (int) env('ARTICLE_MEMBER_RECENT_TITLE_CHARS', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Jadwal default 5× sehari untuk pool materi praktis
    | (Bukan hisab astronomis — slot operasional Karawang/WIB.)
    |--------------------------------------------------------------------------
    */
    'default_member_practical_schedule_times' => [
        '04:45',
        '12:10',
        '15:20',
        '18:05',
        '19:25',
    ],
];
