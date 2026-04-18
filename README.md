## Status Repo Saat Ini

Repo sudah melewati tahap **MVP publik + admin panel dengan role hierarchy + test suite hijau**:

- Laravel 11, FilamentPHP 3, Spatie Permission, dan Spatie Media Library terpasang
- PostgreSQL 17 aktif, 25 migrasi, seeder lengkap (role + permission + 3 user demo + kategori + site setting + post + page)
- 9 Resource admin + 4 RelationManager; policy per-resource dengan role `superadmin`/`admin`/`operator`/`viewer`; guard panel via `FilamentUser::canAccessPanel` (wajib `is_active` + role)
- Auto-generate kode (`ANG-`, `KAS-`, `ADV-`) di model via `booted()` — admin tidak perlu input manual
- Website publik: beranda, daftar berita + pagination, detail berita, halaman statis, formulir pendaftaran anggota (rate-limit 5/menit)
- SEO publik: sitemap.xml dinamis, feed RSS, robots.txt, Open Graph + Twitter Card + canonical
- Asset produksi via Vite (`@vite` menggantikan CDN Tailwind); `npm run build` menghasilkan manifest `public/build/`
- 22 feature test hijau di `tests/Feature/` (registrasi, route publik, akses admin panel, gate policy, regression enum RelationManager)
- Dokumen deployment lengkap (Nginx server block, SSL Certbot, Supervisor Horizon, checklist go-live) di `docs/DEPLOYMENT.md`

Fokus berikutnya: Part 6 (export PDF/Excel, media library UI, dashboard statistik lanjut, notifikasi email), Part 7 lanjutan (Nginx vhost + SSL di server target, monitoring, backup otomatis).

## Quick Start

```bash
# 1. Salin env dan generate APP_KEY
cp .env.example .env
php artisan key:generate

# 2. Install dependency PHP & JS
composer install
npm install

# 3. Siapkan PostgreSQL (user/database sepetak sudah ada di .env.example)
sudo -u postgres createuser --pwprompt sepetak   # password sesuai .env
sudo -u postgres createdb -O sepetak sepetak
sudo -u postgres createdb -O sepetak sepetak_test   # untuk phpunit

# 4. Migrasi + seed
php artisan migrate --seed

# 5. Build asset frontend dan symlink storage
npm run build
php artisan storage:link

# 6. Jalankan server lokal
php artisan serve
```

Akses:

- Website publik: http://127.0.0.1:8000
- Admin panel: http://127.0.0.1:8000/admin
  - `admin@sepetak.org` / `password` — superadmin
  - `redaksi@sepetak.org` / `password` — operator (CRUD non-delete)
  - `publik@sepetak.org` / `password` — viewer (read-only)

Menjalankan test:

```bash
# Database sepetak_test sudah dibuat di Quick Start di atas

./vendor/bin/phpunit                     # seluruh suite (22 test, 75 asersi)
./vendor/bin/phpunit --testdox           # mode readable
./vendor/bin/phpunit --filter=MemberRegistrationTest
```