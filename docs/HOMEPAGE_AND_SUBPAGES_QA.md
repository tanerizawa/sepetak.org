# Perbaikan Homepage & Sub‑Halaman (QA)

Dokumen ini merangkum perubahan UX/UI, konsistensi desain, performa loading, dan checklist kualitas untuk halaman publik yang dinavigasi dari beranda.

## Ruang Lingkup

- Beranda (`/`)
- Artikel (`/artikel`)
- Detail artikel (`/artikel/{slug}`)
- Halaman statis (`/halaman/{slug}`) termasuk “Tentang Kami”
- Kontak (`/kontak`)
- Pendaftaran anggota (`/daftar-anggota`)

## Perubahan Utama

### 1) Beranda: Artikel Maksimal 6 per Kategori + Paginasi

- Per page artikel di beranda diset ke 6 agar grid rapi dan ritme scroll stabil.
- Paginasi tetap fungsional melalui:
  - infinite scroll (IntersectionObserver)
  - tombol “Muat lagi” (fallback aksesibel) saat `next_page_url` tersedia.

### 2) Beranda: Kategori Baru

- Menambahkan kategori `Kajian Ilmiah` (slug: `kajian-ilmiah`) agar struktur tab punya kanal khusus untuk tulisan analitis/ilmiah.
- Kategori ini tetap muncul di tab meskipun belum ada artikel published (seamless integration).

### 3) Beranda: Scroll Tidak Stuck

- Menghapus pemaksaan section height dan nested scroll yang memicu scroll “macet” dan tidak bisa mencapai footer.
- Beranda kembali ke normal document flow agar nyaman di mobile/desktop.

### 4) Interaksi: Kontras Hover/Active Aman

- Tab kategori memastikan kontras aman pada state aktif dan hover (tidak ada kondisi teks dan latar berwarna sama).

### 5) Detail Artikel: Cover Landscape + Layout Baca “Bookish/Ilmiah”

- Cover pada header detail artikel dipaksa `aspect-video` + `object-cover` sehingga foto portrait tetap rapi.
- Kolom baca dipersempit, line-height dan font-size ditata agar pengalaman membaca mirip buku/karya ilmiah.

## Checklist Kualitas

### Konsistensi Warna

- [ ] Semua warna UI memakai token (`flag/ink/paper/ochre/earth`) tanpa hardcode yang memecah theme.
- [ ] Kontras teks normal memenuhi WCAG (≥ 4.5:1) untuk state default dan hover.
- [ ] CTA primary konsisten memakai warna primary (`flag-500`) dengan teks `paper-50`.

### Tipografi

- [ ] Heading memakai Anton (`font-display`) konsisten.
- [ ] Body teks panjang nyaman (line-height stabil, ukuran tidak kecil).
- [ ] Meta memakai Space Mono (`meta-stamp`) konsisten.

### Spacing & Grid

- [ ] Container konsisten (max-width dan padding responsif).
- [ ] Tidak ada section yang memaksa height viewport sehingga memicu nested scroll.
- [ ] Ritme whitespace mengikuti skala spacing tokens.

### Komponen UI

- [ ] Tab kategori: state aktif/hover kontras aman dan bisa dioperasikan keyboard.
- [ ] Tombol “Muat lagi” muncul hanya ketika ada halaman berikutnya.
- [ ] Kartu artikel konsisten: cover 16:9, judul clamp, meta stamp terbaca.

### Perilaku Interaktif

- [ ] Infinite scroll tidak memblokir tombol fallback.
- [ ] Loading skeleton muncul saat fetch, hilang setelah selesai/errored.
- [ ] Error fetch tidak menyebabkan UI “stuck”; state `next_page_url` menjadi kosong.

## Uji yang Dilakukan (Repo)

- Build assets: `npm run build`
- Test suite: `php artisan test`
- Regresi: memastikan beranda tidak memuat class scroll-snap, endpoint artikel beranda membatasi 6 item dan memunculkan paginasi.

## Checklist Uji Manual (Sebelum Deploy Production)

### Device

- [ ] Desktop (≥ 1280px)
- [ ] Mobile (360×640 atau 390×844)

### Browser

- [ ] Chrome
- [ ] Firefox
- [ ] Safari

### Skenario

- [ ] Beranda bisa scroll sampai footer tanpa macet.
- [ ] Tab kategori: ganti kategori, lalu “Muat lagi” bekerja.
- [ ] `/artikel`: filter/search berjalan dan layout tetap konsisten.
- [ ] Detail artikel:
  - cover selalu 16:9 (tidak melar)
  - kolom baca nyaman dan tidak terlalu lebar
  - TOC sticky berfungsi di desktop
- [ ] Halaman “Tentang Kami” dan halaman statis lain tetap rapi (prose + heading).
