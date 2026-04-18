# Laporan Redesign Homepage (Non-Admin)

## Tujuan

- Meningkatkan hierarki visual, keterbacaan, dan konsistensi desain.
- Memperluas section artikel (6–9 item), menambah filter kategori, dan menambah infinite scroll.
- Menambahkan micro-interactions (hover 300ms, skeleton loading, parallax hero, sticky nav blur).
- Memastikan kontras warna lebih ramah WCAG 2.1 (target 4.5:1 untuk teks normal, 3:1 untuk teks besar).

## Before / After

- Before screenshot: [home-before.png](file:///home/sepetak.org/docs/screenshots/home-before.png)
- After screenshot: [home-after.png](file:///home/sepetak.org/docs/screenshots/home-after.png)

## Perubahan Utama

### 1) Coloring & Visual Hierarchy (HSL + kontras)

- Sistem warna diganti menjadi token HSL berbasis CSS variables (`--flag-*`, `--ink-*`, `--paper-*`, dll) dengan rentang saturasi lembut:
  - Primer (flag): 30–40% (sesuai requirement).
  - Netral/sekunder: 8–20%.
- Tailwind palette `flag/ink/paper/ochre/earth` sekarang mengacu ke CSS variables (lebih konsisten lintas komponen).

File terkait:
- [app.css](file:///home/sepetak.org/resources/css/app.css)
- [tailwind.config.js](file:///home/sepetak.org/tailwind.config.js)

### 2) Layout & Section Height + Scroll Snap

- Homepage diubah menjadi snap-scrolling:
  - Hero: 100vh (vertically centered).
  - Section konten: 80vh dengan overflow internal.
  - `scroll-snap-type: y mandatory` dan `scroll-behavior: smooth` (menghormati `prefers-reduced-motion`).

File terkait:
- [home.blade.php](file:///home/sepetak.org/resources/views/home.blade.php)
- [app.css](file:///home/sepetak.org/resources/css/app.css)
- [app.blade.php](file:///home/sepetak.org/resources/views/layouts/app.blade.php)

### 3) Article Section Enhancement (6–9 + tabs + infinite scroll)

- Section artikel menampilkan 9 item per halaman.
- Tabs kategori untuk filter (Semua + hingga 6 kategori teratas by published count).
- Infinite scroll untuk pagination berikutnya.
- Kartu artikel kini memuat:
  - Featured image 16:9 (`aspect-video`)
  - Title max 2 baris, excerpt max 3 baris (line clamp)
  - Category badge
  - Reading time
  - Publish date

File terkait:
- [HomeController.php](file:///home/sepetak.org/app/Http/Controllers/HomeController.php)
- [web.php](file:///home/sepetak.org/routes/web.php)
- [home.blade.php](file:///home/sepetak.org/resources/views/home.blade.php)
- [home-article-cards.blade.php](file:///home/sepetak.org/resources/views/partials/home-article-cards.blade.php)
- [article-card.blade.php](file:///home/sepetak.org/resources/views/components/rev/article-card.blade.php)
- [Post.php](file:///home/sepetak.org/app/Models/Post.php)
- [app.js](file:///home/sepetak.org/resources/js/app.js)

### 4) Micro-interactions & Transitions

- Hover transitions standar 300ms ease-in-out (kartu & tombol).
- Loading skeleton untuk load kategori / infinite scroll.
- Parallax hero (0.5x-ish) via transform + requestAnimationFrame (disabled untuk `prefers-reduced-motion`).
- Sticky navigation dengan `backdrop-filter: blur(10px)`.

File terkait:
- [app.js](file:///home/sepetak.org/resources/js/app.js)
- [app.css](file:///home/sepetak.org/resources/css/app.css)
- [app.blade.php](file:///home/sepetak.org/resources/views/layouts/app.blade.php)

### 5) Typography & Spacing

- Font diganti menjadi sistem:
  - Heading: sans-serif system.
  - Body: serif system.
- Default line-height:
  - Heading: 1.2
  - Body: 1.6
- Grid container homepage distandardisasi: `max-width: 1200px` dengan `padding: 0 5%`.

File terkait:
- [app.css](file:///home/sepetak.org/resources/css/app.css)
- [home.blade.php](file:///home/sepetak.org/resources/views/home.blade.php)

## Lighthouse (Target ≥ 90)

Hasil Lighthouse (after):
- Performance: 99
- Accessibility: 95
- Best Practices: 92
- SEO: 100

File laporan:
- [home-after.json](file:///home/sepetak.org/docs/lighthouse/home-after.json)

## A/B Testing (Legacy vs Modern)

- Modern (default): `/`
- Legacy: `/?ab=legacy`
- Modern (force): `/?ab=modern`

Flag konfigurasi:
- `HOMEPAGE_AB_ENABLED=true` untuk random assignment modern/legacy (cookie `home_ab_variant`).

File terkait:
- [HomeController.php](file:///home/sepetak.org/app/Http/Controllers/HomeController.php)
- [sepetak.php](file:///home/sepetak.org/config/sepetak.php)
- [home_legacy.blade.php](file:///home/sepetak.org/resources/views/home_legacy.blade.php)

## Cross-browser & User Testing (Catatan)

Lingkungan ini tidak bisa menjalankan sesi user testing 5–10 responden secara nyata, namun sudah disiapkan:
- UI lebih aksesibel (skip link, tablist, reduced motion).
- A/B flag tersedia untuk uji perbandingan.

Rekomendasi eksekusi manual:
- Cross-browser: Chrome/Firefox/Safari/Edge untuk scroll-snap, backdrop-filter, dan parallax (fallback OK).
- User testing: 5–10 responden dengan skenario:
  - Temukan artikel berdasarkan kategori.
  - Scroll sampai load artikel berikutnya.
  - Gunakan keyboard (Tab) untuk navigasi tabs & CTA.

