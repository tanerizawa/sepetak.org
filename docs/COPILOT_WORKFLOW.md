# Workflow VS Code + Copilot

## Tujuan

Workflow ini dirancang agar proyek dapat dikerjakan hampir seluruhnya melalui VS
Code dan Copilot secara bertahap, dengan konteks kecil per sesi.

## Prinsip Kerja

- kerjakan satu part kecil per sesi
- mulai dari dokumen, lalu model data, lalu implementasi
- jangan meminta Copilot mengerjakan terlalu banyak file sekaligus
- selalu review hasil sebelum lanjut ke part berikutnya
- selalu rujuk dokumen sumber seperti DATABASE_SCHEMA, MVP_SCOPE, dan LARAVEL_BOOTSTRAP saat meminta implementasi

## Format Request yang Disarankan

Gunakan pola permintaan seperti ini:

1. jelaskan konteks file atau modul
2. sebutkan target hasil
3. batasi ruang lingkup
4. minta verifikasi setelah perubahan

Contoh:

- buat migration tabel members sesuai docs/DATABASE_SCHEMA.md
- buat Filament Resource untuk model Member dengan form dan table dasar
- buat homepage Blade sederhana sesuai MVP_SCOPE

## Urutan Pengerjaan Efektif

1. docs dan keputusan teknis
2. migration dan model inti
3. resource admin inti
4. route dan halaman publik
5. export, laporan, dan deployment

## Tips Menjaga Kuota dan Konteks

- buka hanya file yang sedang dikerjakan
- minta perubahan incremental
- pecah fitur besar menjadi subfitur 1 file sampai 3 file
- gunakan tag git per part agar hasil bisa diunduh bertahap
- minta Copilot memeriksa konflik nama model, relasi, dan dependency package sebelum generate CRUD

## Definisi Selesai per Task

Sebuah task dianggap selesai jika:

- file terkait sudah dibuat atau diperbarui
- tidak ada error sintaks dasar
- dokumen relevan ikut diperbarui bila perlu
- hasil bisa dilanjutkan ke task berikutnya tanpa asumsi tersembunyi

## Guardrail Implementasi

- untuk modul kasus, gunakan nama kode `AgrarianCase`, bukan `Case`
- untuk file upload, pakai media collection dan jangan buru-buru membuat foreign key ke tabel `media`
- jika repo belum berisi aplikasi Laravel, jangan menjalankan perintah artisan seolah app sudah ada
