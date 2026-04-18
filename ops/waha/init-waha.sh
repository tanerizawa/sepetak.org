#!/usr/bin/env bash
# Menjalankan utilitas resmi WAHA untuk menghasilkan berkas .env di direktori ini.
# Lihat: https://waha.devlike.pro/docs/overview/quick-start/

set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

echo "Menjalankan: docker run ... devlikeapro/waha init-waha /app/env"
echo "Volume terpasang: ${DIR} -> /app/env (berkas .env akan dibuat di ops/waha/)"
echo ""

docker run --rm -v "${DIR}:/app/env" "devlikeapro/waha:latest" init-waha /app/env

echo ""
echo "Selesai. Periksa berkas .env (WAHA_API_KEY, dashboard)."
echo "Lanjutkan dengan: docker compose up -d"
