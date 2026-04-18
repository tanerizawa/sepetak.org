# StrReplace fragments for `/home/sepetak.org/config/article-generator.php`

Total edits captured in transcript: **3**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
    'defaults' => [
        'status' => 'draft',
        'auto_publish' => false,
        'author_user_id' => env('ARTICLE_AUTHOR_USER_ID'),
        'min_word_count' => 1500,
        'min_references' => 5,
    ],
```

### new_string

```
    'defaults' => [
        'status' => 'draft',
        'auto_publish' => false,
        'author_user_id' => env('ARTICLE_AUTHOR_USER_ID'),
        'min_word_count' => 1500,
        'min_references' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Zona waktu penjadwalan pool (WIB untuk kisaran waktu shalat di Indonesia)
    |--------------------------------------------------------------------------
    */
    'schedule_timezone' => env('ARTICLE_SCHEDULE_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Profil konten AI (override batas kualitas & gaya prompt)
    |--------------------------------------------------------------------------
    | member_practical = artikel ringan untuk anggota (tips, hukum praktis, organisasi).
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
    | Jadwal default 5× sehari (kisaran waktu shalat perkiraan, Karawang/WIB)
    |--------------------------------------------------------------------------
    | Bukan hisab astronomis — slot operasional situs; sesuaikan di Filament per pool.
    */
    'default_member_practical_schedule_times' => [
        '04:45',
        '12:10',
        '15:20',
        '18:05',
        '19:25',
    ],
```

---

## Edit #2

### old_string

```
        'member_practical' => [
            'min_word_count' => 450,
            'min_references' => 2,
            'min_inline_citations' => 2,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Jadwal default 5× sehari (kisaran waktu shalat perkiraan, Karawang/WIB)
```

### new_string

```
        'member_practical' => [
            'min_word_count' => 450,
            'min_references' => 2,
            'min_inline_citations' => 2,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inferensi jalur "materi praktis" bila pool tidak dikirim (generate dari Topik saja)
    |--------------------------------------------------------------------------
    | Banyak topik tips tidak dipasangkan ke pool di pivot; tanpa aturan ini mereka
    | kembali ke prompt pillar. Isi daftar jika menambah tipe artikel ringkas baru.
    */
    'member_practical_article_types' => [
        'member_guide',
    ],

    /** Kategori (slug) yang memakai jalur materi praktis bila pool null. */
    'member_practical_category_slugs' => [
        'panduan-tips-anggota',
    ],

    /*
    |--------------------------------------------------------------------------
    | Jadwal default 5× sehari (kisaran waktu shalat perkiraan, Karawang/WIB)
```

---

## Edit #3

### old_string

```
    /** Kategori (slug) yang memakai jalur materi praktis bila pool null. */
    'member_practical_category_slugs' => [
        'panduan-tips-anggota',
    ],

    /*
    |--------------------------------------------------------------------------
    | Jadwal default 5× sehari (kisaran waktu shalat perkiraan, Karawang/WIB)
```

### new_string

```
    /** Kategori (slug) yang memakai jalur materi praktis bila pool null. */
    'member_practical_category_slugs' => [
        'panduan-tips-anggota',
    ],

    /*
    |--------------------------------------------------------------------------
    | Judul artikel otomatis terbaru di user prompt (jalur materi praktis)
    |--------------------------------------------------------------------------
    | Hanya judul terbaru dalam rentang hari ini yang dimasukkan; jumlahnya
    | dibatasi agar prompt tidak membesar seiring total arsip di database.
    */
    'member_practical_prompt' => [
        'recent_title_lookback_days' => (int) env('ARTICLE_MEMBER_RECENT_TITLE_DAYS', 14),
        /** Maksimum judul yang ditampilkan di prompt (bukan semua arsip). */
        'recent_title_max' => (int) env('ARTICLE_MEMBER_RECENT_TITLE_MAX', 18),
        /** 0 = tanpa potongan per judul. */
        'recent_title_max_chars' => (int) env('ARTICLE_MEMBER_RECENT_TITLE_CHARS', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Jadwal default 5× sehari (kisaran waktu shalat perkiraan, Karawang/WIB)
```

---

