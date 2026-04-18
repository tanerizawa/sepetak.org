# Dokumentasi Teknis Implementasi

## Tujuan

- Mengganti tipografi heading ke Anton secara konsisten.
- Menerapkan golden ratio untuk scale tipografi dan spacing.
- Memperkenalkan token warna yang lebih lembut, tetap satu keluarga, dan bisa divariasikan (A/B/C).
- Menyediakan halaman design system sebagai mockup interaktif bagi developer.

## Arsitektur Token

### 1) CSS Variables

- Theme ditentukan melalui atribut `data-theme` pada elemen `html`.
- Token warna dan spacing disimpan sebagai CSS variables agar:
  - mudah diganti antar varian
  - tidak perlu refactor besar di Blade

### 2) Tailwind Token Bridge

- Warna Tailwind memetakan ke CSS variables (mis. `bg-paper`, `text-ink`, `bg-primary`).
- Komponen lama (yang sudah memakai kelas token) tetap jalan, hanya nilai warnanya yang berubah.

### 3) Typography

- `font-display` (Anton) menjadi font utama untuk heading dan elemen editorial.
- Skala font memakai nilai berbasis ϕ, dengan `clamp()` untuk responsif.

## Halaman Design System

Halaman ini berfungsi sebagai:
- style guide visual (token + contoh)
- mockup interaktif (switch varian A/B/C)
- tempat QA cepat untuk kontras/spacing/komponen

## Checklist Developer

- Pastikan font Anton termuat via `<link rel="stylesheet" href="https://fonts.googleapis.com/...">`.
- Pastikan `data-theme` default mengarah ke varian yang dipilih.
- Jalankan build asset (Vite) setelah perubahan Tailwind/CSS.
- Verifikasi kontras teks normal minimal WCAG 2.1 (≥ 4.5:1) pada state default dan hover.
