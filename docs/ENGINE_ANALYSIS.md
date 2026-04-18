# Analisis Engine dan Ekosistem

## Ringkasan Eksekutif

Untuk kebutuhan SEPETAK, kombinasi Laravel 11 + FilamentPHP 3 adalah pilihan
terbaik karena paling seimbang antara kecepatan pengembangan, kekuatan admin
panel, fleksibilitas model data, dan kemudahan maintenance di VPS.

## Kebutuhan Sistem

Sistem harus menangani:

- website publik yang ringan dan mudah diubah
- admin panel internal untuk pengurus
- manajemen anggota
- manajemen kasus agraria dan advokasi
- upload dokumen dan foto
- export laporan PDF dan Excel
- pembagian hak akses pengguna

## Opsi yang Dibandingkan

### 1. Laravel 11 + FilamentPHP 3

Kelebihan:

- CRUD admin sangat cepat dibuat
- Struktur model, migration, policy, queue matang
- Ekosistem lokal Indonesia kuat
- Sangat cocok untuk VPS PHP standar
- Mudah dikembangkan bertahap dengan Copilot

Kekurangan:

- Perlu disiplin struktur model dan database
- Fitur realtime berat butuh tambahan konfigurasi

Cocok untuk:

- organisasi dengan banyak form admin dan data relasional

### 2. Django + Wagtail

Kelebihan:

- kuat untuk CMS dan content modeling
- admin bawaan stabil
- Python enak untuk data processing

Kekurangan:

- komunitas implementasi lokal lebih kecil untuk tim umum
- stack deployment sering terasa lebih asing untuk tim PHP
- custom workflow anggota dan kasus tetap butuh banyak coding

Cocok untuk:

- tim Python yang sudah matang

### 3. Node.js + NestJS + AdminJS/Strapi

Kelebihan:

- fleksibel untuk API dan integrasi modern
- cocok jika ke depan ingin banyak aplikasi klien

Kekurangan:

- admin panel tidak secepat Filament untuk data organisasi kompleks
- lebih banyak keputusan arsitektur sejak awal
- maintenance cenderung lebih berat untuk tim kecil

Cocok untuk:

- tim backend JavaScript yang sudah kuat

### 4. WordPress + Plugin

Kelebihan:

- cepat untuk website publik sederhana
- banyak tema dan plugin

Kekurangan:

- manajemen anggota, kasus, dan advokasi akan cepat menjadi tambal sulam
- struktur data kompleks lebih sulit dijaga
- kontrol akses granular biasanya kurang rapi

Cocok untuk:

- portal konten sederhana tanpa proses internal kompleks

## Matriks Penilaian

| Kriteria                           | Laravel + Filament | Django + Wagtail | NestJS + AdminJS | WordPress |
| ---------------------------------- | ------------------ | ---------------- | ---------------- | --------- |
| Kecepatan membuat admin            | 5/5                | 3/5              | 3/5              | 4/5       |
| Fleksibilitas data relasional      | 5/5                | 4/5              | 5/5              | 2/5       |
| Kemudahan VPS                      | 5/5                | 4/5              | 4/5              | 5/5       |
| Cocok untuk tim kecil              | 5/5                | 3/5              | 3/5              | 4/5       |
| Dukungan ekosistem lokal           | 5/5                | 3/5              | 4/5              | 5/5       |
| Cocok untuk Copilot-first workflow | 5/5                | 4/5              | 4/5              | 3/5       |

## Rekomendasi Final

Pilih Laravel 11 + FilamentPHP 3.

Alasan final:

- paling cepat menghasilkan admin panel yang benar-benar usable
- paling mudah dipecah menjadi part bertahap
- paling kuat untuk kombinasi konten publik dan data internal organisasi
- biaya operasional dan kompleksitas teknis tetap masuk akal di VPS

## Paket Pendukung yang Disarankan

- Livewire 3
- Spatie Media Library
- Spatie Permission
- Laravel Excel
- Barryvdh/DomPDF
- Laravel Horizon
- Redis

## Risiko Implementasi yang Harus Dihindari

- Jangan gunakan nama model `Case` di PHP/Laravel karena bertabrakan dengan keyword bahasa. Gunakan `AgrarianCase` atau nama domain lain yang aman.
- Jangan mendesain upload file seolah-olah Spatie Media Library bekerja seperti tabel file biasa yang dihubungkan manual ke semua entitas. Untuk MVP, lebih aman menempelkan media langsung ke model domain melalui media collection.
- Jangan mulai dari UI dulu. Tanpa model, status enum, policy, dan relasi yang rapi, admin panel Filament akan cepat rapuh.

## Keputusan Implementasi Praktis

- Modul kasus di level kode memakai istilah `AgrarianCase`, tetapi label UI tetap bisa ditampilkan sebagai `Kasus`.
- Media utama menggunakan Spatie Media Library dengan collection per model, misalnya `cover`, `documents`, dan `attachments`.
- Queue produksi memakai Redis dan Horizon; hindari menjalankan `queue:work` dan `horizon` bersamaan untuk worker yang sama.

## Batas MVP yang Disarankan

MVP jangan langsung membangun semua fitur. Fokus pada:

- website profil publik
- artikel dan halaman statis
- CRUD anggota
- CRUD kasus
- CRUD advokasi
- upload dokumen
- dashboard ringkas

## Tahap Setelah MVP

- kartu anggota digital
- notifikasi WhatsApp
- laporan lanjutan
- arsip dokumen hukum
- peta kasus berbasis lokasi
