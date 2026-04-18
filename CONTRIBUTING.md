## Code Standards

- **PHP**: PSR-12 (dijalankan via Laravel Pint)
- **Blade**: Indentasi 4 spasi
- **CSS**: Tailwind CSS utility-first, build via Vite
- **Database**: `snake_case` untuk nama tabel dan kolom, tabel plural (`members`, `agrarian_cases`)
- **Models**: PascalCase singular dalam bahasa Inggris (`Member`, `AgrarianCase`, `AdvocacyProgram`, `Post`, `Page`, `Event`). Hindari nama kelas reserved PHP (tidak boleh pakai `Case` — gunakan `AgrarianCase`).
- **Migration**: Deskriptif (`create_members_table`, `make_addresses_line_1_nullable`)
- **Filament Resource**: satu Resource per model utama, RelationManager untuk child entity
- **Policy**: `app/Policies/<Model>Policy.php` extends `BaseResourcePolicy` dengan `$permission = '<spatie-permission>'`

## Development Environment

- PHP 8.3+ (PHP 8.4 juga didukung)
- PostgreSQL 17 (produksi & lokal)
- Node.js 20.x
- Composer 2.x
- Redis 7.x (opsional, wajib di produksi)

## Testing

Database test terpisah (Postgres `sepetak_test`, dibuat sekali):

```bash
sudo -u postgres createdb -O sepetak sepetak_test
```

Lalu jalankan:

```bash
# Semua test
./vendor/bin/phpunit

# Satu test class
./vendor/bin/phpunit --filter=MemberRegistrationTest

# Satu test method
./vendor/bin/phpunit --filter=test_valid_submission_creates_member_and_address

# Output mirip testdox
./vendor/bin/phpunit --testdox
```

## Menambahkan Resource Baru

1. Buat migration + model + factory (opsional)
2. Buat Filament Resource (`php artisan make:filament-resource <Model>`)
3. Buat Policy yang extend `App\Policies\BaseResourcePolicy`, set `$permission`
4. Daftarkan policy di `App\Providers\AppServiceProvider::registerPolicies()`
5. Tambahkan permission baru (jika perlu) di `DatabaseSeeder`
6. Tulis feature test di `tests/Feature/` (minimal: create, update, list, guard policy)