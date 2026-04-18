# Style Guide

Dokumen ini mendefinisikan design tokens dan aturan pemakaian untuk menjaga konsistensi visual dan keterbacaan.

## Typography Scale (Golden Ratio 1:1.618)

Basis:
- Body base: 16px (1rem)
- Rasio: 1.618 (ϕ)

Skala (dibulatkan agar stabil di web):
- Body: 1rem (16px), line-height 1.65
- H6/Label besar: 1.125rem (18px), line-height 1.45
- H5: 1.25rem (20px), line-height 1.35
- H4: 1.625rem (26px), line-height 1.18
- H3: 2.625rem (42px), line-height 1.05
- H2: 4.25rem (68px), line-height 0.95
- H1/Display: 6.875rem (110px), line-height 0.92

Aturan:
- Heading selalu Anton, uppercase.
- Body selalu Work Sans.
- Meta/label selalu Space Mono, uppercase dengan tracking lebar.

## Spacing System (Golden Ratio)

Unit dasar: 8px

Scale:
- S1: 8px
- S2: 13px
- S3: 21px
- S4: 34px
- S5: 55px
- S6: 89px

Aturan:
- Hindari jarak “random”. Gunakan salah satu dari skala di atas.
- Gap antar komponen setara 1–2 tingkat skala (mis: S3→S4).

## Grid & Layout

- Container max width: 1200px (dibatasi), dengan padding responsif.
- Pola:
  - Hero: Z-pattern (headline kiri, visual kanan, CTA jelas).
  - Listing/artikel: F-pattern (judul, meta, excerpt, CTA).
- Lebar kolom teks ideal: 60–75 karakter (≈ 65–72ch).

## Color Palette (Token)

Token wajib:
- `paper` (background)
- `ink` (teks utama)
- `primary` (CTA, highlight utama)
- `primary-weak` (surface ringan/selection/badge)
- `secondary` (aksen pendukung)
- `accent` (aksen untuk status/utility)

Aturan:
- Teks default: `ink` di atas `paper`.
- `primary` untuk elemen yang butuh prioritas (CTA, judul penting, divider).
- `secondary` untuk badge/ikon ringan.
- `accent` hanya untuk utility (tag, hint, progress), bukan untuk CTA utama.

## Komponen (Baseline)

- Button:
  - Solid primary (CTA utama)
  - Ghost ink (CTA sekunder)
  - Neutral (link-style)
- Card:
  - Surface `paper` + border `ink` tipis
  - Hover hanya menambah kontras/outline, tanpa efek berlebihan
- Badge/Meta stamp:
  - Space Mono, uppercase, tracking lebar
  - Pakai `primary-weak` untuk background, `ink` untuk teks
