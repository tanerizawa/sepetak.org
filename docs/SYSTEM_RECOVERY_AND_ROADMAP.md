# SEPETAK.ORG — Laporan Riset Sistem, Recovery, dan Roadmap 100%

**Dokumen:** `docs/SYSTEM_RECOVERY_AND_ROADMAP.md`
**Tanggal:** 18 April 2026
**Audiens:** Maintainer SEPETAK (developer + operasional)
**Konteks:** Sesi agen sebelumnya (`agent-transcripts/c000dcc7-…`) membangun banyak perubahan, namun sebagian file di working copy hilang saat agen di-restart. Dokumen ini merangkum hasil analisis sistem, status recovery, dan rencana perbaikan komprehensif hingga 100%.

---

## 1. Ringkasan Eksekutif

| Metrik                                                                            | Nilai                                                                                  |
| --------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| File yang tercatat ditulis/ubah oleh agen sebelumnya (path `/home/sepetak.org/…`) | **220**                                                                                |
| File yang **hilang total** saat audit                                             | **172**                                                                                |
| File yang berhasil **dipulihkan penuh** dari event `Write` di transcript          | **137**                                                                                |
| File yang hanya bisa dipulihkan **parsial** (event `StrReplace` tanpa `Write`)    | **35** (14 OK + 21 stub rusak)                                                         |
| File stub yang **di-karantina** agar Laravel dapat boot                           | **22** (di `docs/recovery/stubs/`)                                                     |
| Fragment `StrReplace` yang **diekspor** untuk rekonstruksi manual                 | **21 file** (di `docs/recovery/fragments/`)                                            |
| Status boot aplikasi setelah recovery                                             | **Laravel 11.51 boot OK**, `php artisan route:list` = **78 route**                     |
| Status admin panel Filament                                                       | **Belum bisa** (5 Resource + 4 RelationManager + 2 Observer + 3 Service ter-karantina) |
| Status public website                                                             | **Bisa jalan** (HomeController, PostController, EventController, dsb. utuh)            |
| Status tests                                                                      | **Tidak dapat dijalankan** sampai service & resource direkonstruksi                    |

**Estimasi effort ke 100%:** 2–3 hari kerja developer Laravel/Filament senior untuk rekonstruksi manual 22 file stub, diikuti 1 hari QA + CI hijau.

---

## 2. Arsitektur Sistem (Hasil Re-audit)

### 2.1 Stack

- **Bahasa:** PHP ^8.2
- **Framework:** Laravel 11.51 + Filament 3 (`filament/filament`, `filament/spatie-laravel-media-library-plugin`)
- **Auth/ACL:** `spatie/laravel-permission` (role: `superadmin`, `admin`, `operator`, `viewer`)
- **Media:** `spatie/laravel-medialibrary`
- **Jobs/Queue:** `laravel/horizon` (Redis di produksi, `QUEUE_CONNECTION=sync` default lokal)
- **Export:** `maatwebsite/excel`, `barryvdh/laravel-dompdf`
- **HTML sanitization:** `mews/purifier`
- **DB:** PostgreSQL 17
- **Frontend:** Blade + Tailwind (via Vite, bukan CDN)
- **Integrasi WhatsApp:** WAHA (WhatsApp HTTP API) — via `app/Services/Waha/*`, container ops di `ops/waha/`
- **AI/Artikel otomatis:** Provider abstraksi (`App\Contracts\ArticleAiProvider`), job `GenerateArticleJob`, pool scheduling ala "5× sehari"
- **Deployment:** VPS dengan Nginx vhost, Supervisor (Horizon queue), Cron (scheduler), skrip `scripts/provision.sh` + `scripts/deploy.sh` + `scripts/backup.sh`
- **CI:** GitHub Actions (pint dry-run + phpunit + vite build) — file: `.github/workflows/ci.yml` (**sudah dipulihkan**)

### 2.2 Domain Model (18 model Eloquent)

Agraria/advokasi:

- `AgrarianCase` (kode `KAS-`) — `AgrarianCaseParty`, `AgrarianCaseUpdate`, `AgrarianCaseFile`
- `AdvocacyProgram` (kode `ADV-`) — `AdvocacyAction`

Anggota:

- `Member` (kode `ANG-`) + `Address` + `MemberDocument`
- `User` (admin panel) + Spatie Permission tables

Agenda:

- `Event` + `EventAttendance` (catatan: `$table = 'event_attendance'` — BUG-6 historis)

Konten:

- `Post` + `Category` + `Tag` (pivots `post_category`, `post_tag`)
- `Page` (dinamis, dirender oleh `PageController`)
- `GalleryAlbum`, `GalleryItem`
- `SiteSetting` (key-value)

Artikel AI otomatis (fitur v2):

- `ArticleTopic`, `ArticlePool`, `ArticleGenerationLog`
- Post memiliki kolom `auto_*` untuk generasi otomatis (migrasi `2026_04_17_100003`)

Audit:

- `AuditLog`

### 2.3 Rute Publik (terverifikasi di `routes/web.php`)

```
/                              beranda
/halaman/{slug}                pages.show
/daftar-anggota                member-registration.create/store
/artikel, /artikel/{slug}      posts.index/show
/artikel/penulis/{id}          posts.author
/artikel/kategori/{slug}       posts.category
/artikel/tag/{slug}            posts.tag
/agenda                        events.index
/galeri, /galeri/{slug}        gallery.index/show
/kasus-agraria[, /{code}]      agrarian-cases.index/show
/program-advokasi[, /{code}]   advocacy-programs.index/show
/sitemap.xml, /feed.xml, /robots.txt  FeedController
/health                        HealthController
/admin/exports/members.pdf
/admin/exports/agrarian-cases.pdf
/admin/anggota/{member}/kartu-kta
```

### 2.4 Admin Panel Filament (target lengkap — saat ini sebagian rusak)

| Resource                       | Status File | Catatan                                                  |
| ------------------------------ | ----------- | -------------------------------------------------------- |
| `AdvocacyProgramResource`      | ⚠️ **Stub** | Fragment tersedia (9 edit)                               |
| `AgrarianCaseResource`         | ⚠️ **Stub** | Fragment (9 edit) + 2 Page stub + 2 RelationManager stub |
| `ArticleGenerationLogResource` | ✅ OK       | Utuh                                                     |
| `ArticlePoolResource`          | ⚠️ **Stub** | Fragment (11 edit); `EditArticlePool` page juga stub     |
| `ArticleTopicResource`         | ⚠️ **Stub** | Fragment (13 edit)                                       |
| `CategoryResource`             | ✅ OK       |                                                          |
| `EventResource`                | ⚠️ **Stub** | Fragment (7 edit)                                        |
| `GalleryAlbumResource`         | ✅ OK       |                                                          |
| `MemberResource`               | ⚠️ **Stub** | Fragment (17 edit)                                       |
| `PageResource`                 | ✅ OK       |                                                          |
| `PostResource`                 | ✅ OK       |                                                          |
| `SiteSettingResource`          | ✅ OK       |                                                          |
| `UserResource`                 | ✅ OK       |                                                          |

### 2.5 Kontribusi utama sesi sebelumnya (urutan kronologis dari transcript)

1. **AUTO_ARTICLE_SYSTEM** — generasi artikel otomatis via AI provider + pool schedule 5× sehari (Asia/Jakarta)
2. **WhatsApp notifications** — `WhatsAppMemberNotifier`, 3 Job (`NotifyMembersEventPublicWhatsAppJob`, `NotifyMembersPostPublishedWhatsAppJob`, `ManualWhatsAppBroadcastJob`), page `WhatsAppOperationsPage`
3. **Content profiles** (pillar vs member_practical) untuk AI article generation
4. **Advocacy/organization research docs** dan seeder timeline
5. **Public profile pages seeder** (`PublicProfilePagesSeeder`)
6. **Redesign landing** ("rev" components di `resources/views/components/rev/*`)
7. **Security hardening:** `SecurityHeaders` middleware, robots.txt baru, HSTS
8. **Ops:** script deploy/provision/backup, Nginx vhost, Supervisor, Cron
9. **PDF/Excel exports** (Members, AgrarianCases, Events, EventAttendances, Advocacy, AdvocacyActions)

---

## 3. Analisis Kehilangan File

### 3.1 Sumber "hilang"

Working copy `/home/sepetak.org/` tidak di-tracking oleh Git (tidak ada `.git`). Tidak ada backup tarball terkini. Satu-satunya jejak perubahan adalah **transcript agen** di `~/.cursor/projects/home-sepetak-org/agent-transcripts/c000dcc7-…/c000dcc7-….jsonl` — yang hanya mencatat **input tool** agen, bukan respon (`tool_result` = 0).

### 3.2 Klasifikasi 172 file hilang

```
Kategori A (137) — Fully Recoverable
  ├─ Event Write saja atau Write + StrReplace
  └─ Disk state dapat direplay 100%  ✅  (sudah dipulihkan)

Kategori B (14) — Partial Recovery (OK-ish)
  ├─ Hanya StrReplace pada file yang baseline-nya hilang
  └─ Namun fragment akhir kebetulan membentuk konten yang legal secara syntax

Kategori C (21) — Partial Recovery (Stub rusak)
  ├─ Hanya StrReplace; fragment akhir bukan file PHP valid
  └─ Dikarantina ke docs/recovery/stubs/
```

Kategori C (butuh rekonstruksi manual):

| File                                                 | Edit count | Komentar                                        |
| ---------------------------------------------------- | ---------: | ----------------------------------------------- |
| `app/Services/ArticleGeneratorService.php`           |         25 | **Paling kritis** — orkestrasi generate artikel |
| `database/seeders/DatabaseSeeder.php`                |         25 | Role/permission seeding + demo data             |
| `app/Filament/Resources/MemberResource.php`          |         17 | Form anggota full                               |
| `app/Filament/Resources/ArticleTopicResource.php`    |         13 | Topik otomatis                                  |
| `app/Filament/Resources/ArticlePoolResource.php`     |         11 | Pool jadwal                                     |
| `app/Filament/Resources/AdvocacyProgramResource.php` |          9 | Advocacy program                                |
| `app/Filament/Resources/AgrarianCaseResource.php`    |          9 | Kasus agraria                                   |
| `app/Filament/Resources/EventResource.php`           |          7 | Agenda                                          |
| `app/Observers/PostObserver.php`                     |          6 | HTML purify + slug                              |
| `app/Services/ResponseParser.php`                    |          5 | Parser output AI                                |
| `app/Services/TopicPicker.php`                       |          5 | Rotasi topik                                    |
| `app/Observers/PageObserver.php`                     |          3 | HTML purify page                                |
| `config/article-generator.php`                       |          3 | Config AI article                               |
| `.../Pages/EditArticlePool.php`                      |          2 | Page edit                                       |
| `config/purifier.php`                                |          2 | HTML Purifier config                            |
| `.../RelationManagers/PartiesRelationManager.php`    |          2 | Parties                                         |
| `.../Pages/CreateAgrarianCase.php`                   |          1 | Page create                                     |
| `.../Pages/EditAgrarianCase.php`                     |          1 | Page edit                                       |
| `.../RelationManagers/UpdatesRelationManager.php`    |          1 | Updates                                         |
| `.../RelationManagers/ActionsRelationManager.php`    |          3 | Actions                                         |
| `resources/views/welcome.blade.php`                  |          1 | Default welcome                                 |
| `tests/Feature/ExampleTest.php`                      |          1 | Default example                                 |

### 3.3 Tindakan recovery otomatis yang sudah dilakukan

1. `scripts/replay` membaca transcript dan memutar ulang event `Write`/`StrReplace` terhadap virtual-FS → tulis ulang 172 file yang hilang di disk.
2. Lint `php -l` atas seluruh pohon `app/`, `tests/`, `config/`, `database/` → menemukan 1 file parse error (`EditArticlePool.php`).
3. File stub yang tidak valid PHP dikarantina ke `docs/recovery/stubs/<relpath>` (tidak di-delete) supaya autoload & config loading tidak crash.
4. Seluruh fragmen `StrReplace` 21 file stub disimpan di `docs/recovery/fragments/<flatpath>.fragments.md` untuk referensi rekonstruksi.
5. Validasi: `php artisan --version` = `Laravel Framework 11.51.0`, `php artisan route:list` = 78 route.

---

## 4. Rekomendasi Perbaikan ke 100%

### 4.1 Prioritas Kritis (P0 — tanpa ini admin panel mati)

1. **Inisialisasi ulang Git** (`git init`, commit current state, push ke origin). Tanpa ini kita akan mengulang tragedi yang sama.
2. **Rekonstruksi 21 file stub** (lihat §5 untuk panduan per-file).
3. **Restore `config/purifier.php`** dari vendor default:
   ```bash
   php artisan vendor:publish --provider='Mews\Purifier\PurifierServiceProvider'
   ```
   Kemudian patch preset `filament_rich_html` sesuai fragment di `docs/recovery/fragments/config__purifier.php.fragments.md`.
4. **Restore `config/article-generator.php`** — lihat `docs/recovery/fragments/config__article-generator.php.fragments.md`. Struktur kunci yang dibutuhkan (dari fragmen):
   - `defaults.status`, `defaults.auto_publish`, `defaults.author_user_id`, `min_word_count`, `min_references`
   - `schedule_timezone` (`env('ARTICLE_SCHEDULE_TIMEZONE', 'Asia/Jakarta')`)
   - `content_profiles.pillar.*` dan `content_profiles.member_practical.*`
   - `member_practical_article_types`, `member_practical_category_slugs`
   - `member_practical_prompt.*`
   - `default_member_practical_schedule_times` (5 entri)

### 4.2 Prioritas Tinggi (P1)

1. **Regenerasi `DatabaseSeeder.php`** — penting untuk onboarding dev baru & CI:
   - Seed role & permission (`superadmin`, `admin`, `operator`, `viewer`) via Spatie
   - 3 user demo (`admin@sepetak.org`, `redaksi@…`, `publik@…`) — password `password`
   - `SiteSetting` default
   - Kategori + 1 post welcome
   - Panggil `PublicProfilePagesSeeder`, `AdArtPageContent`, `SejarahPageContent`, dst. (sudah tersedia)
2. **Regenerasi Observer** (`PostObserver`, `PageObserver`):
   - Hook `saving`: jika `isDirty('body')`, sanitasi via Purifier `filament_rich_html`.
   - `PostObserver` tambahan: slug via `App\Support\PostSlug`.
3. **Regenerasi Filament Resources** (6 file) — gunakan Filament generator lalu patch:
   ```bash
   php artisan make:filament-resource Member --generate
   php artisan make:filament-resource AgrarianCase --generate
   php artisan make:filament-resource AdvocacyProgram --generate
   php artisan make:filament-resource Event --generate
   php artisan make:filament-resource ArticleTopic --generate
   php artisan make:filament-resource ArticlePool --generate
   ```
   Lalu terapkan fragment edit dari `docs/recovery/fragments/*.fragments.md` untuk custom field & action.
4. **Regenerasi Service AI**:
   - `ArticleGeneratorService` — tanda tangan yang terlihat dari transcript: `generate(ArticlePool|null $pool, ArticleTopic $topic): Post|null`, gunakan `ArticleAiProvider` + `ArticleQualityValidator` + `ResponseParser` + `TopicPicker`.
   - `ResponseParser` — metode `hasAbstract`, `extractReferences`, `parseHeadings` (dari fragmen nama).
   - `TopicPicker` — rotasi topik memperhatikan `ArticleGenerationLog`.

### 4.3 Prioritas Menengah (P2)

1. **Jalankan full test suite** setelah P0/P1 selesai: `vendor/bin/phpunit`. Target: sesuai klaim transcript = **47 test hijau**.
2. **Pint dry-run**: `vendor/bin/pint --test` → pass.
3. **Re-setup CI**: `.github/workflows/ci.yml` sudah dipulihkan; cukup push ke origin agar terpicu.
4. **Backup otomatis**: `scripts/backup.sh` sudah dipulihkan; schedule di cron per hari.
5. **Re-seed WAHA**: `ops/waha/docker-compose.yml` sudah dipulihkan; `init-waha.sh` untuk pair session.

### 4.4 Prioritas Rendah (P3 — higienitas)

1. Hapus `.env.bak.local.20260416-173149` setelah verifikasi tidak ada nilai unik.
2. Tinjau `resources/views/welcome.blade.php` (stub) — setelah rekonstruksi, ini hanya dipakai kalau route `/` diarahkan ke view default Laravel (tidak aktif, `HomeController` yang menangani).
3. Hapus `tests/Feature/ExampleTest.php` stub — test ini tidak memberi sinyal.

---

## 5. Panduan Rekonstruksi Per-File Stub

Untuk tiap file di `docs/recovery/stubs/`, tersedia fragmen `StrReplace` di `docs/recovery/fragments/` dengan nama yang di-flatten (`/` → `__`). Workflow rekonstruksi yang direkomendasikan:

```
1. Buka fragment file.
2. Urut kronologis (Edit #1 → #N): setiap entri menunjukkan "sebelum" (old_string) dan "sesudah" (new_string) dari SATU diff agen.
3. Bentuk draft awal dengan menggabungkan semua new_string dalam konteks skeleton Filament/Laravel (artisan generator).
4. Verifikasi `php -l` lulus, kemudian `php artisan filament:optimize` tidak error.
5. Jalankan Livewire test terkait bila ada (mis. `MemberCreateLivewireTest`).
```

Tips per kategori:

- **Filament Resource**: mulai dari `php artisan make:filament-resource X --generate` lalu override `form()`, `table()`, `getPages()`, `getNavigationGroup()`, `canCreate()/canDelete()/canEdit()` via policy.
- **RelationManager**: mulai dari `php artisan make:filament-relation-manager Parent children field` lalu override `form()` dan `table()`.
- **Observer**: skeleton `class PostObserver { public function saving(Post $post): void { if ($post->isDirty('body') && filled($post->body)) { $post->body = Purifier::clean($post->body, 'filament_rich_html'); } } }`.
- **Service AI**: ikuti test `tests/Unit/ArticleGenerator/*` (sudah dipulihkan) sebagai spesifikasi tanda tangan metode.

---

## 6. Recommended Action Plan (Sequenced)

1. **Hari 1 AM** — Inisiasi Git, commit state saat ini. Lalu restore `config/article-generator.php`, `config/purifier.php`, `DatabaseSeeder.php`, dua Observer.
2. **Hari 1 PM** — Rekonstruksi 3 service AI (`ArticleGeneratorService`, `ResponseParser`, `TopicPicker`), jalankan `tests/Unit/ArticleGenerator/*`.
3. **Hari 2 AM** — Rekonstruksi 6 Filament Resource stub + 4 RelationManager/Page stub.
4. **Hari 2 PM** — Smoke test admin panel, jalankan full `phpunit` + `pint --test`.
5. **Hari 3** — Hapus stub folder (`docs/recovery/stubs/`) setelah semua direkonstruksi, push ke repo, deploy via `scripts/deploy.sh`.

---

## 7. Pencegahan Kejadian Ulangan (Must-Do)

1. **Git wajib** sejak hari pertama setiap proyek agen. Commit per milestone kecil.
2. **Auto-backup working copy** — cron `rsync /home/sepetak.org /var/backups/sepetak.org-$(date +%F)` harian, simpan 7 hari rolling.
3. **Jangan hanya mengandalkan `StrReplace`** pada file kritis yang belum di-version-control. Jika agen akan modifikasi >N file, lakukan `Write` snapshot dulu.
4. **Aktifkan `CI` dan `pre-commit`**: lint + phpunit pada setiap push, agar stub rusak segera terdeteksi.
5. **Simpan `.env` dan file konfigurasi sensitif** secara terpisah (Vault/1Password) — jangan bergantung pada disk.

---

## 8. Lampiran — Inventori Recovery

- `docs/recovery/fragments/` — 21 berkas markdown, isi fragment `StrReplace` per file stub
- `docs/recovery/stubs/` — 22 file PHP/Blade stub yang di-karantina (mirror struktur `/home/sepetak.org/`)
- Script utility replay: `/tmp/restore_sepetak.py` (disarankan di-salin ke `scripts/dev/restore-from-transcript.py` bila dibutuhkan lagi)
- Transcript sumber: `~/.cursor/projects/home-sepetak-org/agent-transcripts/c000dcc7-cca2-4012-83de-c3dfeb72961a/c000dcc7-cca2-4012-83de-c3dfeb72961a.jsonl`

---

## 9. Status Akhir Sesi Recovery (18 April 2026)

- ✅ Laravel 11 boot: `artisan --version` → `Laravel Framework 11.51.0`
- ✅ Route publik: 78 route terdaftar
- ✅ 137 file hilang dipulihkan penuh dari transcript
- ✅ 14 file parsial dengan konten valid (OK-ish) tetap di posisi semula
- ⚠️ 22 file stub dikarantina + fragmen diekspor untuk rekonstruksi manual (P0/P1 di atas)
- ⚠️ Admin panel Filament: 6 Resource + 4 page/relation manager + 2 observer + 3 service + 2 config **belum bisa digunakan** sampai rekonstruksi selesai
- ⚠️ Test suite **belum dijalankan** (menunggu rekonstruksi service AI + resource Filament)
- ⚠️ Git **belum diinisialisasi** di working copy — kerjakan **pertama** sebelum melanjutkan perubahan apa pun
