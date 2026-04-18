#!/usr/bin/env bash
# =============================================================================
# scripts/deploy.sh — deploy rilis baru ke /var/www/sepetak.org
# -----------------------------------------------------------------------------
# Pola zero-downtime: setiap rilis disimpan di releases/<timestamp>, lalu
# symlink `current/` di-switch dengan `ln -sfn` atomik setelah semua langkah
# build sukses. Rilis lama disimpan (default 5 terakhir).
#
# Jalankan dari dalam checkout source (mis. dari CI atau `git pull` manual):
#   bash scripts/deploy.sh [release-name]
#
# Argumen:
#   release-name   opsional. Default: timestamp YYYYMMDDHHMMSS.
#
# Variabel env yang bisa di-override:
#   APP_ROOT       (default: /var/www/sepetak.org)
#   KEEP_RELEASES  (default: 5)
#   PHP_BIN        (default: php)
#   COMPOSER_BIN   (default: composer)
#   NPM_BIN        (default: npm)
#   SKIP_NPM       (default: 0) set ke 1 jika build asset dilakukan di CI
#   SKIP_MIGRATE   (default: 0) set ke 1 jika migrasi dijalankan manual
#   WEB_USER       (default: www-data) — pemilik storage/bootstrap; artisan
#                  view:cache di bawah dijalankan sebagai user ini jika sudo -n ada,
#                  supaya view terkompilasi bukan milik root (hindari Permission denied).
#
# Asumsi:
#   - Script dijalankan oleh user yang bisa tulis ke ${APP_ROOT}/releases dan
#     /run/php/php*-fpm.sock reload via `sudo systemctl reload php8.3-fpm`.
#   - File env produksi sudah ada di ${APP_ROOT}/shared/.env
# -----------------------------------------------------------------------------
set -euo pipefail

APP_ROOT="${APP_ROOT:-/var/www/sepetak.org}"
KEEP_RELEASES="${KEEP_RELEASES:-5}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"
SKIP_NPM="${SKIP_NPM:-0}"
SKIP_MIGRATE="${SKIP_MIGRATE:-0}"
WEB_USER="${WEB_USER:-www-data}"

RELEASE_NAME="${1:-$(date -u +%Y%m%d%H%M%S)}"
RELEASES_DIR="${APP_ROOT}/releases"
SHARED_DIR="${APP_ROOT}/shared"
CURRENT_SYMLINK="${APP_ROOT}/current"
NEW_RELEASE="${RELEASES_DIR}/${RELEASE_NAME}"

log()  { printf "\033[1;34m[deploy]\033[0m %s\n" "$*"; }
die()  { printf "\033[1;31m[deploy]\033[0m %s\n" "$*" >&2; exit 1; }

[[ -d "${RELEASES_DIR}" ]] || die "Folder ${RELEASES_DIR} tidak ada. Jalankan provision.sh lebih dulu."
[[ -f "${SHARED_DIR}/.env" ]] || die "${SHARED_DIR}/.env tidak ada. Salin dari .env.production.example."

SCRIPT_PATH="${BASH_SOURCE[0]}"
SOURCE_ROOT="$(cd "$(dirname "${SCRIPT_PATH}")/.." && pwd)"

log "Release  : ${RELEASE_NAME}"
log "Source   : ${SOURCE_ROOT}"
log "Target   : ${NEW_RELEASE}"

if [[ "${SOURCE_ROOT}" != "${NEW_RELEASE}" ]]; then
  log "1. Sinkronisasi source → ${NEW_RELEASE}"
  mkdir -p "${NEW_RELEASE}"
  rsync -a --delete \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage' \
    --exclude='public/storage' \
    --exclude='.env' \
    "${SOURCE_ROOT}/" "${NEW_RELEASE}/"
else
  log "1. Source sudah di ${NEW_RELEASE}, skip rsync."
fi

log "2. Link shared .env → release"
ln -sfn "${SHARED_DIR}/.env" "${NEW_RELEASE}/.env"

log "3. Link shared storage → release"
rm -rf "${NEW_RELEASE}/storage"
ln -sfn "${SHARED_DIR}/storage" "${NEW_RELEASE}/storage"

log "4. composer install --no-dev --optimize-autoloader"
( cd "${NEW_RELEASE}" && ${COMPOSER_BIN} install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader )

log "4b. Livewire front-end → public/vendor/livewire (hindari 404 jika /livewire/* tidak ke PHP)"
( cd "${NEW_RELEASE}" && ${PHP_BIN} artisan livewire:publish --assets --no-interaction ) || true

if [[ "${SKIP_NPM}" != "1" ]]; then
  log "5. npm ci && npm run build"
  ( cd "${NEW_RELEASE}" && ${NPM_BIN} ci --no-audit --no-fund && ${NPM_BIN} run build )
else
  log "5. SKIP_NPM=1 — melewati build Vite (asumsi sudah di-bundle di sumber)."
fi

log "6. php artisan storage:link"
( cd "${NEW_RELEASE}" && ${PHP_BIN} artisan storage:link --force --quiet ) || true

if [[ "${SKIP_MIGRATE}" != "1" ]]; then
  log "7. php artisan migrate --force"
  ( cd "${NEW_RELEASE}" && ${PHP_BIN} artisan migrate --force )
else
  log "7. SKIP_MIGRATE=1 — melewati migrasi."
fi

log "8. Cache produksi (config, route, view, event)"
# Pastikan target tulis dimiliki WEB_USER sebelum artisan (penting jika langkah
# sebelumnya pernah di-root dan meninggalkan berkas root di shared storage).
if [[ "$(id -u)" -eq 0 ]] || sudo -n true 2>/dev/null; then
  if [[ "$(id -u)" -eq 0 ]]; then
    chown -R "${WEB_USER}:${WEB_USER}" "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
    chmod -R ug+rwx "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
  else
    sudo -n chown -R "${WEB_USER}:${WEB_USER}" "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
    sudo -n chmod -R ug+rwx "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
  fi
fi

run_artisan_cache() {
  local sub="$1"
  if sudo -n -u "${WEB_USER}" true 2>/dev/null; then
    sudo -n -u "${WEB_USER}" -- bash -c "cd \"${NEW_RELEASE}\" && ${PHP_BIN} artisan ${sub}"
  else
    ( cd "${NEW_RELEASE}" && ${PHP_BIN} artisan "${sub}" )
  fi
}

run_artisan_cache "config:cache"
run_artisan_cache "route:cache"
run_artisan_cache "view:cache"
run_artisan_cache "event:cache" || true

log "9. Ownership + permissions: shared storage + bootstrap/cache (${WEB_USER})"
if [[ "$(id -u)" -eq 0 ]]; then
  chown -R "${WEB_USER}:${WEB_USER}" "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
  chmod -R ug+rwx "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
elif sudo -n true 2>/dev/null; then
  sudo -n chown -R "${WEB_USER}:${WEB_USER}" "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
  sudo -n chmod -R ug+rwx "${NEW_RELEASE}/bootstrap/cache" "${SHARED_DIR}/storage"
else
  log "PERINGATAN: tidak root dan tanpa sudo NOPASSWD — pastikan ${WEB_USER} bisa tulis ke shared/storage dan bootstrap/cache (jalankan: sudo bash scripts/fix-storage-permissions.sh)."
fi

log "10. Symlink atomik: current → ${NEW_RELEASE}"
ln -sfn "${NEW_RELEASE}" "${CURRENT_SYMLINK}"

log "11. Reload services (php-fpm, nginx, queue worker)"
if command -v systemctl >/dev/null 2>&1; then
  sudo -n systemctl reload php8.3-fpm 2>/dev/null || sudo -n systemctl reload php8.4-fpm 2>/dev/null || true
  sudo -n systemctl reload nginx 2>/dev/null || true
fi
if command -v supervisorctl >/dev/null 2>&1; then
  sudo -n supervisorctl restart sepetak-queue:* 2>/dev/null || true
fi

log "12. Rotasi rilis lama (simpan ${KEEP_RELEASES} terakhir)"
cd "${RELEASES_DIR}"
ls -1dt */ 2>/dev/null | tail -n +"$((KEEP_RELEASES + 1))" | xargs -r rm -rf || true

log "DEPLOY SUKSES: ${RELEASE_NAME}"
