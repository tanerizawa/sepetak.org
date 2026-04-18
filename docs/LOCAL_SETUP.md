# Local Setup - VS Code + Copilot

## Tujuan

Dokumen ini memastikan pengembangan dilakukan di VS Code dengan alur kerja yang
ringan dan mendukung 100 persen Copilot coding.

## Prasyarat

- PHP 8.3+ (PHP 8.4 didukung)
- Composer 2.x
- Node.js 20.x + npm
- PostgreSQL 17 (ekstensi `pdo_pgsql` wajib di PHP)
- Redis 7.x (opsional untuk pengembangan lokal)

## Rekomendasi Extension VS Code

- PHP Intelephense
- Laravel Extension Pack
- Tailwind CSS IntelliSense
- DotENV
- EditorConfig for VS Code

## Langkah Setup

```bash
# 1. Clone repo dan install dependency
composer install
npm install

# 2. Siapkan file .env
cp .env.example .env
php artisan key:generate

# 3. Siapkan database PostgreSQL
sudo -u postgres createuser --pwprompt sepetak   # password: sepetak (atau sesuaikan)
sudo -u postgres createdb -O sepetak sepetak
sudo -u postgres createdb -O sepetak sepetak_test  # untuk phpunit

# 4. Migrasi + seed
php artisan migrate --seed

# 5. Build asset frontend
npm run build

# 6. Symlink storage publik
php artisan storage:link

# 7. Jalankan server
php artisan serve
```

Akses:

- Website publik: http://127.0.0.1:8000
- Admin panel: http://127.0.0.1:8000/admin
  - `admin@sepetak.org` / `password` (superadmin)
  - `redaksi@sepetak.org` / `password` (operator)
  - `publik@sepetak.org` / `password` (viewer)

## Konvensi Kerja

- Satu fitur kecil per commit
- Gunakan branch per fitur
- Simpan perubahan dokumentasi di folder `docs/`
- Jalankan `./vendor/bin/phpunit` sebelum PR

## Alur Kerja Copilot

1. Buka file target di editor
2. Tulis komentar singkat tujuan perubahan
3. Gunakan Copilot untuk draft
4. Review dan rapikan hasil

## Struktur Folder

- `app/` — logic (Models, Controllers, Policies, Services)
- `app/Filament/` — admin panel (Resources, Pages, Widgets, RelationManagers)
- `resources/views/` — tampilan publik (layouts, posts, pages, feeds, member-registration)
- `resources/css/` dan `resources/js/` — entry point Vite
- `database/` — migrations, seeders, factories
- `tests/Feature/` — feature test
- `docs/` — dokumentasi proyek
