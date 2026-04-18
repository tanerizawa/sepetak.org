#!/usr/bin/env bash
# =============================================================================
# scripts/provision.sh — provisioning server sekali-pakai untuk sepetak.org
# -----------------------------------------------------------------------------
# Target OS : Ubuntu 22.04 LTS (server baru, akses sudo).
# Idempotent: aman dijalankan ulang; setiap langkah mengecek state sebelum
#             melakukan mutasi.
#
# Jalankan dari root server target:
#   sudo bash provision.sh
#
# Variabel yang bisa di-override via env:
#   DEPLOY_USER   (default: deploy)     user OS non-root untuk deploy
#   APP_ROOT      (default: /var/www/sepetak.org)
#   DB_NAME       (default: sepetak)
#   DB_USER       (default: sepetak)
#   DB_PASS       (default: di-generate random)
#   PHP_VERSION   (default: 8.3)
#   POSTGRES_VER  (default: 17)
#   NODE_VERSION  (default: 20)
#
# Output terakhir akan menulis summary ke /root/sepetak-provision-summary.txt
# -----------------------------------------------------------------------------
set -euo pipefail

DEPLOY_USER="${DEPLOY_USER:-deploy}"
APP_ROOT="${APP_ROOT:-/var/www/sepetak.org}"
DB_NAME="${DB_NAME:-sepetak}"
DB_USER="${DB_USER:-sepetak}"
DB_PASS="${DB_PASS:-$(tr -dc 'A-Za-z0-9' </dev/urandom | head -c 32)}"
PHP_VERSION="${PHP_VERSION:-8.3}"
POSTGRES_VER="${POSTGRES_VER:-17}"
NODE_VERSION="${NODE_VERSION:-20}"
SUMMARY_FILE="/root/sepetak-provision-summary.txt"

log()  { printf "\033[1;34m[provision]\033[0m %s\n" "$*"; }
warn() { printf "\033[1;33m[provision]\033[0m %s\n" "$*" >&2; }
die()  { printf "\033[1;31m[provision]\033[0m %s\n" "$*" >&2; exit 1; }

[[ $EUID -eq 0 ]] || die "Harus dijalankan sebagai root. Gunakan sudo."

export DEBIAN_FRONTEND=noninteractive

# -----------------------------------------------------------------------------
log "1. Update apt + paket dasar"
apt-get update -y
apt-get install -y --no-install-recommends \
  ca-certificates curl gnupg lsb-release software-properties-common \
  ufw fail2ban supervisor unzip zip git

# -----------------------------------------------------------------------------
log "2. Tambah repo PHP (ondrej) + PostgreSQL PGDG + NodeSource"
if ! apt-cache policy | grep -q "ondrej/php"; then
  add-apt-repository -y ppa:ondrej/php
fi

if [[ ! -f /etc/apt/sources.list.d/pgdg.list ]]; then
  install -d /usr/share/keyrings
  curl -fsSL https://www.postgresql.org/media/keys/ACCC4CF8.asc \
    | gpg --dearmor -o /usr/share/keyrings/postgresql.gpg
  echo "deb [signed-by=/usr/share/keyrings/postgresql.gpg] http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" \
    > /etc/apt/sources.list.d/pgdg.list
fi

if [[ ! -f /etc/apt/sources.list.d/nodesource.list ]]; then
  install -d /usr/share/keyrings
  curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
    | gpg --dearmor -o /usr/share/keyrings/nodesource.gpg
  echo "deb [signed-by=/usr/share/keyrings/nodesource.gpg] https://deb.nodesource.com/node_${NODE_VERSION}.x nodistro main" \
    > /etc/apt/sources.list.d/nodesource.list
fi

apt-get update -y

# -----------------------------------------------------------------------------
log "3. Install PHP ${PHP_VERSION} + extension yang dibutuhkan Laravel + Filament"
apt-get install -y --no-install-recommends \
  php${PHP_VERSION} php${PHP_VERSION}-fpm php${PHP_VERSION}-cli \
  php${PHP_VERSION}-bcmath php${PHP_VERSION}-curl php${PHP_VERSION}-gd \
  php${PHP_VERSION}-intl php${PHP_VERSION}-mbstring php${PHP_VERSION}-opcache \
  php${PHP_VERSION}-pgsql php${PHP_VERSION}-redis php${PHP_VERSION}-xml \
  php${PHP_VERSION}-zip php${PHP_VERSION}-readline

# -----------------------------------------------------------------------------
log "4. Install PostgreSQL ${POSTGRES_VER} + Redis + Nginx + Node ${NODE_VERSION}"
apt-get install -y --no-install-recommends \
  postgresql-${POSTGRES_VER} postgresql-client-${POSTGRES_VER} \
  redis-server nginx nodejs

systemctl enable --now postgresql redis-server nginx supervisor

# -----------------------------------------------------------------------------
log "5. Install Composer (global /usr/local/bin/composer)"
if ! command -v composer >/dev/null 2>&1; then
  EXPECTED_SIG="$(curl -fsSL https://composer.github.io/installer.sig)"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  ACTUAL_SIG="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
  [[ "$EXPECTED_SIG" == "$ACTUAL_SIG" ]] || die "Composer signature mismatch"
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet
  rm composer-setup.php
fi

# -----------------------------------------------------------------------------
log "6. User deploy (${DEPLOY_USER}) + struktur folder ${APP_ROOT}"
if ! id -u "${DEPLOY_USER}" >/dev/null 2>&1; then
  adduser --disabled-password --gecos "" "${DEPLOY_USER}"
fi
usermod -aG www-data "${DEPLOY_USER}"

install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/releases"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/app"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/app/public"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/framework"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/framework/cache"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/framework/sessions"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/framework/views"
install -d -o "${DEPLOY_USER}" -g www-data -m 2775 "${APP_ROOT}/shared/storage/logs"

if [[ ! -f "${APP_ROOT}/shared/.env" ]]; then
  warn "File ${APP_ROOT}/shared/.env BELUM ADA. Buat dari .env.production.example setelah clone repo."
fi

# -----------------------------------------------------------------------------
log "7. Postgres: buat role '${DB_USER}' dan database '${DB_NAME}' bila belum ada"
sudo -u postgres psql -tAc "SELECT 1 FROM pg_roles WHERE rolname='${DB_USER}'" | grep -q 1 \
  || sudo -u postgres psql -c "CREATE ROLE ${DB_USER} LOGIN PASSWORD '${DB_PASS}';"

sudo -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='${DB_NAME}'" | grep -q 1 \
  || sudo -u postgres createdb -O "${DB_USER}" "${DB_NAME}"

sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE ${DB_NAME} TO ${DB_USER};" >/dev/null

# -----------------------------------------------------------------------------
log "8. UFW firewall + Fail2ban"
ufw allow OpenSSH || true
ufw allow 'Nginx Full' || true
yes | ufw enable || true
systemctl enable --now fail2ban

# -----------------------------------------------------------------------------
log "9. Tuning opcache produksi"
OPCACHE_INI="/etc/php/${PHP_VERSION}/fpm/conf.d/99-sepetak-opcache.ini"
cat >"${OPCACHE_INI}" <<'EOF'
opcache.enable=1
opcache.memory_consumption=192
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
opcache.save_comments=1
EOF
systemctl reload "php${PHP_VERSION}-fpm" || true

# -----------------------------------------------------------------------------
log "10. Nulis summary ke ${SUMMARY_FILE}"
{
  echo "SEPETAK provisioning selesai pada $(date --iso-8601=seconds)"
  echo "=============================================================="
  echo "DEPLOY_USER      : ${DEPLOY_USER}"
  echo "APP_ROOT         : ${APP_ROOT}"
  echo "PHP              : ${PHP_VERSION} (fpm + cli)"
  echo "Postgres         : ${POSTGRES_VER}"
  echo "Node             : ${NODE_VERSION}"
  echo ""
  echo "Database (simpan aman!):"
  echo "  DB_HOST=127.0.0.1"
  echo "  DB_PORT=5432"
  echo "  DB_DATABASE=${DB_NAME}"
  echo "  DB_USERNAME=${DB_USER}"
  echo "  DB_PASSWORD=${DB_PASS}"
  echo ""
  echo "Langkah lanjutan (jalankan sebagai ${DEPLOY_USER}):"
  echo "  1. su - ${DEPLOY_USER}"
  echo "  2. git clone <repo-url> ${APP_ROOT}/releases/initial"
  echo "  3. cd ${APP_ROOT}/releases/initial && cp .env.production.example ${APP_ROOT}/shared/.env"
  echo "  4. Edit ${APP_ROOT}/shared/.env (isi DB_* di atas, APP_KEY, MAIL_*, REDIS_*)"
  echo "  5. ln -s ${APP_ROOT}/shared/.env ${APP_ROOT}/releases/initial/.env"
  echo "  6. bash ${APP_ROOT}/releases/initial/scripts/deploy.sh initial"
  echo ""
  echo "Nginx + SSL:"
  echo "  sudo cp ${APP_ROOT}/current/ops/nginx/sepetak.org.conf /etc/nginx/sites-available/"
  echo "  sudo ln -s /etc/nginx/sites-available/sepetak.org.conf /etc/nginx/sites-enabled/"
  echo "  sudo nginx -t && sudo systemctl reload nginx"
  echo "  sudo certbot --nginx -d sepetak.org -d www.sepetak.org --redirect"
  echo ""
  echo "Supervisor queue worker:"
  echo "  sudo cp ${APP_ROOT}/current/ops/supervisor/sepetak-queue.conf /etc/supervisor/conf.d/"
  echo "  sudo supervisorctl reread && sudo supervisorctl update"
  echo ""
  echo "Backup harian:"
  echo "  sudo cp ${APP_ROOT}/current/ops/cron/sepetak /etc/cron.d/sepetak"
} > "${SUMMARY_FILE}"
chmod 600 "${SUMMARY_FILE}"

log "Selesai. Baca ringkasan di ${SUMMARY_FILE}"
