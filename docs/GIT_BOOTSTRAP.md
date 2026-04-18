# Git Bootstrap dan Strategi Rilis Bertahap

## Tujuan

Dokumen ini menyiapkan alur kerja git agar hasil pekerjaan bisa dipisah per part,
mudah diunduh, dan tidak menghabiskan kuota kerja sekaligus.

## Branch yang Disarankan

- main untuk kondisi stabil terakhir
- develop untuk integrasi kerja aktif
- part/01-foundation
- part/02-data-model
- part/03-laravel-filament
- part/04-admin-core
- part/05-public-mvp
- part/06-advanced-features
- part/07-deployment-hardening

## Aturan Praktis

- satu part selesai lalu merge ke develop
- tag setiap part yang sudah usable
- hindari fitur campur aduk dalam satu branch
- simpan perubahan dokumentasi bersama kode pada part yang sama
- untuk proyek solo, branch `develop` boleh diabaikan dan cukup gunakan `main` + `part/*`

## Tag Release yang Disarankan

- v0.1.0-foundation
- v0.2.0-data-model
- v0.3.0-laravel-base
- v0.4.0-admin-core
- v0.5.0-public-mvp
- v0.6.0-advanced
- v1.0.0-production-ready

## Strategi Download Bertahap

Pilihan aman:

- pengguna checkout tag tertentu lalu mengunduh zip repo
- atau buat release GitHub per part dengan lampiran artefak

## Isi Minimum per Part

- part 1: repo docs dan standar kerja
- part 2: schema dan seed data
- part 3: app Laravel dasar
- part 4: resource admin inti
- part 5: website publik MVP
- part 6: export, dashboard, media
- part 7: deployment, testing, hardening

## Commit Style

Gunakan commit kecil dan jelas:

- docs: tambah schema database awal
- chore: tambah editorconfig dan git attributes
- feat: tambah resource anggota di admin panel
- feat: tambah halaman artikel publik

## Release Checklist

- branch bersih
- dokumen part terbarui
- tidak ada credential di repo
- tag dibuat setelah review singkat
- hasil build atau artefak yang ingin diunduh diberi nama jelas sesuai part
