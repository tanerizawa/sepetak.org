#!/usr/bin/env bash
# =============================================================================
# scripts/backup.sh — backup harian database + folder storage
# -----------------------------------------------------------------------------
# Menghasilkan dua file per hari di ${BACKUP_DIR}:
#   db-YYYYMMDD-HHMMSS.sql.gz       (pg_dump terkompresi)
#   storage-YYYYMMDD-HHMMSS.tar.gz  (folder shared/storage, tanpa logs)
#
# Retensi default 14 hari — file lebih lama dari itu dihapus otomatis.
#
# Dipanggil oleh cron harian (lihat ops/cron/sepetak):
#   15 2 * * * deploy  /var/www/sepetak.org/current/scripts/backup.sh >> /var/log/sepetak-backup.log 2>&1
#
# Variabel env yang bisa di-override:
#   APP_ROOT     (default: /var/www/sepetak.org)
#   BACKUP_DIR   (default: ${APP_ROOT}/shared/backups)
#   RETENTION    (default: 14)   hari
#   DB_HOST      (opsional, jika tidak di-set akan di-read dari .env)
#   DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD — idem
#
# Opsional: set RCLONE_REMOTE="myremote:sepetak-backups" untuk rsync ke
#   S3/B2/GDrive via rclone.
# -----------------------------------------------------------------------------
set -euo pipefail

APP_ROOT="${APP_ROOT:-/var/www/sepetak.org}"
RETENTION="${RETENTION:-14}"

# Dukung dua layout:
#   1. Release-based : ${APP_ROOT}/shared/.env  + ${APP_ROOT}/shared/storage
#   2. In-place      : ${APP_ROOT}/.env         + ${APP_ROOT}/storage
if [[ -f "${APP_ROOT}/shared/.env" ]]; then
  ENV_FILE="${APP_ROOT}/shared/.env"
  STORAGE_SRC="shared/storage"
  DEFAULT_BACKUP_DIR="${APP_ROOT}/shared/backups"
elif [[ -f "${APP_ROOT}/.env" ]]; then
  ENV_FILE="${APP_ROOT}/.env"
  STORAGE_SRC="storage"
  DEFAULT_BACKUP_DIR="${APP_ROOT}/storage/backups"
else
  echo "ERROR: .env tidak ditemukan di ${APP_ROOT}/shared/.env maupun ${APP_ROOT}/.env" >&2
  exit 1
fi

BACKUP_DIR="${BACKUP_DIR:-${DEFAULT_BACKUP_DIR}}"

log()  { printf "[%s] %s\n" "$(date --iso-8601=seconds)" "$*"; }
die()  { printf "[%s] ERROR: %s\n" "$(date --iso-8601=seconds)" "$*" >&2; exit 1; }
mkdir -p "${BACKUP_DIR}"
chmod 750 "${BACKUP_DIR}"

# Parse kunci sederhana dari .env bila variabel belum di-set.
read_env() {
  local key="$1"
  grep -E "^${key}=" "${ENV_FILE}" | head -n1 | cut -d'=' -f2- | sed 's/^"//; s/"$//'
}

: "${DB_HOST:=$(read_env DB_HOST)}"
: "${DB_PORT:=$(read_env DB_PORT)}"
: "${DB_DATABASE:=$(read_env DB_DATABASE)}"
: "${DB_USERNAME:=$(read_env DB_USERNAME)}"
: "${DB_PASSWORD:=$(read_env DB_PASSWORD)}"

[[ -n "${DB_DATABASE}" && -n "${DB_USERNAME}" ]] || die "DB_DATABASE/DB_USERNAME tidak terbaca dari .env"

STAMP="$(date -u +%Y%m%d-%H%M%S)"
DB_FILE="${BACKUP_DIR}/db-${STAMP}.sql.gz"
STORAGE_FILE="${BACKUP_DIR}/storage-${STAMP}.tar.gz"

log "1. pg_dump ${DB_DATABASE} → ${DB_FILE}"
PGPASSWORD="${DB_PASSWORD}" pg_dump \
  --host="${DB_HOST:-127.0.0.1}" \
  --port="${DB_PORT:-5432}" \
  --username="${DB_USERNAME}" \
  --no-owner --no-privileges \
  "${DB_DATABASE}" \
  | gzip -9 > "${DB_FILE}"

DB_SIZE=$(du -h "${DB_FILE}" | awk '{print $1}')
log "   Ukuran db backup: ${DB_SIZE}"

log "2. tar ${STORAGE_SRC} → ${STORAGE_FILE}"
if [[ -d "${APP_ROOT}/${STORAGE_SRC}" ]]; then
  tar --warning=no-file-changed \
      --exclude="${STORAGE_SRC}/framework/cache" \
      --exclude="${STORAGE_SRC}/framework/sessions" \
      --exclude="${STORAGE_SRC}/framework/views" \
      --exclude="${STORAGE_SRC}/logs" \
      --exclude="${STORAGE_SRC}/backups" \
      -C "${APP_ROOT}" \
      -czf "${STORAGE_FILE}" "${STORAGE_SRC}" || true
  STG_SIZE=$(du -h "${STORAGE_FILE}" | awk '{print $1}')
  log "   Ukuran storage backup: ${STG_SIZE}"
else
  log "   ${APP_ROOT}/${STORAGE_SRC} tidak ada, skip."
fi

log "3. Rotasi file > ${RETENTION} hari"
find "${BACKUP_DIR}" -maxdepth 1 -type f \( -name 'db-*.sql.gz' -o -name 'storage-*.tar.gz' \) \
  -mtime +"${RETENTION}" -print -delete || true

if [[ -n "${RCLONE_REMOTE:-}" ]] && command -v rclone >/dev/null 2>&1; then
  log "4. Sync ke ${RCLONE_REMOTE}"
  rclone sync "${BACKUP_DIR}" "${RCLONE_REMOTE}" --include 'db-*.sql.gz' --include 'storage-*.tar.gz' --quiet || log "   rclone sync gagal (non-fatal)"
else
  log "4. RCLONE_REMOTE belum di-set, skip off-site sync."
fi

log "BACKUP SUKSES untuk stamp ${STAMP}"
