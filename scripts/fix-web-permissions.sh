#!/usr/bin/env bash
# Jalankan setelah deploy/cache artisan sebagai root agar PHP-FPM (www-data)
# dapat menulis ulang compiled Blade di storage/framework/views.
# Tanpa ini, request dapat HTTP 500: file_put_contents(...views/...): Permission denied
set -euo pipefail
APP_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
WEB_USER="${WEB_USER:-www-data}"
WEB_GROUP="${WEB_GROUP:-www-data}"

echo "==> ${APP_ROOT}: chown storage + bootstrap/cache -> ${WEB_USER}:${WEB_GROUP}"
sudo chown -R "${WEB_USER}:${WEB_GROUP}" "${APP_ROOT}/storage" "${APP_ROOT}/bootstrap/cache"
sudo chmod -R g+rwX "${APP_ROOT}/storage" "${APP_ROOT}/bootstrap/cache"
sudo find "${APP_ROOT}/storage" "${APP_ROOT}/bootstrap/cache" -type d -exec chmod g+s {} +

echo "==> Rebuild view cache sebagai ${WEB_USER}"
sudo -u "${WEB_USER}" php "${APP_ROOT}/artisan" view:clear
sudo -u "${WEB_USER}" php "${APP_ROOT}/artisan" view:cache

echo "Selesai."
