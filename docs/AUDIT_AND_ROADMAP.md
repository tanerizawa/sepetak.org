# SEPETAK — Audit & Roadmap Komprehensif

> **Tanggal:** 17 April 2026
> **Scope:** Public site (sepetak.org) + Admin panel (FilamentPHP 3)
> **Stack:** Laravel 11 · FilamentPHP 3 · Livewire · PostgreSQL 17 · Redis · Spatie Media Library
> **Tujuan:** Mengidentifikasi bug, gap fitur, isu UX/aksesibilitas/keamanan/performa, dan merancang roadmap perbaikan berprioritas.

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Critical Bugs](#2-critical-bugs)
3. [High-Priority Issues](#3-high-priority-issues)
4. [Medium-Priority Improvements](#4-medium-priority-improvements)
5. [Low-Priority Enhancements](#5-low-priority-enhancements)
6. [Security Concerns](#6-security-concerns)
7. [Performance Concerns](#7-performance-concerns)
8. [Accessibility Concerns](#8-accessibility-concerns)
9. [Inkonsistensi UI/UX](#9-inkonsistensi-uiux)
10. [Feature Recommendations](#10-feature-recommendations)
11. [Technical Debt & Refactoring](#11-technical-debt--refactoring)
12. [Good Practices yang Sudah Diimplement](#12-good-practices-yang-sudah-diimplement)
13. [Roadmap Eksekusi](#13-roadmap-eksekusi)
14. [Task Checklist (Actionable)](#14-task-checklist-actionable)

---

## 1. Ringkasan Eksekutif

SEPETAK saat ini adalah **content + CRM site produksi** untuk organisasi serikat tani, dengan 3 fitur utama yang sudah matang:

1. **Artikel pilar** (Markdown → HTML dengan TOC, progress bar, callout, bibliografi) + auto-generation AI via OpenRouter
2. **Registrasi anggota** online dengan notifikasi email ke sekretariat
3. **Admin panel** FilamentPHP 3 dengan 13 resource (Artikel, Halaman, Kategori, Anggota, Kegiatan, Advokasi, Kasus Agraria, Topik Artikel, Pool Artikel, Log Generasi, Site Setting, User, Galeri Album)

**Kondisi umum:** arsitektur bersih, design system "Tani Merah" konsisten, dokumentasi docs/\* lengkap. Namun ada **8 bug kritis** yang perlu segera diperbaiki, **12 high-priority gap** yang memengaruhi UX/SEO/keamanan, dan **20+ rekomendasi fitur** untuk menaikkan kualitas ke level enterprise.

**3 area yang paling butuh perhatian segera:**

- 🔴 **Keamanan**: NIK anggota belum di-encrypt, body artikel di-render tanpa sanitasi XSS, route export PII tanpa policy check
- 🔴 **SEO**: `og:image` tidak per-artikel, galeri tidak di sitemap, tidak ada archive kategori/tag
- 🔴 **Admin UX**: PostResource form tidak punya field kategori/tag/ai_disclosure, SiteSetting key bebas ketik, tidak ada search publik

---

## 2. Critical Bugs

Bug yang harus segera diperbaiki (blocking atau breaking).

### C-1. Tahun pendirian salah di hero homepage

**Severity:** 🔴 Critical (kredibilitas organisasi)
**File:** [resources/views/home.blade.php](resources/views/home.blade.php)
**Issue:** Hero kiri stamp tertulis **"Didirikan 1999"** padahal organisasi berdiri **2007** (konsisten di navbar, footer, ticker, JSON-LD `foundingDate: 2007-12-10`).
**Fix:** Ubah `1999` → `2007` atau parametrisasi ke `SiteSetting::getValue('founded_year')`.

### C-2. Typo di copy beranda

**Severity:** 🔴 Critical (readability)
**File:** [resources/views/home.blade.php](resources/views/home.blade.php)
**Issue:** "Organisasi berdiri sejak 2007 **dsampai** saat ini." → seharusnya "hingga" atau "sampai".
**Fix:** Perbaiki typo.

### C-3. Stat homepage menyesatkan

**Severity:** 🔴 Critical (transparansi publik)
**File:** [app/Http/Controllers/HomeController.php](app/Http/Controllers/HomeController.php)
**Issue:**

- `case_count = AgrarianCase::count()` ditampilkan dengan label _"kasus yang sedang didampingi"_ — termasuk `resolved` & `closed`
- `program_count = AdvocacyProgram::count()` dengan label _"yang aktif dijalankan"_ — termasuk `completed`/`paused`/`planned`
  **Fix:**

```php
'case_count' => AgrarianCase::whereNotIn('status', ['resolved','closed'])->count(),
'program_count' => AdvocacyProgram::where('status','active')->count(),
```

### C-4. PostResource form tidak punya field kategori, tag, AI disclosure

**Severity:** 🔴 Critical (fungsional admin)
**File:** [app/Filament/Resources/PostResource.php](app/Filament/Resources/PostResource.php)
**Issue:** Tabel `post_category`, `post_tag`, dan kolom `ai_disclosure` ada di DB, relasi ada di Post model, tapi form admin tidak mengekspos. Admin tidak bisa mengkategorikan artikel manual. Sidebar `posts/show` kosong untuk semua artikel manual.
**Fix:** Tambahkan di form:

```php
Forms\Components\Select::make('categories')->relationship('categories','name')->multiple()->preload()->searchable(),
Forms\Components\Select::make('tags')->relationship('tags','name')->multiple()->preload()->searchable()->createOptionForm([
    Forms\Components\TextInput::make('name')->required(),
]),
Forms\Components\Toggle::make('ai_disclosure')->label('Artikel AI')->default(false),
```

### C-5. GalleryAlbumPolicy tidak terdaftar

**Severity:** 🔴 Critical (keamanan authorization)
**File:** [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)
**Issue:** Tidak ada `GalleryAlbumPolicy`. `operator`/`viewer` bisa mengelola album tanpa batas permission `manage-content`. Hanya superadmin di-bypass via `Gate::before`.
**Fix:** Buat `app/Policies/GalleryAlbumPolicy.php` extends `BaseResourcePolicy` dengan `protected string $permission = 'manage-content';` dan register di `AppServiceProvider::registerPolicies()`.

### C-6. SiteSetting::getValue() tanpa cache — N+1 kronis

**Severity:** 🔴 Critical (performa)
**File:** [app/Models/SiteSetting.php](app/Models/SiteSetting.php)
**Issue:** Setiap panggilan = 1 query DB. Layout memanggil ~10× per render (title, description, og:\*, footer), ditambah partials JSON-LD. Setiap page request = 15+ query untuk settings saja.
**Fix:**

```php
public static function getValue(string $key, mixed $default = null): mixed
{
    return \Cache::remember("site_setting:$key", 3600, function () use ($key, $default) {
        return static::where('setting_key', $key)->value('setting_value') ?? $default;
    });
}

protected static function booted(): void
{
    static::saved(fn ($m) => \Cache::forget("site_setting:{$m->setting_key}"));
    static::deleted(fn ($m) => \Cache::forget("site_setting:{$m->setting_key}"));
}
```

### C-7. og:image global — tidak per-artikel

**Severity:** 🔴 Critical (SEO & social sharing)
**File:** [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php), [resources/views/posts/show.blade.php](resources/views/posts/show.blade.php)
**Issue:** Layout set `<meta property="og:image">` ke `logo-512.png` tanpa `@yield`. Share artikel ke WhatsApp/FB/Twitter selalu pakai logo, bukan cover image. Engagement loss.
**Fix:**

- Layout: `<meta property="og:image" content="@yield('og_image', asset('img/logo/logo-512.png'))">`
- posts/show: `@section('og_image', $post->getFirstMediaUrl('cover') ?: asset('img/logo/logo-512.png'))`
- Tambahkan `og:image:width` dan `og:image:height` dari Spatie Media.

### C-8. Sitemap tidak memuat galeri

**Severity:** 🔴 Critical (SEO)
**File:** [app/Http/Controllers/FeedController.php](app/Http/Controllers/FeedController.php)
**Issue:** Route `/galeri` dan setiap `gallery.show` tidak terdaftar di sitemap. Google tidak akan meng-index album.
**Fix:** Tambahkan loop `GalleryAlbum::published()` + entry `/galeri` index di `FeedController::sitemap()`.

---

## 3. High-Priority Issues

Masalah signifikan yang memengaruhi UX, SEO, atau keamanan tapi tidak langsung blocking.

### H-1. Tidak ada halaman error kustom (404/500/503)

**File:** `resources/views/errors/` (tidak ada)
**Issue:** Pengguna landing di halaman error default Laravel yang tidak sesuai brand.
**Fix:** `php artisan vendor:publish --tag=laravel-errors` → kustom dengan `layouts.app`.

### H-2. Tidak ada fitur pencarian publik

**Issue:** Tidak ada route `/cari`, tidak ada controller/view. Untuk content site dengan artikel akademik ini gap besar.
**Fix:** `Route::get('/cari', [PostController::class, 'search'])` dengan PostgreSQL `to_tsvector` atau Laravel Scout + Typesense.

### H-3. Tidak ada archive kategori/tag/penulis

**File:** [resources/views/posts/show.blade.php](resources/views/posts/show.blade.php)
**Issue:** Tag di sidebar ditampilkan sebagai `<span>` yang tidak clickable (dead-end UX). Model Category & Tag ada, tapi tidak ada route `/kategori/{slug}` atau `/tag/{slug}`.
**Fix:** Tambah route + controller + view. Ubah `sidebar-tag` jadi `<a>`.

### H-4. Tidak ada related articles di posts/show

**Issue:** Setelah membaca artikel, pembaca langsung ke CTA "Daftar Anggota" — bounce rate tinggi.
**Fix:** Section "Artikel Terkait" berdasarkan overlap kategori/tag, limit 3, tampil sebelum CTA.

### H-5. MemberRegistration tanpa CAPTCHA/honeypot

**File:** [app/Http/Controllers/MemberRegistrationController.php](app/Http/Controllers/MemberRegistrationController.php)
**Issue:** Hanya throttle 5/menit/IP — botnet residential dengan rotating IP tembus mudah. DB anggota berisiko terisi spam.
**Fix:** `spatie/laravel-honeypot` atau Cloudflare Turnstile.

### H-6. XSS stored risk — body artikel di-render `{!! !!}`

**Severity:** 🟠 High (security)
**File:** [resources/views/posts/show.blade.php](resources/views/posts/show.blade.php), [resources/views/pages/show.blade.php](resources/views/pages/show.blade.php)
**Issue:** RichEditor Filament tidak filter ke whitelist ketat. Admin kompromis atau AI prompt injection bisa inject `<script>`.
**Fix:** `mews/purifier` + `PostObserver::saving()` → purify body sebelum simpan; atau purify saat render dengan `{!! clean($post->body) !!}`.

### H-7. PostResource tidak tampilkan info AI traceability

**File:** [app/Filament/Resources/PostResource.php](app/Filament/Resources/PostResource.php)
**Issue:** Admin tidak bisa melihat topic/log terhubung saat mengedit artikel AI. Hanya ada placeholder notice.
**Fix:** `Placeholder` di section "Informasi AI" visible ketika `source_type === 'auto_generated'` — tampilkan link ke topic & log.

### H-8. SiteSettingResource form primitif — key bebas ketik

**File:** [app/Filament/Resources/SiteSettingResource.php](app/Filament/Resources/SiteSettingResource.php)
**Issue:** Admin harus tahu semua `setting_key`. Rawan typo → setting orphan.
**Fix:** Custom Filament Page `Settings` dengan tab grup + field fixed (site*name, site_description, contact*_, social\__). Atau enum `SettingKey`.

### H-9. member_code tidak atomic-safe

**File:** [app/Models/Member.php](app/Models/Member.php)
**Issue:** Generator random 5 char tanpa retry-on-collision & tanpa unique constraint di DB (perlu verifikasi migrasi).
**Fix:** Loop-until-unique + tambah `unique` index migration.

### H-10. PostPolicy/PagePolicy tidak cek ownership

**Issue:** Operator dapat menghapus artikel penulis lain. Tidak granular.
**Fix:** Override `update/delete`: `return $user->hasAnyRole(['superadmin','admin']) || $record->author_id === $user->id;`

### H-11. Navigasi desktop vs mobile tidak konsisten

**File:** [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)
**Issue:** Desktop: Beranda, Artikel, Tentang, Sejarah, Struktur, Galeri. Mobile tambah: Wilayah Kerja, Kontak. User desktop tidak bisa mencapai halaman tersebut dari nav.
**Fix:** Samakan — tambah "Wilayah Kerja" & "Kontak" ke desktop (mungkin dropdown "Tentang") atau tambahkan dropdown menu.

### H-12. btn-rev-outline missing class

**File:** [resources/views/gallery/show.blade.php](resources/views/gallery/show.blade.php) (baris 147)
**Issue:** Class `btn-rev-outline` dirujuk tapi tidak ada di [resources/css/app.css](resources/css/app.css). Tombol "Kembali ke Galeri" tampil polos.
**Fix:** Ganti ke `btn-rev btn-rev-ghost` atau tambahkan definisi `btn-rev-outline` di app.css.

---

## 4. Medium-Priority Improvements

Fitur/perbaikan yang seharusnya ada tapi tidak blocking.

| ID   | Area   | Deskripsi                                                                                                                                            | File                                                    |
| ---- | ------ | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------- |
| M-1  | Public | Filter tanggal/kategori/search di `/artikel` index                                                                                                   | resources/views/posts/index.blade.php                   |
| M-2  | Public | Ticker `role="marquee"` bukan ARIA valid — ganti `role="region"`                                                                                     | resources/views/components/rev/ticker.blade.php         |
| M-3  | Admin  | Widget dashboard tanpa cache — query heavy per request                                                                                               | app/Filament/Widgets/\*                                 |
| M-4  | Admin  | PostResource tabel tanpa thumbnail cover                                                                                                             | app/Filament/Resources/PostResource.php                 |
| M-5  | Admin  | Tidak ada action "Duplicate" di PostResource                                                                                                         |                                                         |
| M-6  | Admin  | GalleryAlbumResource Repeater untuk items lambat — ganti RelationManager dengan `SpatieMediaLibraryFileUpload::make()->multiple()` untuk bulk upload | app/Filament/Resources/GalleryAlbumResource.php         |
| M-7  | Admin  | PageResource form tanpa `author_id` dan `meta_description` editable                                                                                  | app/Filament/Resources/PageResource.php                 |
| M-8  | Admin  | CategoryResource tanpa kolom color/icon — kategori tampil polos di public                                                                            | app/Filament/Resources/CategoryResource.php             |
| M-9  | Admin  | ArticlePool UI tidak tampilkan "next run time"                                                                                                       | app/Filament/Resources/ArticlePoolResource.php          |
| M-10 | Admin  | ArticleGenerationLog tidak punya action "Retry"                                                                                                      | app/Filament/Resources/ArticleGenerationLogResource.php |
| M-11 | Admin  | Tidak ada AuditLogResource walau model & migrasi ada                                                                                                 | app/Models/AuditLog.php                                 |
| M-12 | Admin  | Tidak ada backup/restore UI — `scripts/backup.sh` manual                                                                                             | scripts/backup.sh                                       |
| M-13 | Admin  | Tidak ada queue monitor (Horizon / filament-jobs-monitor)                                                                                            |                                                         |
| M-14 | Admin  | Tidak ada filter periode di dashboard widgets                                                                                                        |                                                         |
| M-15 | Public | Footer `contact_phone` filter brittle — hardcode placeholder `"+62 xxx xxxx xxxx"`                                                                   | resources/views/layouts/app.blade.php                   |
| M-16 | Public | Mobile menu `max-height: 560px` hard-coded                                                                                                           | resources/css/app.css                                   |
| M-17 | Admin  | Tidak ada dark mode Filament                                                                                                                         | app/Providers/Filament/AdminPanelProvider.php           |
| M-18 | Public | Homepage stat `Member::count()` tanpa cache — query per request                                                                                      | app/Http/Controllers/HomeController.php                 |
| M-19 | Public | Sitemap tanpa cache — unlimited `get()`                                                                                                              | app/Http/Controllers/FeedController.php                 |
| M-20 | Public | Tidak ada `srcset`/`sizes` pada cover artikel di mobile                                                                                              | resources/views/posts/show.blade.php                    |
| M-21 | Public | Gallery conversions tidak terdaftar — thumbnail grid download full-size                                                                              | app/Models/GalleryItem.php                              |
| M-22 | Admin  | `FileUpload::make('cover_upload')->dehydrated(false)` orphan — intermediate file mungkin tidak terhapus                                              | app/Filament/Resources/PostResource.php                 |

---

## 5. Low-Priority Enhancements

Nice-to-haves yang tidak urgent.

- **L-1.** Komentar usang di view — bersihkan
- **L-2.** Dedup Address saat registrasi member (saat ini buat baru setiap submit)
- **L-3.** RSS per kategori (`/feed/pemikiran-kritis.xml`)
- **L-4.** Tombol WhatsApp floating contact
- **L-5.** Share buttons (WhatsApp/Twitter/FB/copy-link) di artikel
- **L-6.** `humans.txt` / `security.txt`
- **L-7.** `favicon.ico` fallback di `public/`
- **L-8.** Form registrasi anggota tambah upload KTP (model `MemberDocument` sudah ada)
- **L-9.** Newsletter signup (Brevo/Mailchimp)
- **L-10.** JSON-LD BreadcrumbList untuk pages & gallery
- **L-11.** Drop-cap artikel opsional (CSS class `.drop-cap`)
- **L-12.** Print-friendly stylesheet untuk artikel

---

## 6. Security Concerns

Isu keamanan yang perlu perhatian.

### S-1. XSS Stored (🔴 Critical) — lihat H-6

Body artikel/page di-render via `{!! !!}` tanpa sanitasi whitelist.

### S-2. NIK anggota tidak di-encrypt (🔴 Critical)

**File:** [app/Models/Member.php](app/Models/Member.php)
**Issue:** MemberResource tampilkan label "akan dienkripsi" tapi `casts()` tidak mengandung `'nik' => 'encrypted'`. PII plaintext di DB.
**Fix:**

```php
protected function casts(): array
{
    return [
        ...,
        'nik' => 'encrypted',
    ];
}
```

**Warning:** data existing perlu migrasi one-time: baca plaintext → simpan kembali (observer akan encrypt).

### S-3. Route export PII tanpa policy check (🟠 High)

**File:** [routes/web.php](routes/web.php)
**Issue:** `/admin/exports/members.pdf` & `agrarian-cases.pdf` hanya `auth` middleware, tidak ada `can:*`.
**Fix:**

```php
Route::get('members.pdf', ...)->middleware(['can:manage-members']);
Route::get('agrarian-cases.pdf', ...)->middleware(['can:manage-cases']);
```

### S-4. Tidak ada rate limit eksplisit pada admin login (🟠 High)

Filament default throttle perlu verifikasi. Tambah eksplisit:

```php
Route::middleware('throttle:5,1')->group(...)
```

### S-5. Tidak ada 2FA untuk superadmin (🟡 Medium)

Plugin tersedia: `stechstudio/filament-impersonate` + 2FA filament plugin.

### S-6. Tidak ada Content Security Policy header (🟡 Medium)

Tambah middleware CSP (allow self, Google Fonts, YouTube/Vimeo iframe untuk gallery).

### S-7. `/health` endpoint publik rentan amplifikasi (🟡 Medium)

DB query per request tanpa rate limit.
**Fix:** `->middleware('throttle:30,1')`.

### S-8. Wikimedia cover fetch tanpa MIME/size validation (🟡 Medium)

**File:** [app/Services/ArticleImageService.php](app/Services/ArticleImageService.php)
**Issue:** External URL fetch tanpa cek Content-Type match, tanpa max file size.
**Fix:** Tambah validasi Content-Type starts with `image/` dan Content-Length ≤ 10MB sebelum simpan.

### S-9. SESSION_ENCRYPT=false default (🟡 Low)

Untuk admin panel PII, pertimbangkan `true`.

---

## 7. Performance Concerns

### P-1. SiteSetting tanpa cache — lihat C-6 (🔴 Critical)

### P-2. Sitemap tanpa cache + unlimited query (🟠 High)

**File:** [app/Http/Controllers/FeedController.php](app/Http/Controllers/FeedController.php)
**Fix:**

```php
return Cache::remember('sitemap.xml', 3600, function () {
    return Post::published()->chunk(500, ...); // stream XML
});
```

### P-3. Gallery full-size thumbnails (🟠 High)

**File:** [app/Models/GalleryItem.php](app/Models/GalleryItem.php)
**Fix:** Tambah media conversions:

```php
public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('thumb')->width(400)->height(400)->sharpen(10);
    $this->addMediaConversion('preview')->width(1200)->height(800);
}
```

Di view thumbnail: `$item->getFirstMediaUrl('gallery_photo', 'thumb')`.

### P-4. Homepage Member::count() tanpa cache (🟡 Medium)

**Fix:** `Cache::remember('homepage.stats', 300, fn () => [...])`.

### P-5. Dashboard widgets query heavy tanpa cache (🟡 Medium)

`MembersChartWidget`, `CasesChartWidget` — 24 bulan full scan.
**Fix:** `Cache::remember('widgets.members_chart', 600, ...)`.

### P-6. Google Fonts blocking ~180KB (🟡 Medium)

4 font families × multi weights.
**Fix:** Self-host subset Latin + preload WOFF2 critical.

### P-7. `view:cache` belum otomatis di deploy (🟡 Low)

**File:** `scripts/deploy.sh`
Verifikasi `php artisan view:cache && route:cache && config:cache`.

### P-8. `cover` image tanpa `srcset` responsive (🟡 Medium)

**Fix:** Register Spatie conversions untuk `cover`: thumb (400), card (800), hero (1600) + pakai `<img srcset>` di card.

---

## 8. Accessibility Concerns

### A-1. `role="marquee"` tidak valid (🟡 Medium) — lihat M-2

### A-2. Form error tidak ditautkan ke field (🟡 Medium)

**File:** [resources/views/member-registration/create.blade.php](resources/views/member-registration/create.blade.php)
**Fix:** Setiap input:

```html
@error('full_name') aria-invalid="true" aria-describedby="err-full_name"
@enderror
<p id="err-full_name">...</p>
```

### A-3. Back-to-top button clickable saat invisible (🟡 Low)

**File:** `resources/css/app.css`
**Fix:** `pointer-events:none` di default state, override di `.visible`.

### A-4. Mobile menu tanpa focus trap (🟡 Medium)

Keyboard user bisa tab keluar dari menu terbuka.
**Fix:** JS focus trap saat menu open.

### A-5. Gallery lightbox tanpa focus trap & focus return (🟡 Medium)

**File:** [resources/views/gallery/show.blade.php](resources/views/gallery/show.blade.php)
**Fix:** Store trigger reference, return focus on close.

### A-6. Bahasa asing tanpa `lang` attribute (🟡 Low)

Kata "Soko Guru" (Jawa) — tidak kritis.

---

## 9. Inkonsistensi UI/UX

- **I-1.** Tahun 1999 vs 2007 (C-1)
- **I-2.** Nav desktop vs mobile (H-11)
- **I-3.** `btn-rev-outline` missing (H-12)
- **I-4.** Kartu artikel tidak tampilkan kategori (konsekuensi C-4)
- **I-5.** Breadcrumb JSON-LD hanya untuk posts, tidak pages/gallery
- **I-6.** Footer default text vs JSON-LD tagline berbeda sumber
- **I-7.** Label admin konsisten Indonesia ✅
- **I-8.** Palet warna konsisten via Tailwind config ✅

---

## 10. Feature Recommendations

Fitur yang wajar ada di content site organisasi rakyat tapi belum diimplement.

### Tier 1 — Fitur Publik Penting

1. **Search publik** — `/cari?q=` dengan highlight
2. **Archive kategori/tag/penulis** — `/kategori/{slug}`, `/tag/{slug}`, `/penulis/{slug}`
3. **Related articles** di posts/show
4. **Share buttons** + copy-link
5. **Halaman Agenda/Kalender** publik (model `Event` sudah ada)
6. **Halaman Kasus Agraria** publik — transparansi advokasi (judul + ringkasan, sensor data sensitif)
7. **Halaman Program Advokasi** showcase publik
8. **Form kontak** interaktif (sekarang hanya static page)

### Tier 2 — Admin Power Features

9. **Activity log** (`spatie/laravel-activitylog`) + resource di admin
10. **Queue monitor** (`filament-jobs-monitor`)
11. **Backup UI** + schedule otomatis
12. **Impersonation** untuk superadmin debug
13. **Admin quick-generate** — page "Generate Artikel Sekarang" dengan selector pool+topic
14. **Bulk media manager** — upload banyak foto sekaligus ke galeri
15. **Custom Settings page** dengan tab (SEO, Social, Kontak, Branding)

### Tier 3 — Pengalaman Pengguna

16. **Dark mode** untuk public site
17. **PWA/offline** — manifest.webmanifest
18. **Multilingual ID/EN** — `lang()` helper
19. **Newsletter signup** + Brevo/Mailchimp integration
20. **Donation button** (kalau organisasi menerima)

### Tier 4 — Data & Analitik

21. **Analytics dashboard** (page views per artikel)
22. **Member demographics chart** (per wilayah, per tahun gabung)
23. **AI article cost tracker** (tokens × harga model)

---

## 11. Technical Debt & Refactoring

- **T-1.** `SiteSetting::getValue` inline di banyak Blade → buat view composer: `site_setting('key')` helper
- **T-2.** Duplikasi meta structure layout vs JSON-LD → konsolidasi `<x-seo :post="$post"/>`
- **T-3.** posts/show JS inline (TOC + progress) >100 baris → extract ke `resources/js/article.js`
- **T-4.** member-registration `<style>` block besar → pindah ke app.css sebagai components
- **T-5.** Bulk action `publish`/`archive` duplikasi di banyak resource → trait `HasPublishingBulkActions`
- **T-6.** Tidak ada `Form Request` dimanapun — validasi inline di controller
- **T-7.** `BaseResourcePolicy` tanpa `restore`/`forceDelete` granular
- **T-8.** `.env.example` tidak memuat ARTICLE*GENERATOR*_, OPENROUTER\__, PEXELS_API_KEY, UNSPLASH_ACCESS_KEY — onboarding developer gagal
- **T-9.** `routes/console.php` tidak punya `backup:run` schedule
- **T-10.** Test coverage lemah — tidak ada test untuk ArticleGeneratorService, TopicPicker, ResponseParser, GalleryController
- **T-11.** `ArticlePool::isDueAt()` logic belum di-unit test
- **T-12.** `MemberRegistrationController::store` create Address inline → Form Request + Repository

---

## 12. Good Practices yang Sudah Diimplement

Apresiasi untuk hal-hal yang sudah solid.

- ✅ Policy pattern dengan `BaseResourcePolicy` + superadmin bypass via `Gate::before`
- ✅ Skip-to-content link untuk keyboard user
- ✅ CSS `prefers-reduced-motion` handling
- ✅ `loading="lazy"` & `decoding="async"` konsisten
- ✅ Spatie Media Library untuk semua upload (bukan FK manual)
- ✅ JSON-LD Organization, NewsArticle, BreadcrumbList untuk posts
- ✅ RSS feed + sitemap.xml tersedia
- ✅ Rate-limit pada public form registration
- ✅ SoftDeletes pada semua domain model
- ✅ Notifikasi pendaftaran dengan graceful failure (log-only)
- ✅ Article generator dengan rate limit + cooldown + queue + ShouldBeUnique (baru)
- ✅ PostObserver auto-attach cover saat publish (baru)
- ✅ Export Excel + PDF lengkap
- ✅ Filament widget color palette konsisten dengan "Tani Merah"
- ✅ Seeder sensible (role/permission/user superadmin)
- ✅ Design system dokumentasi lengkap di docs/
- ✅ `Member::booted` auto-generate `member_code`
- ✅ Filament Indonesia localization konsisten
- ✅ Custom auth login page dengan brand

---

## 13. Roadmap Eksekusi

### Sprint 1 — Quick Wins (1 sesi, ~3 jam)

**Tujuan:** Perbaikan low-risk high-impact.

- [ ] C-1: 1999 → 2007 di home.blade.php
- [ ] C-2: Fix typo "dsampai"
- [ ] C-3: Filter stats homepage by status
- [ ] C-6: Cache SiteSetting::getValue
- [ ] C-7: og:image per-artikel
- [ ] C-8: Sitemap gallery
- [ ] H-1: Error views 404/500/503
- [ ] H-12: Fix btn-rev-outline
- [ ] S-3: Policy check export routes
- [ ] T-8: Lengkapi .env.example

### Sprint 2 — Admin UX & Data (4-6 jam)

**Tujuan:** Menutup gap admin panel.

- [ ] C-4: PostResource form + categories/tags/ai_disclosure
- [ ] C-5: GalleryAlbumPolicy
- [ ] H-7: PostResource AI traceability section
- [ ] H-8: Custom Settings page (Filament cluster)
- [ ] H-10: Ownership check di PostPolicy/PagePolicy
- [ ] M-4: PostResource tabel thumbnail
- [ ] M-5: Action "Duplicate" di PostResource
- [ ] M-7: PageResource author + meta
- [ ] M-8: CategoryResource color/icon

### Sprint 3 — Security Hardening (2-3 jam)

**Tujuan:** Menutup celah keamanan.

- [ ] S-2: Encrypt NIK cast + migrasi data
- [ ] S-1/H-6: Install `mews/purifier` + PostObserver sanitize
- [ ] S-4: Rate limit admin login
- [ ] S-7: Rate limit /health
- [ ] S-8: Validasi MIME/size di ArticleImageService
- [ ] H-5: Honeypot di form registrasi anggota

### Sprint 4 — SEO & Public UX (4-5 jam)

**Tujuan:** Fitur publik yang hilang.

- [ ] H-2: Fitur pencarian publik
- [ ] H-3: Archive kategori + tag + author
- [ ] H-4: Related articles di posts/show
- [ ] H-11: Konsistensi nav desktop/mobile
- [ ] Tier 1 feature #5 (Agenda), #6 (Kasus publik), #7 (Program publik)
- [ ] M-1: Filter di /artikel

### Sprint 5 — Performance (3-4 jam)

**Tujuan:** TTFB < 300ms.

- [ ] P-2: Sitemap cache + chunked
- [ ] P-3: Gallery conversions (thumb/preview)
- [ ] P-4: Homepage stats cache
- [ ] P-5: Dashboard widgets cache
- [ ] P-6: Self-host Google Fonts subset
- [ ] P-8: Cover conversions + srcset

### Sprint 6 — Accessibility (2 jam)

- [ ] A-1: Ticker role fix
- [ ] A-2: Form error aria-describedby
- [ ] A-3: Back-to-top pointer-events
- [ ] A-4: Mobile menu focus trap
- [ ] A-5: Lightbox focus return

### Sprint 7 — Admin Power Features (6-8 jam)

- [ ] M-11: AuditLogResource (spatie/laravel-activitylog)
- [ ] M-13: Queue monitor (filament-jobs-monitor)
- [ ] M-12: Backup UI + schedule otomatis
- [ ] Tier 2 feature #13: Quick-generate page
- [ ] Tier 2 feature #12: Impersonation

### Sprint 8 — Feature Expansion (ongoing)

- [ ] Tier 1 features selengkapnya
- [ ] Tier 2 features sesuai prioritas bisnis
- [ ] Tier 3 Dark mode & PWA
- [ ] Tier 4 Analytics dashboard

---

## 14. Task Checklist (Actionable)

### 🔴 Critical (kerjakan dalam 1 minggu)

| #   | Task                                               | File                    | Estimasi |
| --- | -------------------------------------------------- | ----------------------- | -------- |
| C-1 | Ubah tahun pendirian 1999→2007 di hero             | home.blade.php          | 5m       |
| C-2 | Fix typo "dsampai"                                 | home.blade.php          | 2m       |
| C-3 | Filter stats by status                             | HomeController.php      | 15m      |
| C-4 | PostResource form + categories/tags/ai_disclosure  | PostResource.php        | 30m      |
| C-5 | GalleryAlbumPolicy + register                      | app/Policies/           | 15m      |
| C-6 | Cache SiteSetting::getValue                        | SiteSetting.php         | 20m      |
| C-7 | og:image per-artikel                               | layouts/app, posts/show | 15m      |
| C-8 | Sitemap gallery                                    | FeedController.php      | 15m      |
| S-1 | Install mews/purifier + PostObserver sanitize body | composer + Observer     | 30m      |
| S-2 | Encrypt NIK + data migration                       | Member.php + script     | 45m      |
| S-3 | Policy middleware export routes                    | routes/web.php          | 10m      |

**Total: ~3.5 jam**

### 🟠 High (2-4 minggu)

| #    | Task                              | Estimasi |
| ---- | --------------------------------- | -------- |
| H-1  | Error views 404/500/503           | 30m      |
| H-2  | Search publik                     | 2h       |
| H-3  | Archive kategori/tag/author       | 3h       |
| H-4  | Related articles                  | 45m      |
| H-5  | Honeypot registrasi               | 30m      |
| H-7  | PostResource AI traceability      | 20m      |
| H-8  | Custom Settings page              | 1.5h     |
| H-9  | member_code atomic + unique index | 45m      |
| H-10 | Ownership check policies          | 30m      |
| H-11 | Nav konsistensi                   | 15m      |
| H-12 | Fix btn-rev-outline               | 5m       |
| S-4  | Rate limit admin login            | 10m      |
| S-7  | Rate limit /health                | 5m       |
| S-8  | Validasi MIME image service       | 20m      |
| P-2  | Sitemap cache                     | 30m      |
| P-3  | Gallery conversions               | 30m      |

**Total: ~11.5 jam**

### 🟡 Medium (1-2 bulan)

Lihat Sprint 4-7. Total estimasi ~20 jam.

### 🔵 Low (backlog)

Lihat Sprint 8 + Low-Priority Enhancements. Total ~15 jam.

---

## Lampiran A — Perintah Quick-Start Fix

```bash
# 1. Quick wins (Sprint 1)
php artisan vendor:publish --tag=laravel-errors
composer require mews/purifier
composer require spatie/laravel-honeypot
composer require spatie/laravel-activitylog
composer require bezhansalleh/filament-shield          # optional: advanced RBAC
composer require awcodes/filament-table-repeater       # optional: better GalleryItem UX
composer require saade/filament-laravel-log            # optional: view Laravel log

# 2. Testing coverage
php artisan make:test ArticleGeneratorServiceTest --unit
php artisan make:test GalleryControllerTest
php artisan make:test SitemapTest

# 3. Performance
php artisan optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache
php artisan event:cache
```

## Lampiran B — Metric Target Setelah Semua Sprint Selesai

| Metric                     | Baseline (estimasi) | Target                            |
| -------------------------- | ------------------- | --------------------------------- |
| TTFB homepage              | ~800ms              | <300ms                            |
| Lighthouse Performance     | ~70                 | ≥90                               |
| Lighthouse Accessibility   | ~85                 | ≥95                               |
| Lighthouse SEO             | ~80                 | ≥95                               |
| Test coverage              | ~15%                | ≥60%                              |
| Google indexed pages       | ~6                  | ≥50 (artikel + galeri + kategori) |
| Admin task completion time | n/a                 | -40% dengan search + bulk actions |

---

**Dokumen ini adalah living document.** Update saat ada sprint selesai, bug baru ditemukan, atau prioritas berubah. Commit ke `docs/AUDIT_AND_ROADMAP.md` dan tinjau tiap akhir sprint.
