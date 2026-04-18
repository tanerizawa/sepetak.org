#!/usr/bin/env bash
# Jalankan `php artisan …` sebagai user PHP-FPM (default: www-data) di server produksi,
# supaya storage/framework/views dan bootstrap/cache tidak dimiliki root.
#
#   bash scripts/artisan-web.sh migrate --force
#   APP_ROOT=/var/www/sepetak.org/current WEB_USER=www-data bash scripts/artisan-web.sh queue:restart
#
# Logika:
#   - Jika UID saat ini = UID WEB_USER → jalankan langsung (lokal / container).
#   - Jika `sudo -n -u WEB_USER` tersedia → jalankan lewat sudo.
#   - Jika UID=0 (root) dan tidak bisa sudo ke WEB_USER → gagal (hindari cache/view milik root).
#   - Selain itu → jalankan sebagai user saat ini (dev laptop, CI).
#
set -euo pipefail
ROOT="${APP_ROOT:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
WEB_USER="${WEB_USER:-www-data}"
PHP_BIN="${PHP_BIN:-php}"

die() { printf "%s\n" "$*" >&2; exit 1; }

[[ -f "${ROOT}/artisan" ]] || die "Tidak ada ${ROOT}/artisan — set APP_ROOT ke root aplikasi Laravel."

if [[ "$#" -lt 1 ]]; then
  die "Pemakaian: bash scripts/artisan-web.sh <perintah artisan> [arg ...]
Contoh: bash scripts/artisan-web.sh config:cache"
fi

web_uid="$(id -u "${WEB_USER}" 2>/dev/null || true)"
if [[ -n "${web_uid}" && "$(id -u)" -eq "${web_uid}" ]]; then
  exec ${PHP_BIN} "${ROOT}/artisan" "$@"
fi

if sudo -n -u "${WEB_USER}" true 2>/dev/null; then
  exec sudo -n -u "${WEB_USER}" -- ${PHP_BIN} "${ROOT}/artisan" "$@"
fi

if [[ "$(id -u)" -eq 0 ]] && command -v runuser >/dev/null 2>&1; then
  exec runuser -u "${WEB_USER}" -- ${PHP_BIN} "${ROOT}/artisan" "$@"
fi

if [[ "$(id -u)" -eq 0 ]]; then
  die "Dilarang menjalankan artisan sebagai root tanpa sudo atau runuser ke '${WEB_USER}'.
Contoh: sudo -u ${WEB_USER} ${PHP_BIN} \"${ROOT}/artisan\" $*"
fi

exec ${PHP_BIN} "${ROOT}/artisan" "$@"
