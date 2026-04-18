# Go-Live Runbook — sepetak.org

Checklist eksekusi urutan go-live dari server baru sampai produksi live.
Ikuti urut dari atas ke bawah; setiap langkah idempotent dan aman diulang.

## Prasyarat

- [ ] DNS: A record `sepetak.org` dan `www.sepetak.org` sudah point ke IP server.
- [ ] Port 22 (SSH), 80 (HTTP), 443 (HTTPS) terbuka di firewall cloud provider.
- [ ] SSH key deploy sudah terpasang di server.
- [ ] Repo Git sudah ada (GitHub/self-hosted) dengan branch `main` = kode produksi.
- [ ] SMTP provider transaksional siap (Mailgun/SES/Postmark) — alamat
      `no-reply@sepetak.org` atau domain terverifikasi.

## 1. Provisioning server (sekali-pakai)

Sebagai **root** di server target (Ubuntu 22.04 LTS):

```bash
# Salin dari workstation
scp scripts/provision.sh root@IP:/root/
ssh root@IP

# Jalankan provisioning
sudo bash /root/provision.sh
```

Durasi ± 8–15 menit tergantung bandwidth. Output akhir disimpan di
`/root/sepetak-provision-summary.txt` — file ini berisi **DB_PASSWORD yang
di-generate otomatis**; salin aman ke password manager tim.

Verifikasi:

- [ ] `systemctl is-active nginx postgresql redis-server supervisor` → semuanya `active`
- [ ] `php -v` → 8.3.x
- [ ] `psql --version` → 17.x
- [ ] `node -v` → v20.x
- [ ] `id deploy` → user ada, anggota grup `www-data`
- [ ] `sudo ufw status` → `Status: active`, 22/80/443 allowed

## 2. Clone repo + env produksi (sebagai user `deploy`)

```bash
sudo su - deploy
cd /var/www/sepetak.org/releases
git clone git@github.com:<org>/sepetak.org.git initial
cd initial

# Salin template env ke shared (sekali saja)
cp .env.production.example /var/www/sepetak.org/shared/.env
chmod 640 /var/www/sepetak.org/shared/.env
```

Edit `/var/www/sepetak.org/shared/.env` dan isi minimal:

```
APP_KEY=          # dibuat di langkah 3
APP_URL=https://sepetak.org
DB_PASSWORD=      # dari /root/sepetak-provision-summary.txt
MAIL_HOST=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="no-reply@sepetak.org"
```

Set `SESSION_DOMAIN=.sepetak.org` jika nanti perlu subdomain.

## 3. Generate APP_KEY + deploy pertama

```bash
# Symlink env ke rilis (sekali saja, selanjutnya deploy.sh yang menangani)
ln -sfn /var/www/sepetak.org/shared/.env /var/www/sepetak.org/releases/initial/.env

cd /var/www/sepetak.org/releases/initial
composer install --no-dev --optimize-autoloader
php artisan key:generate --show
# Copy-paste output ke APP_KEY di /var/www/sepetak.org/shared/.env

# Deploy rilis pertama (otomatis: composer, npm build, migrate, cache, symlink current)
bash scripts/deploy.sh initial
```

Verifikasi:

- [ ] `ls -la /var/www/sepetak.org/current` → symlink → `releases/initial`
- [ ] `curl http://127.0.0.1 -H 'Host: sepetak.org'` → 200 atau 301

### Seed data awal (HANYA sekali di produksi)

```bash
cd /var/www/sepetak.org/current
php artisan db:seed --class=DatabaseSeeder --force
```

Setelah seed sukses, **segera** ganti password 3 akun demo:

```bash
php artisan tinker
>>> User::where('email', 'admin@sepetak.org')->update(['password' => bcrypt('PASSWORD-BARU-YANG-KUAT')]);
>>> User::where('email', 'redaksi@sepetak.org')->update(['password' => bcrypt('...')]);
>>> User::where('email', 'publik@sepetak.org')->update(['password' => bcrypt('...')]);
>>> exit
```

## 4. Pasang Nginx + SSL

```bash
sudo cp /var/www/sepetak.org/current/ops/nginx/sepetak.org.conf /etc/nginx/sites-available/
sudo ln -s /etc/nginx/sites-available/sepetak.org.conf /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

# Certbot harus berjalan setelah DNS sudah propagasi
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d sepetak.org -d www.sepetak.org --redirect --agree-tos -m admin@sepetak.org -n
sudo systemctl enable --now certbot.timer
sudo certbot renew --dry-run
```

Verifikasi:

- [ ] `curl -I https://sepetak.org` → `HTTP/2 200`
- [ ] `curl -I http://sepetak.org` → `301 https://sepetak.org/`
- [ ] `curl -I https://www.sepetak.org` → `301 https://sepetak.org/`
- [ ] SSLLabs grade ≥ A (https://www.ssllabs.com/ssltest/analyze.html?d=sepetak.org)

## 5. Queue worker + Cron scheduler + Backup

```bash
# Queue worker (supervisor)
sudo cp /var/www/sepetak.org/current/ops/supervisor/sepetak-queue.conf /etc/supervisor/conf.d/
sudo supervisorctl reread && sudo supervisorctl update
sudo supervisorctl status sepetak-queue:*

# Scheduler + backup (cron)
sudo cp /var/www/sepetak.org/current/ops/cron/sepetak /etc/cron.d/sepetak
sudo chmod 644 /etc/cron.d/sepetak
sudo systemctl restart cron

# Test manual backup sekali
sudo -u deploy /var/www/sepetak.org/current/scripts/backup.sh
ls -la /var/www/sepetak.org/shared/backups/
```

Verifikasi:

- [ ] `supervisorctl status sepetak-queue:*` → `RUNNING`
- [ ] `/var/log/sepetak-scheduler.log` bertambah tiap menit (tunggu 2-3 menit setelah cron aktif)
- [ ] Folder `shared/backups/` berisi `db-*.sql.gz` dan `storage-*.tar.gz`

### (Opsional) Off-site backup via rclone

```bash
sudo apt install -y rclone
rclone config  # setup remote mis. `s3-sepetak`
# Tambah ke /etc/cron.d/sepetak (edit baris backup):
# 15 2 * * * deploy RCLONE_REMOTE=s3-sepetak:bucket/backups /var/www/sepetak.org/current/scripts/backup.sh
```

## 6. Smoke test publik

```bash
# Dari luar server
curl -fsS https://sepetak.org/health | jq .
# Harus: { "status": "ok", "checks": { "database": {"status":"ok"}, "cache": {"status":"ok"} } }

curl -fsS -o /dev/null -w '%{http_code}\n' https://sepetak.org/             # 200
curl -fsS -o /dev/null -w '%{http_code}\n' https://sepetak.org/berita       # 200
curl -fsS -o /dev/null -w '%{http_code}\n' https://sepetak.org/sitemap.xml  # 200
curl -fsS -o /dev/null -w '%{http_code}\n' https://sepetak.org/feed.xml     # 200
curl -fsS -o /dev/null -w '%{http_code}\n' https://sepetak.org/admin/login  # 200
curl -fsS -o /dev/null -w '%{http_code}\n' https://sepetak.org/daftar-anggota  # 200
```

Login admin di `https://sepetak.org/admin/login`, verifikasi:

- [ ] Dashboard render (4 stat + 2 chart)
- [ ] Resource Anggota & Kasus Agraria render dengan tombol Export/View
- [ ] ViewAction membuka infolist dengan galeri media
- [ ] Coba daftar anggota dummy → cek email `no-reply@sepetak.org` masuk ke admin

## 7. Pasang monitoring eksternal

- **Uptime Robot** (gratis): monitor HTTPS GET `https://sepetak.org/health`
  tiap 5 menit, alert via email + WhatsApp bila 503 atau unreachable.
- **SSL expiry monitor** (Uptime Robot juga support) untuk `sepetak.org`.
- **(Opsional) Sentry**: `composer require sentry/sentry-laravel` +
  `SENTRY_LARAVEL_DSN` di `.env` untuk error tracking.

## 8. Harden akses admin

- [ ] Matikan password SSH root: `/etc/ssh/sshd_config` → `PermitRootLogin no`, `PasswordAuthentication no`, `systemctl reload ssh`.
- [ ] Ganti port SSH dari 22 ke port non-standar (opsional).
- [ ] IP allow-list di Nginx untuk `/admin` (opsional, aktifkan saat kritis).
- [ ] Fail2ban sudah aktif (lihat `fail2ban-client status sshd`).

## 9. Rilis berikutnya (update pasca go-live)

Alur standar untuk rilis berikutnya (dari branch `main` yang baru di-merge):

```bash
sudo su - deploy
cd /var/www/sepetak.org/releases
git clone git@github.com:<org>/sepetak.org.git $(date -u +%Y%m%d%H%M%S)
cd $(ls -1dt */ | head -n1)
ln -sfn /var/www/sepetak.org/shared/.env .env
bash scripts/deploy.sh $(basename "$PWD")
```

Atau lebih ringkas dari rilis saat ini:

```bash
cd /var/www/sepetak.org/current && git pull && bash scripts/deploy.sh
```

`deploy.sh` akan:

1. `composer install --no-dev --optimize-autoloader`
2. `npm ci && npm run build`
3. `php artisan storage:link`
4. `php artisan migrate --force`
5. `php artisan config:cache && route:cache && view:cache && event:cache`
6. `chmod` storage + `bootstrap/cache`
7. Switch symlink `current` ke rilis baru (atomik)
8. Reload php-fpm + nginx
9. Restart queue worker supervisor
10. Hapus rilis lama (simpan 5 terakhir)

## 10. Rollback cepat

Jika rilis baru bermasalah:

```bash
# List rilis yang masih ada
ls -1dt /var/www/sepetak.org/releases/

# Point current ke rilis sebelumnya (misal sebelum latest)
sudo -u deploy ln -sfn /var/www/sepetak.org/releases/<RELEASE-SEBELUMNYA> /var/www/sepetak.org/current
sudo systemctl reload php8.3-fpm
sudo supervisorctl restart sepetak-queue:*
```

Database rollback: `pg_restore` dari `shared/backups/db-YYYYMMDD-HHMMSS.sql.gz`.

## Indikator go-live SUKSES

Semua centang di bawah harus hijau sebelum mengumumkan ke publik:

- [ ] `https://sepetak.org/health` → 200 `ok`
- [ ] Homepage, berita, daftar anggota, sitemap, admin login — semua 200
- [ ] Sertifikat SSL valid, HSTS aktif
- [ ] Password 3 akun demo sudah diganti
- [ ] Queue worker aktif (test: buat pendaftar anggota → admin dapat email)
- [ ] Backup harian pertama sukses
- [ ] Uptime Robot monitoring aktif
- [ ] Tim pengurus sudah dibagikan password admin via password manager
