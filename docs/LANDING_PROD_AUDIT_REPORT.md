# Audit & Perbaikan Landing (sepetak.org)

Dokumen ini mengaudit tampilan publik (exclude admin) di domain **sepetak.org** dan memastikan konsistensi dengan spesifikasi desain pada [LANDING_REDESIGN_PLAN.md](file:///home/sepetak.org/docs/LANDING_REDESIGN_PLAN.md).

## 1) Temuan Utama (Produksi)

### A. Asset/Build Issue: request `@vite/client` gagal

- Di produksi, halaman memicu request `https://sepetak.org/@vite/client` dan gagal (`net::ERR_ABORTED`).
- Ini indikasi lingkungan produksi masih “terdeteksi dev server” (biasanya karena file `public/hot` tertinggal), sehingga browser mencoba memuat Vite HMR client.

**Dampak**
- JS/CSS bisa tidak termuat konsisten (tergantung urutan/caching), menyebabkan tampilan berpotensi “acak-acakan”.

**Fix yang diimplementasikan**
- Layout hanya memakai mode `hot` jika environment `local`. Produksi akan selalu pakai `build/manifest.json`.
- Implementasi: [app.blade.php](file:///home/sepetak.org/resources/views/layouts/app.blade.php)

### B. Ketidaksesuaian konsep desain (vs LANDING_REDESIGN_PLAN)

**Yang tidak sesuai konsep awal:**
- Tipografi berubah ke sistem fonts (padahal spec meminta Google Fonts: Big Shoulders/Anton/Work Sans/Space Mono/Roboto Slab).
- Palet warna sempat diganti ke token HSL (padahal spec menetapkan hex “Tani Merah”).
- Ada elemen “soft shadow/rounded/hover scale/parallax” yang bertentangan dengan prinsip poster (sudut tegas, hard shadow, no hover scale/parallax).

**Fix yang diimplementasikan**
- Mengembalikan palet dan tipografi ke konsep awal.
- Menghapus parallax.
- Mengubah kartu artikel ke gaya poster (hard border + hard shadow + tanpa hover scale).
- Implementasi: [app.css](file:///home/sepetak.org/resources/css/app.css), [tailwind.config.js](file:///home/sepetak.org/tailwind.config.js), [app.blade.php](file:///home/sepetak.org/resources/views/layouts/app.blade.php), [app.js](file:///home/sepetak.org/resources/js/app.js), [article-card.blade.php](file:///home/sepetak.org/resources/views/components/rev/article-card.blade.php)

## 2) Before/After Screenshot (Baseline)

- Produksi sebelum fix (baseline audit): [sepetak-org-home-before-fix.png](file:///home/sepetak.org/docs/screenshots/sepetak-org-home-before-fix.png)
- Lokal sesudah fix (referensi hasil yang akan sama setelah deploy): [home-after-plan.png](file:///home/sepetak.org/docs/screenshots/home-after-plan.png)

## 3) Konsistensi Dengan LANDING_REDESIGN_PLAN.md (Checklist)

- [x] Palet warna “Tani Merah” (hex) digunakan kembali via Tailwind tokens.
- [x] Tipografi kembali ke Google Fonts sesuai spec.
- [x] Sudut tegas + hard shadow diprioritaskan.
- [x] Tidak ada hover scale / parallax.
- [x] Reduced-motion: ticker/animasi dimatikan saat `prefers-reduced-motion`.

## 4) Build & Deployment (Produksi)

Karena proses deploy tidak bisa dijalankan dari lingkungan ini, berikut langkah aman untuk redeploy ke sepetak.org:

1. Pull perubahan kode terbaru.
2. Pastikan **hapus `public/hot`** bila ada (ini akar masalah `@vite/client`).
3. Build asset:
   - `npm ci`
   - `npm run build` (menghasilkan `public/build/manifest.json` dan asset CSS/JS)
4. Laravel cache refresh:
   - `php artisan optimize:clear`
   - `php artisan config:cache`
   - `php artisan route:cache` (opsional)
   - `php artisan view:cache` (opsional)
5. Restart layanan PHP (php-fpm / supervisor) bila diperlukan.

## 5) Cross-browser Testing

Lingkungan ini hanya bisa memverifikasi via Chromium. Untuk memenuhi target (Chrome/Firefox/Safari) lakukan smoke test manual setelah deploy:

- **Chrome / Edge (Blink)**: cek render font, header/nav sticky, ticker, card hover translate, tidak ada request `@vite/client`.
- **Firefox (Gecko)**: pastikan `clip-path` diagonal masih OK; jika ada issue, fallback adalah mengganti divider diagonal ke border biasa.
- **Safari (WebKit)**: fokus pada `backdrop-filter`; bila blur tidak didukung, fallback tetap aman karena background nav menggunakan opacity.

