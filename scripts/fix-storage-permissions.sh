#!/usr/bin/env bash
# Perbaiki kepemilikan storage/bootstrap agar PHP-FPM bisa menulis cache Blade
# (storage/framework/views), sesi, log. Wajib setelah `view:cache` / deploy di-root,
# gejala: file_put_contents(...storage/framework/views/...): Permission denied.
#
#   sudo bash scripts/fix-storage-permissions.sh
#   APP_ROOT=/var/www/sepetak.org/current sudo -E bash scripts/fix-storage-permissions.sh
#   WEB_USER=nginx sudo -E bash scripts/fix-storage-permissions.sh   # jika FPM bukan www-data
#
set -euo pipefail
ROOT="${APP_ROOT:-$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)}"
WEB_USER="${WEB_USER:-www-data}"

if [[ ! -d "${ROOT}/storage" ]]; then
  echo "Tidak ada ${ROOT}/storage — periksa APP_ROOT." >&2
  exit 1
fi

if ! id -u "${WEB_USER}" &>/dev/null; then
  echo "User sistem '${WEB_USER}' tidak ada — set WEB_USER ke user PHP-FPM Anda." >&2
  exit 1
fi

chown -R "${WEB_USER}:${WEB_USER}" "${ROOT}/storage" "${ROOT}/bootstrap/cache"
chmod -R ug+rwx "${ROOT}/storage" "${ROOT}/bootstrap/cache"
find "${ROOT}/storage" -type d -exec chmod g+s {} \; 2>/dev/null || true
echo "OK: ${ROOT}/storage dan bootstrap/cache → ${WEB_USER}:${WEB_USER}"
