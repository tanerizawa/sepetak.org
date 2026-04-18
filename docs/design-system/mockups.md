# Mockup Hi‑Fi (Deskripsi)

Dokumen ini mendeskripsikan mockup high‑fidelity untuk 3 halaman utama (Home, Listing, Detail Artikel). Implementasi interaktif tersedia sebagai halaman design system di aplikasi.

## 1) Home (Landing)

**Struktur (Z‑pattern)**

```
┌─────────────────────────────────────────────────────────────┐
│ NAV: Logo | Menu | CTA                                      │
├─────────────────────────────────────────────────────────────┤
│ HERO: H1 besar (Anton)         | Visual/Poster/Shape         │
│      Subcopy (Work Sans)       |                             │
│      CTA primary + secondary   |                             │
├─────────────────────────────────────────────────────────────┤
│ Impact strip: 3 statistik + label meta                       │
├─────────────────────────────────────────────────────────────┤
│ Section Artikel: tabs kategori + grid cards                   │
│  [Badge][Title..]  [Badge][Title..]  [Badge][Title..]        │
│  excerpt..         excerpt..         excerpt..               │
│  meta stamp        meta stamp        meta stamp              │
├─────────────────────────────────────────────────────────────┤
│ CTA akhir: ringkas + tombol utama                             │
└─────────────────────────────────────────────────────────────┘
```

**Aturan penting**
- Heading kontras tinggi, subcopy tidak “teriak”.
- CTA primary hanya satu, jelas.
- Whitespace memakai skala 8–13–21–34–55–89.

## 2) Listing (Kategori/Arsip)

**Struktur (F‑pattern)**

```
H1 Kategori
Meta/intro singkat
---------------------------------------------------------------
[Card row]  Judul (Anton) + meta (Space Mono) + excerpt
[Card row]  ...
[Pagination/Infinite]
```

**Aturan penting**
- Konsistensi tinggi antar card rows.
- Meta stamp selalu berada di tempat yang sama untuk memudahkan scanning.

## 3) Detail Artikel

**Struktur**

```
H1 Artikel (Anton)
Meta: kategori | tanggal | reading time
---------------------------------------------------------------
Lead/intro
Body (Work Sans) dengan prose yang lega
Subheading (Anton)
Pull quote / callout (optional)
Footer: tag, share, navigasi next/prev
```

**Aturan penting**
- Lebar kolom teks dibatasi (≈ 65–72ch).
- Paragraf punya jarak yang konsisten (S3 atau S4).

## Mockup Interaktif (Dalam Aplikasi)

Halaman “Design System” menampilkan:
- Switch varian moodboard (A/B/C) untuk preview warna.
- Skala tipografi dan contoh heading/body.
- Komponen tombol/kartu/badge dalam konteks.
