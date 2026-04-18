<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WAHA — WhatsApp HTTP API
    |--------------------------------------------------------------------------
    | Integrasi dengan WAHA (self-hosted). Dokumentasi: https://waha.devlike.pro/
    | Repositori: https://github.com/devlikeapro/waha
    |
    | Setelah WAHA berjalan, autentikasi sesi (QR), lalu isi env di bawah.
    */

    'enabled' => (bool) env('WAHA_ENABLED', false),

    'base_url' => rtrim((string) env('WAHA_BASE_URL', 'http://127.0.0.1:3000'), '/'),

    'api_key' => (string) env('WAHA_API_KEY', ''),

    /** Nama sesi WAHA (biasanya `default` pada edisi Core). */
    'session' => (string) env('WAHA_SESSION', 'default'),

    'timeout' => (int) env('WAHA_TIMEOUT', 30),

    'verify_ssl' => filter_var(env('WAHA_VERIFY_SSL', true), FILTER_VALIDATE_BOOL),

    /*
    |--------------------------------------------------------------------------
    | Notifikasi otomatis (antrian)
    |--------------------------------------------------------------------------
    */
    'auto' => [
        'on_post_published' => (bool) env('WAHA_AUTO_POST_PUBLISHED', false),
        'on_event_public' => (bool) env('WAHA_AUTO_EVENT_PUBLIC', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Template pesan (placeholder: :title, :url, :date, :location)
    |--------------------------------------------------------------------------
    */
    'templates' => [
        'post_published' => (string) env(
            'WAHA_TEMPLATE_POST',
            "📰 Artikel baru SEPETAK\n\n:title\n\n:url"
        ),
        'event_public' => (string) env(
            'WAHA_TEMPLATE_EVENT',
            "📅 Kegiatan SEPETAK\n\n:title\n🗓 :date\n📍 :location\n\n:url"
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcast massal — perlindungan throttling
    |--------------------------------------------------------------------------
    */
    'broadcast' => [
        /** Jeda antar pengiriman ke nomor berbeda (milis). */
        'delay_ms_between_sends' => (int) env('WAHA_BROADCAST_DELAY_MS', 1500),
        /** Maksimum penerima per satu eksekusi job (sisanya bisa job berikutnya). */
        'max_recipients_per_job' => (int) env('WAHA_BROADCAST_MAX_PER_JOB', 100),
    ],

];
