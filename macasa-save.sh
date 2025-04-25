#!/usr/bin/env bash
set -euo pipefail

# === Configuración ===
PROYECTO="${HOME}/macasa-erp"
DB_NAME="erp_ecommerce_db"
DB_USER="${DB_USER:-macasa_user}"
DB_PASS="${DB_PASS:-macasa123}"
SERVICE="mariadb"
EXPORT_DIR="${PROYECTO}/database"
mkdir -p "$EXPORT_DIR"

TIMESTAMP=$(date '+%Y-%m-%d_%H-%M-%S')
EXPORT_FILE="${EXPORT_DIR}/backup-${TIMESTAMP}.sql.gz"
LATEST_FILE="${EXPORT_DIR}/backup-latest.sql.gz"

# === Funciones de UI ===
cyan() { printf "\e[1;36m%s\e[0m\n" "$*"; }
red()  { printf "\e[1;31m%s\e[0m\n" "$*" >&2; }
green() { printf "\e[1;32m%s\e[0m\n" "$*"; }
die()  { red "✖ $*"; exit 1; }

# === Validación de entorno ===
cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

cyan "▶ Exportando base de datos → ${EXPORT_FILE}"

if ! docker compose ps --services --filter "status=running" | grep -qx "$SERVICE"; then
  die "El servicio '$SERVICE' no está corriendo (via docker compose)"
fi

# Dump + gzip
if ! docker compose exec -T "$SERVICE" \
        mysqldump -u"$DB_USER" -p"$DB_PASS" --quick "$DB_NAME" \
        | gzip > "$EXPORT_FILE"; then
  rm -f "$EXPORT_FILE"; die "mysqldump falló"
fi

[ -s "$EXPORT_FILE" ] || { rm -f "$EXPORT_FILE"; die "Dump vacío"; }

ln -fs "$(basename "$EXPORT_FILE")" "$LATEST_FILE"
green "✔ Backup creado: $(basename "$EXPORT_FILE") ($(du -h "$EXPORT_FILE" | cut -f1))"

# Limpiar antiguos
ls -1t "$EXPORT_DIR"/backup-*.sql.gz | tail -n +4 | xargs -r rm -v

# === Git ===
cyan "💾 Guardando cambios en Git..."
if git diff --quiet && git diff --cached --quiet; then
  green "😎 No hay cambios que guardar. Código limpio."
  exit 0
fi

MSG=${1:-"Auto-commit: $(date '+%Y-%m-%d %H:%M:%S')"}
git add -A
git commit -m "$MSG"
git push origin dev && green "✔ Cambios guardados y enviados al repo"
