# `ops/` — artefak deployment produksi SEPETAK

Folder ini berisi konfigurasi sistem yang disalin ke server produksi saat
go-live. Semua file aman untuk di-commit (tidak mengandung secret).

## Struktur

```
ops/
├── nginx/
│   └── sepetak.org.conf       # server block + redirect www → apex + SSL
├── supervisor/
│   └── sepetak-queue.conf     # queue worker Laravel (Redis)
└── cron/
    └── sepetak                # scheduler Laravel + backup harian + cleanup
```

Lihat juga:

- [`scripts/provision.sh`](../scripts/provision.sh) — setup server satu-kali
- [`scripts/deploy.sh`](../scripts/deploy.sh) — deploy rilis baru (idempotent)
- [`scripts/backup.sh`](../scripts/backup.sh) — pg_dump + tar storage harian
- [`docs/GO_LIVE_RUNBOOK.md`](../docs/GO_LIVE_RUNBOOK.md) — checklist go-live
- [`.env.production.example`](../.env.production.example) — template env

## Instalasi kilat (setelah `provision.sh` & deploy pertama)

```bash
sudo cp /var/www/sepetak.org/current/ops/nginx/sepetak.org.conf /etc/nginx/sites-available/
sudo ln -s /etc/nginx/sites-available/sepetak.org.conf /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo certbot --nginx -d sepetak.org -d www.sepetak.org --redirect

sudo cp /var/www/sepetak.org/current/ops/supervisor/sepetak-queue.conf /etc/supervisor/conf.d/
sudo supervisorctl reread && sudo supervisorctl update

sudo cp /var/www/sepetak.org/current/ops/cron/sepetak /etc/cron.d/sepetak
sudo chmod 644 /etc/cron.d/sepetak
sudo systemctl restart cron
```
