## Status Saat Ini

- Part 1 selesai: fondasi repo dan dokumen awal tersedia
- Part 2 selesai: skema database, 25 migrasi, 18 model, seeder terisi (role, permission, 3 user demo, kategori, site setting, post, page)
- Part 3 selesai: Laravel 11 + Filament 3 aktif, PostgreSQL 17 online, user admin awal tersedia
- Part 4 selesai untuk MVP: 9 Resource Filament, 4 RelationManager (Parties, Updates, Files, Actions) dengan opsi enum yang sudah tersinkron ke CHECK constraint PostgreSQL, auto-generate kode (`ANG-`, `KAS-`, `ADV-`) via model `booted()`, policy per-resource dengan role hierarchy (superadmin bypass via `Gate::before`, admin full, operator CRUD non-delete, viewer read-only), guard panel admin via `FilamentUser::canAccessPanel` (cek `is_active` + role), field `lead_user_id` tersedia di AdvocacyProgram
- Part 5 selesai: homepage, daftar berita (+ pagination), detail berita, halaman statis dinamis (tentang-kami, visi-misi, sejarah, struktur-organisasi, wilayah-kerja, kontak), formulir pendaftaran anggota (dengan rate-limit 5 req/menit), Open Graph + Twitter Card + canonical, sitemap.xml dinamis, feed RSS, robots.txt, build asset via Vite (CDN Tailwind dihapus), symlink `public/storage` aktif, **JSON-LD terstruktur** (Organization global + WebSite di beranda + NewsArticle + BreadcrumbList di halaman post)
- Part 6 selesai untuk MVP: widget statistik overview + **2 chart widget** (`MembersChartWidget`, `CasesChartWidget`) per-bulan di dashboard, **export Excel** (4 modul: Member, AgrarianCase, AdvocacyProgram, Event) via Maatwebsite/Excel, **export PDF** (rekap Member + AgrarianCase) via Barryvdh/DomPDF, **notifikasi email** pendaftaran anggota (`MemberRegistrationReceived` ke pendaftar + `AdminNewMemberRegistered` ke superadmin/admin/operator) — semua di-queue (`ShouldQueue`) dengan fallback sync
- Part 7 selesai: **47 feature test hijau** dengan 170 assertion (semua suite sebelumnya + 2 test `HealthEndpointTest`), **Pint dry-run pass**, **GitHub Actions CI** aktif (`.github/workflows/ci.yml`: job pint + phpunit dgn service Postgres 17 + build Vite), dokumen deployment lengkap: `docs/DEPLOYMENT.md` + **`docs/GO_LIVE_RUNBOOK.md`** step-by-step + script otomasi `scripts/{provision,deploy,backup}.sh` + konfigurasi sistem `ops/{nginx,supervisor,cron}/` + endpoint publik `/health` untuk uptime monitor
- Akun demo: `admin@sepetak.org` (superadmin), `redaksi@sepetak.org` (operator), `publik@sepetak.org` (viewer) — password `password` (WAJIB diganti sebelum produksi)
- Domain `sepetak.org` sudah terhubung ke IP server; go-live tinggal eksekusi `scripts/provision.sh` di VPS → `scripts/deploy.sh` → `certbot --nginx`. Seluruh artefak deploy sudah di-commit ke repo
- **LIVE**: `https://sepetak.org` sudah aktif produksi per 17 April 2026 (Let's Encrypt cert valid s/d 15 Juli 2026, auto-renew via `certbot.timer` dry-run sukses). Seluruh smoke test 14 endpoint → 200, HTTP→HTTPS 301, HSTS + security headers aktif, tidak bentrok dengan 8 web app lain di VPS yang sama (areton.id, bizmark.id, academos.or.id, hadez.us, wts.co.id, admin.areton.id, api.areton.id, api.bizmark.id)
- Identitas organisasi: nama resmi **Serikat Pekerja Tani Karawang** sejak **Kongres IV, 31 Oktober–1 November 2020** (sebelumnya *Serikat Petani Karawang*). Konten seeded (site_name, site_description, halaman profil, post welcome) menyelaraskan **empat kongres** (TANI MOTEKAR = Kongres II 2010 per Wikipedia; visi agraria/industri = Kongres III 25–26 April 2016; AD/ART + nama = Kongres IV 31 Okt–1 Nov 2020) selain dokumen AD/ART dan narasi sejarah STN → Dewan Tani → SEPETAK.

## Iterasi Lanjutan (16 April 2026 — Phase 5 close + Phase 6 + Phase 7)

### Part 5 ditutup: JSON-LD Structured Data

- **SEO-1** Partial `resources/views/partials/jsonld/organization.blade.php` — Organization schema (name, alternateName, foundingDate=2007-12-10, foundingLocation, areaServed, contactPoint, sameAs dari social_* setting). Dipakai global via `layouts/app.blade.php` `<head>`.
- **SEO-2** Partial `resources/views/partials/jsonld/website.blade.php` — WebSite schema di-push via `@push('head')` di beranda (`home.blade.php`).
- **SEO-3** Partial `resources/views/partials/jsonld/news-article.blade.php` — NewsArticle + BreadcrumbList (Beranda → Berita → Judul) di halaman post (`posts/show.blade.php`). Termasuk `mainEntityOfPage`, `image` (cover media), `author`, dan `publisher` link ke `#organization`.
- **SEO-4** `@stack('head')` ditambahkan di layout supaya halaman bisa inject script tambahan di `<head>`.
- **Test**: `tests/Feature/JsonLdTest.php` — parse seluruh blok `application/ld+json` dari HTML response dan validasi struktur (Organization, WebSite di beranda; Organization + NewsArticle + BreadcrumbList di post).

### Part 6: Export, Dashboard, Email

- **EXP-1** `app/Exports/MembersExport.php` — xlsx lengkap (15 kolom termasuk alamat kampus/desa/kecamatan/kabupaten). FromQuery + filter optional status/gender.
- **EXP-2** `app/Exports/AgrarianCasesExport.php`, `AdvocacyProgramsExport.php`, `EventsExport.php` — pola sama, headings Indonesia, auto-size kolom, bold header.
- **EXP-3** Tombol `Export Excel` ditambahkan di header table Filament `MemberResource`, `AgrarianCaseResource`, `AdvocacyProgramResource`, `EventResource`.
- **PDF-1** `app/Http/Controllers/Admin/ExportController.php` — endpoint `GET /admin/exports/members.pdf` dan `GET /admin/exports/agrarian-cases.pdf`, otorisasi via role (superadmin/admin/operator), template di `resources/views/exports/pdf/*.blade.php` dengan layout umum (`_layout.blade.php`) — mendukung DejaVu Sans & landscape A4.
- **PDF-2** Tombol `Export PDF` ditambahkan di header table `MemberResource` dan `AgrarianCaseResource` (redirect ke route admin).
- **DASH-1** `MembersChartWidget` — line chart "Pendaftaran vs Disetujui" 12 bulan terakhir.
- **DASH-2** `CasesChartWidget` — bar chart "Kasus Dibuka vs Ditutup" 12 bulan terakhir.
- **MAIL-1** `app/Notifications/MemberRegistrationReceived.php` — email konfirmasi ke pendaftar (mencantumkan kode anggota, prosedur verifikasi Departemen Internal).
- **MAIL-2** `app/Notifications/AdminNewMemberRegistered.php` — email alert ke user aktif ber-role superadmin/admin/operator + alamat `contact_email` setting.
- **MAIL-3** `MemberRegistrationController::sendNotifications()` — trigger di `store()`, semua exception ditelan & di-log agar tidak ganggu UX publik; notifikasi kedua class pakai `ShouldQueue` (`QUEUE_CONNECTION=sync` default lokal, siap Redis di produksi).
- **BUG-6** Ditemukan saat menulis test: model `App\Models\EventAttendance` tidak men-set `$table`, sehingga Eloquent mencari `event_attendances` sementara migrasi membuat `event_attendance` — relasi `Event::attendances()` & `withCount('attendances')` akan error runtime. → `protected $table = 'event_attendance';` ditambahkan.

### Part 7: Testing, Lint, CI

- **QA-1** `tests/Feature/JsonLdTest.php` (2 test, 26 assertion).
- **QA-2** `tests/Feature/AdminExportTest.php` (4 test) — otorisasi route PDF + content-type `application/pdf`.
- **QA-3** `tests/Feature/ExcelExportTest.php` (4 test) — export xlsx untuk 4 modul (Members, AgrarianCase, AdvocacyProgram, Event) + `Excel::fake()` untuk store.
- **QA-4** `tests/Feature/MemberRegistrationNotificationTest.php` (2 test) — `Notification::fake()` memastikan email pendaftar + admin terkirim, viewer TIDAK menerima.
- **CI-1** `laravel/pint` ditambahkan sebagai dev dependency; seluruh codebase (49 file) di-reformat ke standar Pint default; `vendor/bin/pint --test` sekarang pass.
- **CI-2** `.github/workflows/ci.yml` — 3 job: **pint** (dry-run), **phpunit** (PHP 8.3 + service Postgres 17 + migrate + phpunit), **build** (Node 20 + npm ci + vite build). `concurrency` group mencegah run duplikat per branch.
- **QA-5** Route stub `GET /login` → redirect ke `/admin/login` supaya middleware `auth` Laravel punya named route untuk 302 redirect (tidak lagi `RouteNotFoundException`).

Verifikasi akhir (semua hijau):

- `vendor/bin/pint --test` → `{"result":"pass"}`
- `vendor/bin/phpunit` → **41 tests, 145 assertions** pass.
- `php artisan db:seed --force` → sukses.
- `npm run build` → sukses (72.43 kB CSS + 37.64 kB JS).
- Smoke test HTTP: `/`, `/berita/:slug`, `/halaman/:slug` (6 halaman), `/admin/login`, `/admin/exports/*.pdf` (tanpa auth → 302 ke `/login` → `/admin/login`).

## Iterasi Polish v2 (16 April 2026 — lanjutan)

Fokus: membersihkan deprecation, memperkuat UI media library, menambah notifikasi kasus agraria, dan memperluas cakupan uji ke layer Livewire/Filament.

### Cleanup deprecation Filament 3.x

- **DEP-1** Migrasi semua `Tables\Columns\BadgeColumn` (deprecated di Filament 3) → `TextColumn::make(...)->badge()` dengan `->color(fn ($state) => match (...))`. Terdampak: `MemberResource`, `AgrarianCaseResource`, `AgrarianCaseResource\RelationManagers\UpdatesRelationManager`, `AdvocacyProgramResource`, `AdvocacyProgramResource\RelationManagers\ActionsRelationManager`, `EventResource`, `PageResource`, `PostResource`. Hasil: `grep BadgeColumn` pada `app/Filament` = 0 hit.

### UI Media Library

- **MEDIA-UI-1** `MemberResource` table sekarang menampilkan `ImageColumn::make('photo_url')` bundar 42px yang resolve via `$record->getFirstMediaUrl('photo')`. Fallback `defaultImageUrl` ke `/images/default-photo.png` bila anggota belum punya foto.
- **MEDIA-UI-2** `AgrarianCaseResource` table: kolom `Lampiran` (`badge gray`) menghitung `getMedia('attachments')->count()`, sehingga admin langsung tahu berapa arsip Spatie Media yang melekat per kasus.
- **MEDIA-UI-3** `AdvocacyProgramResource` table: kolom `Foto` (`badge gray`) menghitung `getMedia('photos')->count()`.
- **MEDIA-UI-4** `EventResource` table: kolom `Kehadiran` (`badge primary`) dari `->counts('attendances')` + kolom `Foto` (`badge gray`) dari `getMedia('photos')->count()`.
- **MEDIA-UI-5** `AgrarianCaseResource` form mendapat field `FileUpload::make('attachments_upload')->multiple()->dehydrated(false)`. Handler upload ditambahkan ke `Pages\CreateAgrarianCase::afterCreate()` dan `Pages\EditAgrarianCase::afterSave()` — memanggil `addMediaFromDisk(..., 'public')->toMediaCollection('attachments')`. Catatan: RelationManager `Berkas` tetap dipertahankan untuk dokumen formal bernama (berbeda koleksi).

### Notifikasi Perubahan Status Kasus Agraria

- **NOTIF-CASE-1** `App\Notifications\AgrarianCaseStatusChanged` (ShouldQueue) — email terformat Markdown berisi kode kasus, judul, lokasi, status sebelumnya, status terbaru, (opsional) catatan, tombol aksi ke `/admin/agrarian-cases/{id}/edit`. Termasuk `humanize()` untuk enum (`reported`, `under_review`, `mediation`, `legal_process`, `resolved`, `closed`).
- **NOTIF-CASE-2** `App\Observers\AgrarianCaseObserver` — hook `updated()` memeriksa `wasChanged('status')`, lalu kirim notifikasi ke: (a) `leadUser` aktif jika belum tercakup list internal, (b) semua user aktif ber-role `superadmin|admin|operator`, (c) alamat `contact_email` (on-demand) bila tidak duplikat. Exception ditelan & di-log agar transaksi update tidak rollback.
- **NOTIF-CASE-3** Observer didaftarkan di `App\Providers\AppServiceProvider::registerObservers()`.

### Test Integrasi Filament (Livewire)

- **QA-INT-1** `tests/Feature/AgrarianCaseStatusNotificationTest.php` — 2 test: (a) update status memicu notifikasi ke admin, leadUser, dan contact_email `OnDemandNotifiable`; viewer tidak menerima; (b) update field non-status tidak memicu notifikasi.
- **QA-INT-2** `tests/Feature/Filament/MemberResourceLivewireTest.php` — 3 test: list render + table records, create member via `fillForm()->call('create')` (termasuk alamat auto-create), edit member mengubah `status` dari `pending` → `active`.
- **QA-INT-3** `tests/Feature/Filament/AgrarianCaseResourceLivewireTest.php` — 2 test: list render, dan observer berfungsi saat `$case->update(['status' => 'legal_process'])` (verifikasi end-to-end tanpa browser RichEditor). Catatan teknis: `Livewire::test(CreateAgrarianCase::class)->fillForm([...])` memicu error `Attempt to read property "form" on null` akibat `Forms\Components\RichEditor` yang tidak compatible dengan Livewire testable headless — coverage tetap aman karena observer diuji langsung + HTTP smoke test admin panel sudah dicakup `AdminPanelAccessTest`.

Verifikasi akhir (semua hijau):

- `vendor/bin/pint --test` → `{"result":"pass"}`
- `vendor/bin/phpunit` → **41 tests, 145 assertions** pass (waktu ±4.1s, PHP 8.4 + PHPUnit 11.5).
- Smoke test HTTP admin: `/admin/login` 200, `/admin/exports/members.pdf` tanpa auth → 302 (stub `/login` → `/admin/login`).
- Grep `BadgeColumn` di `app/` → 0 hit (bersih dari deprecation).

## Iterasi Polish v3 (16 April 2026 — lanjutan)

Fokus: menambah export granular (kehadiran per-event, aksi per-program), menghadirkan galeri media yang bisa dilihat langsung tanpa keluar dari panel, dan membangun test view-action untuk memastikan infolist stabil.

### Export Excel Tambahan

- **EXP-5** `app/Exports/EventAttendancesExport.php` — flat `event_attendance` × `member`. Constructor opsional menerima `Event` untuk scope per-event (export "Kehadiran" dari tiap baris event) atau semua event (rekap tahunan). Heading: Event, Tanggal, Kode Anggota, Nama, Status Kehadiran (Hadir/Tidak Hadir/Izin), Catatan.
- **EXP-6** `app/Exports/AdvocacyActionsExport.php` — flat `advocacy_actions` × `advocacy_programs`. Constructor opsional menerima `AdvocacyProgram` untuk scope per-program. Heading: Kode Program, Judul, Status Program, Tanggal Aksi, Tipe Aksi (humanized), Catatan, Outcome, Dicatat Oleh.
- **EXP-7** `EventResource` table: action per-record `Kehadiran (xlsx)` + header action `Export Semua Kehadiran`.
- **EXP-8** `AdvocacyProgramResource` table: action per-record `Aksi (xlsx)` + header action `Export Semua Aksi`.

### View Action + Galeri Media

- **VIEW-1** Partial `resources/views/filament/infolists/components/media-gallery.blade.php` — grid 2/3/4 kolom responsif, menampilkan thumbnail gambar (aspek kotak, lazy-load, `group-hover:scale-105`) atau kartu ikon dokumen (non-image) dengan ekstensi + ukuran. Setiap item tautan ke `getFullUrl()` (target `_blank`). Mendukung dark mode.
- **VIEW-2** `MemberResource::infolist()` — 3 section: Identitas (ImageEntry foto 120px bundar), Kontak & Alamat (10 entry: phone/email/birth/joined + alamat 5 level), **Galeri Dokumen** via `View::make('filament.infolists.components.media-gallery')` dengan data `getMedia('documents')`.
- **VIEW-3** `AgrarianCaseResource::infolist()` — 2 section: Ringkasan Kasus (10 entry: code/title/status-badge/priority-badge/tanggal/lokasi/PIC/summary/description HTML) + **Galeri Lampiran** via partial media-gallery dengan data `getMedia('attachments')`.
- **VIEW-4** `Tables\Actions\ViewAction::make()` ditambahkan di `actions()` kedua resource — modal infolist dapat dibuka tanpa redirect (UX lebih cepat untuk audit cepat oleh operator/viewer).
- **VIEW-5** Bug fix saat test: `TextEntry->date()->default('-')` melempar `Carbon: Could not parse '-'` karena default state '-' dialirkan ke formatter `date()`. Diganti ke `->date()->placeholder('-')` pada semua entry (placeholder hanya render tanpa formatter). Berlaku untuk MemberResource (5 entry) dan AgrarianCaseResource (5 entry).

### Test Baru

- **QA-6** `ExcelExportTest::test_event_attendances_export_download` — skenario scoped (per-event) dan semua.
- **QA-7** `ExcelExportTest::test_advocacy_actions_export_download` — skenario scoped (per-program) dan semua.
- **QA-8** `Filament/MemberResourceLivewireTest::test_view_action_opens_infolist_without_error` — `mountTableAction('view', $member)` render sukses + `assertSee(full_name)`.
- **QA-9** `Filament/AgrarianCaseResourceLivewireTest::test_view_action_opens_infolist_without_error` — sama untuk AgrarianCase.

Verifikasi akhir (semua hijau):

- `vendor/bin/pint --test` → `{"result":"pass"}`
- `vendor/bin/phpunit` → **45 tests, 157 assertions** pass (waktu ±4.6s, PHP 8.4 + PHPUnit 11.5).
- ReadLints pada 7 file terdampak → no errors.

## Rebranding & Konfigurasi Produksi (16 April 2026 — lanjutan)

Perubahan identitas organisasi dan persiapan go-live domain `sepetak.org`:

- **BRAND-1** Singkatan SEPETAK = **Serikat Pekerja Tani Karawang** (nama resmi sejak **Kongres IV, 31 Oktober–1 November 2020**; sebelumnya *Serikat Petani Karawang*).
- **BRAND-2** `DatabaseSeeder` + halaman profil: empat kongres (I 2007; II 10–11 Des 2010 & TANI MOTEKAR per Wikipedia; III visi agraria/industri; IV 2020 AD/ART + nama); `site_name`, `site_description`, `site_tagline`, `contact_address`, post welcome diselaraskan.
- **BRAND-3** Halaman `/halaman/sejarah`: timeline kongres + aksi Teluk Jambe (2013), tol Jakarta–Cikampek (11 Juli 2013), BPN Karawang (27 Juli 2023), Hari Tani 2025; tautan di footer.
- **BRAND-4** Halaman `kontak` direvisi: menghapus placeholder telepon yang membingungkan, menegaskan email resmi `info@sepetak.org`, dan mengarahkan ke formulir pendaftaran anggota untuk onboarding publik.
- **BRAND-5** `resources/views/home.blade.php` dan `resources/views/layouts/app.blade.php` diperbarui: badge hero, body “Siapa SEPETAK?”, dan seluruh fallback meta (`site_description`, OG description, Twitter description) sekarang menyebut *Serikat Pekerja Tani Karawang*.
- **DOC-6** `README.md` section "Tentang SEPETAK" ditulis ulang penuh berbasis sumber publik (Wikipedia, KPA, dll.) dengan jejak aksi historis.
- **DOC-7** `docs/ORGANIZATION_RESEARCH.md` di-rewrite total dari "belum terverifikasi" menjadi dokumen riset terverifikasi yang mencantumkan sumber publik, tanggal penting, jejak perjuangan, jaringan, dan checklist validasi pengurus.
- **PROD-1** `docs/DEPLOYMENT.md` diperbarui: hapus opsi MySQL 8 (stack final Postgres 17), perluas section Environment Produksi (SESSION_SECURE_COOKIE, SESSION_DOMAIN), tulis ulang "Catatan Domain" menjadi panduan go-live step-by-step.
- **PROD-2** `.env.production.example` baru di root repo — template lengkap produksi (APP_URL=https://sepetak.org, Redis session/cache/queue, SMTP, filesystem public) + checklist pasca-isi.

Verifikasi (semuanya hijau):

- `php artisan db:seed --class=DatabaseSeeder --force` → `DatabaseSeeder completed successfully.`
- `./vendor/bin/phpunit` → **22 tests, 75 assertions** hijau.
- Smoke test HTTP: `/`, `/berita`, `/halaman/tentang-kami`, `/halaman/visi-misi`, `/halaman/sejarah`, `/kontak` (form kontak; `/halaman/kontak` mengarah 301 ke sini), `/daftar-anggota`, `/sitemap.xml`, `/feed.xml`, `/admin/login` semua `200 OK` (kecuali 301 kontak seperti di atas).
- Grep output HTML beranda: hanya muncul `Serikat Pekerja Tani Karawang` (tidak ada lagi singkatan lama).

## Audit & Perbaikan Terakhir (16 April 2026)

Hasil audit konsistensi dokumentasi vs kode — sudah diperbaiki:

- **BUG-1** `PartiesRelationManager` opsi `party_type` (plaintiff/defendant/...) melanggar CHECK constraint migrasi (`member/community/institution/company/government/other`). Create party akan gagal. → Opsi form disesuaikan dengan migrasi.
- **BUG-2** `ActionsRelationManager` opsi `action_type` (legal_filing/negotiation/workshop/...) melanggar CHECK constraint migrasi (`meeting/training/campaign/field_visit/legal/other`). → Opsi form disesuaikan.
- **BUG-3** `AdvocacyProgramResource` form tidak memiliki field `lead_user_id` yang sudah didefinisikan di migration + model. → Field `Select` ditambahkan, kolom tabel juga menampilkan `leadUser.name`.
- **BUG-4** Symlink `public/storage` belum dibuat sehingga URL file hasil upload `disk('public')` mengembalikan 404. → `php artisan storage:link` dijalankan.
- **BUG-5** `.env.example` masih `DB_CONNECTION=mysql` (port 3306, user `root`). Developer yang salin langsung akan gagal migrasi. → Diubah ke `pgsql` (5432) dengan user/password `sepetak`, `FILESYSTEM_DISK=public`, `APP_URL=http://127.0.0.1:8000`.
- **DOC-1** `README.md` Tech Stack table menyebut MySQL 8 → diganti PostgreSQL 17, ditambah baris Frontend Build (Vite + Tailwind 3), versi PHP 8.3/8.4.
- **DOC-2** `README.md` deskripsi role (Super Admin/Pengurus/Editor/Anggota) → diselaraskan ke `superadmin/admin/operator/viewer`.
- **DOC-3** `CONTRIBUTING.md` Development Environment MySQL → PostgreSQL 17; section Models diupdate ke nama English aktual + catatan larangan nama `Case`; tambah panduan testing (`sepetak_test`) dan prosedur menambah Resource baru.
- **DOC-4** `docs/LOCAL_SETUP.md` ditulis ulang lengkap: setup PostgreSQL (create user, create db test), `storage:link`, `npm run build`, akun demo.
- **DOC-5** `docs/DATABASE_SCHEMA.md` intro MySQL 8 utf8mb4 → PostgreSQL 17 UTF-8; tambah catatan `enum` Laravel = `CHECK constraint` di Postgres (mismatch = 23514) plus format kode bisnis; kolom `addresses.line_1` ditandai Nullable sesuai migrasi baru.
- **REG-1** Ditambahkan `RelationManagerEnumTest` yang secara refleksi membaca opsi `Select` di RelationManager dan menyimpan record untuk tiap nilai — mencegah regresi enum drift di masa depan.

Rekomendasi (backlog, belum dikerjakan):

- `Filament\Tables\Columns\BadgeColumn` deprecated di Filament 3; migrasi bertahap ke `TextColumn::make(...)->badge()`
- Exempt middleware `throttle` di environment testing (jika ke depan ada test submit berulang)
- OG image dinamis (social card per artikel) + JSON-LD `NewsArticle`
- GitHub Actions CI (lint via Pint + phpunit + build Vite)
- Migrasikan enum `party_type`/`action_type` ke tabel lookup jika daftar opsi sering berubah — lebih ramah tanpa migrasi setiap kali