# Deployment VPS - Rekomendasi Produksi

## Stack Produksi

- Ubuntu 22.04 LTS
- Nginx
- PHP 8.3 / 8.4 + PHP-FPM
- PostgreSQL 17 (standar; migrasi sudah diuji di Postgres dan memakai `CHECK` constraint untuk enum)
- Redis 7 (cache / session / queue)
- Supervisor (Horizon worker)
- Certbot untuk SSL Let's Encrypt
- Domain **`sepetak.org`** sudah di-point ke IP server target (A record aktif)

## Struktur Server

- `/var/www/sepetak.org/current` — symlink ke rilis aktif
- `/var/www/sepetak.org/shared/.env` — file env tunggal, dibagikan ke semua rilis
- `/var/www/sepetak.org/shared/storage` — folder storage persisten

## User dan Permission

- User deploy (misal `deploy`) terpisah dari root
- Web server user: `www-data`
- `shared/storage` dan `current/bootstrap/cache` writable oleh `www-data`
- Anggota grup: `usermod -aG deploy www-data`

## Artisan di server (cache / view)

Jangan menjalankan `php artisan …` sebagai **root** pada direktori produksi: file di `storage/framework/views` dan `bootstrap/cache` akan dimiliki root sehingga PHP-FPM (`www-data`) gagal menulis (HTTP 500, *Permission denied*).

- **Manual / SSH:** dari root aplikasi, gunakan `bash scripts/artisan-web.sh <perintah>` (set `APP_ROOT` jika perlu). Skrip ini menjalankan artisan sebagai `WEB_USER` (default `www-data`) lewat `sudo` atau `runuser`.
- **Deploy otomatis:** `scripts/deploy.sh` memanggil semua langkah artisan sebagai `WEB_USER` yang sama; tidak ada fallback ke root.

## Langkah Setup Dasar

1. Update server dan install dependency sistem
2. Install PHP 8.3 dan extension yang dibutuhkan
3. Install PostgreSQL, Redis, Nginx, Supervisor
4. Buat database `sepetak` dan user `sepetak` di PostgreSQL
5. Clone repo ke server (`/var/www/sepetak.org/releases/<tag>`) lalu symlink ke `current`
6. Isi file env produksi (gunakan `.env.example` sebagai basis)
7. Jalankan `composer install --no-dev --optimize-autoloader`
8. Jalankan `npm ci && npm run build`
9. Jalankan `php artisan migrate --force`
10. Jalankan `php artisan db:seed --class=DatabaseSeeder --force` (sekali saja)
11. Jalankan `php artisan storage:link`
12. Cache produksi: `php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache`
13. Konfigurasi queue worker atau Horizon, plus scheduler
14. Pasang SSL via Certbot dan redirect HTTPS

## PHP Extensions Minimum

- bcmath
- ctype
- curl
- dom
- fileinfo
- intl
- mbstring
- openssl
- pdo_pgsql
- pgsql
- redis
- tokenizer
- xml
- zip
- gd atau imagick (untuk konversi media Spatie)

## Nginx Server Block (contoh)

File `/etc/nginx/sites-available/sepetak.org`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name sepetak.org www.sepetak.org;
    return 301 https://sepetak.org$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name www.sepetak.org;
    return 301 https://sepetak.org$request_uri;

    ssl_certificate     /etc/letsencrypt/live/sepetak.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sepetak.org/privkey.pem;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name sepetak.org;

    root /var/www/sepetak.org/current/public;
    index index.php;

    charset utf-8;
    client_max_body_size 25M;

    # Keamanan dasar
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    ssl_certificate     /etc/letsencrypt/live/sepetak.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sepetak.org/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript application/xml text/xml image/svg+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location = /robots.txt  { access_log off; log_not_found off; }
    location = /favicon.ico { access_log off; log_not_found off; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 60s;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* ^/(?:\.env|storage/logs) {
        deny all;
    }
}
```

Aktifkan dan reload:

```bash
sudo ln -s /etc/nginx/sites-available/sepetak.org /etc/nginx/sites-enabled/sepetak.org
sudo nginx -t
sudo systemctl reload nginx
```

## SSL dengan Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d sepetak.org -d www.sepetak.org --redirect
sudo systemctl enable --now certbot.timer
```

## Environment Produksi

Gunakan template lengkap di **[`.env.production.example`](../.env.production.example)**
sebagai basis. Ringkasan nilai wajib:

```
APP_NAME=SEPETAK
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sepetak.org
APP_LOCALE=id
APP_FALLBACK_LOCALE=id

LOG_LEVEL=warning

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sepetak
DB_USERNAME=sepetak
DB_PASSWORD=<strong-random>

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.sepetak.org

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=<smtp-provider>
MAIL_FROM_ADDRESS="no-reply@sepetak.org"
MAIL_FROM_NAME="SEPETAK"
```

> **PENTING**:
> - Ganti `APP_KEY` dengan output `php artisan key:generate --show`.
> - Pastikan `APP_URL=https://sepetak.org` agar sitemap/feed/canonical URL
>   mengarah ke domain yang benar.
> - Set `SESSION_SECURE_COOKIE=true` karena seluruh trafik dilindungi HTTPS.
> - Set `SESSION_DOMAIN=.sepetak.org` jika ingin cookie aktif di subdomain
>   (contoh `admin.sepetak.org` bila suatu saat dipisah). Untuk domain tunggal
>   biarkan `null`.

## Queue dan Scheduler

Supervisor, file `/etc/supervisor/conf.d/sepetak-horizon.conf`:

```ini
[program:sepetak-horizon]
process_name=%(program_name)s
command=php /var/www/sepetak.org/current/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/supervisor/sepetak-horizon.log
stopwaitsecs=3600
```

Cron untuk scheduler:

```
* * * * * cd /var/www/sepetak.org/current && php artisan schedule:run >> /dev/null 2>&1
```

## Hardening Minimum

- Nonaktifkan login password root (`PermitRootLogin no`, `PasswordAuthentication no`)
- Gunakan SSH key dan ganti port SSH dari 22
- Aktifkan UFW: allow 22 (atau port SSH baru), 80, 443
- Pasang Fail2ban untuk SSH dan nginx
- Backup database harian (pg_dump) dan storage harian (rsync/borg)
- Batasi akses `/admin` via IP allow-list bila memungkinkan
- Pasang `APP_DEBUG=false` di produksi dan pastikan log level minimal `warning`
- Rotasi secret (`APP_KEY`) tidak boleh dilakukan setelah data terenkripsi tertulis

## Catatan Domain

Status saat ini:

- Domain **`sepetak.org`** sudah dimiliki organisasi dan A record sudah
  mengarah ke IP server target.
- `www.sepetak.org` akan di-redirect ke apex `sepetak.org` oleh server block
  Nginx (lihat bagian Nginx di atas).

Langkah konfigurasi saat go-live:

1. **DNS**: pastikan A record `sepetak.org` + `www.sepetak.org` mengarah ke
   IP publik server. Tambahkan juga `MX` dan `SPF`/`DKIM` kalau memakai email
   `@sepetak.org` via provider eksternal.
2. **Nginx**: aktifkan server block `sites-available/sepetak.org` dan symlink
   ke `sites-enabled/`. `nginx -t` harus sukses sebelum `systemctl reload nginx`.
3. **SSL**: jalankan `certbot --nginx -d sepetak.org -d www.sepetak.org
   --redirect`. Aktifkan `certbot.timer` dan verifikasi `certbot renew --dry-run`.
4. **APP_URL**: set `APP_URL=https://sepetak.org` di `.env` produksi.
5. **Sitemap & robots**: `sitemap.xml` menggunakan `url()` helper — otomatis
   pakai `APP_URL`, jadi tidak perlu hardcode. Verifikasi
   `curl https://sepetak.org/sitemap.xml`.
6. **Cookie & session**: `SESSION_SECURE_COOKIE=true` di produksi. Pertimbangkan
   `SESSION_DOMAIN=.sepetak.org` jika akan memakai subdomain.
7. **Permission**: `storage/` dan `bootstrap/cache/` writable oleh `www-data`
   (755 atau 775 untuk folder, 644 untuk file).
8. **Harden `/admin`**: minimal HTTPS + rate-limiting Nginx. Pertimbangkan IP
   allow-list via `allow`/`deny` atau basic auth layer sementara saat onboarding
   pengurus.

## Checklist Go-Live

- [ ] `php artisan migrate:status` semua hijau
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache` sukses
- [ ] `npm run build` menghasilkan manifest di `public/build/`
- [ ] `curl -I https://sepetak.org` mengembalikan `HTTP/2 200`
- [ ] `curl -I https://sepetak.org/sitemap.xml` mengembalikan `200`
- [ ] `curl -I https://sepetak.org/admin/login` mengembalikan `200`
- [ ] Login admin berhasil, password default sudah diganti
- [ ] Role `viewer` hanya bisa melihat, `operator` tidak bisa delete, `admin`/`superadmin` full akses
- [ ] Form pendaftaran anggota lewat `throttle:5,1` (maks 5 submit/menit)
- [ ] Horizon berjalan (`php artisan horizon:status`)
- [ ] Scheduler cron berjalan (cek `storage/logs/laravel.log`)
- [ ] Backup database & storage sudah dijadwalkan
