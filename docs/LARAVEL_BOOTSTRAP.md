# Bootstrap Laravel + Filament untuk Part 3

## Tujuan

Dokumen ini berisi urutan implementasi saat repo siap masuk ke tahap kode nyata.
Gunakan dokumen ini sebagai checklist eksekusi Copilot pada sesi berikutnya.

## Status Eksekusi Saat Ini

- Laravel 11 sudah berhasil dimasukkan ke root repo
- Filament panel sudah berhasil terpasang
- Dependency frontend sudah terpasang dan build berhasil
- Route admin login aktif di `/admin/login`
- Tahap yang belum selesai hanyalah koneksi database, migration, dan pembuatan admin user pertama

Penting: repo ini sudah berisi dokumentasi dan file konfigurasi dasar. Karena itu,
**jangan** langsung menjalankan `composer create-project laravel/laravel .` di root repo,
karena perintah itu akan gagal pada direktori non-kosong.

## Langkah 1 - Inisialisasi Laravel

Pendekatan aman:

```bash
composer create-project laravel/laravel /tmp/sepetak-laravel "^11.0"
```

Lalu salin scaffold Laravel ke root repo secara selektif sambil mempertahankan folder `docs/`, `.vscode/`, dan file repo yang sudah ada.

Setelah scaffold masuk ke repo:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

## Langkah 2 - Database Dasar

- buat database MySQL
- isi kredensial pada .env
- jalankan koneksi awal

Perintah:

```bash
php artisan migrate
```

## Langkah 3 - Install FilamentPHP 3

Perintah umum:

```bash
composer require filament/filament:"^3.0" -W
php artisan filament:install --panels
```

Jika pada saat implementasi ada kendala kompatibilitas minor package, lakukan verifikasi versi stabil terbaru yang masih sesuai dengan keputusan arsitektur repo ini sebelum melanjutkan.

## Langkah 4 - Paket Pendukung

```bash
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
composer require laravel/horizon
```

## Langkah 5 - Prioritas Model Awal

Buat model dan migration inti terlebih dahulu:

- Member
- Address
- AgrarianCase
- AgrarianCaseUpdate
- AgrarianCaseParty
- AgrarianCaseFile
- AdvocacyProgram
- AdvocacyAction
- Event
- Post
- Page
- SiteSetting

## Langkah 6 - Prioritas Resource Filament

Urutan resource admin yang disarankan:

1. MemberResource
2. AgrarianCaseResource
3. AdvocacyProgramResource
4. PostResource
5. PageResource
6. EventResource

## Langkah 7 - Auth dan Authorization

- buat super admin awal
- konfigurasi role dan permission
- pastikan data anggota dan kasus tidak terbuka ke role umum

## Definition of Done untuk Part 3

- Laravel 11 berjalan lokal
- Filament panel bisa dibuka
- login admin berfungsi
- minimal 1 resource admin berhasil ditampilkan
- struktur repo dokumentasi lama tetap aman setelah scaffold Laravel dimasukkan
