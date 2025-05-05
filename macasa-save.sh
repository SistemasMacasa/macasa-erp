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

BACKUP_FILE="$EXPORT_DIR/backup-main.sql.gz"

# === Funciones de UI ===
cyan()  { printf "\e[1;36m%s\e[0m\n" "$*"; }
red()   { printf "\e[1;31m%s\e[0m\n" "$*" >&2; }
green() { printf "\e[1;32m%s\e[0m\n" "$*"; }
die()   { red "✖ $*"; exit 1; }

# === Validación de entorno ===
cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

cyan "▶ Exportando base de datos a: $(basename "$BACKUP_FILE")"

if ! docker compose ps --services --filter "status=running" | grep -qx "$SERVICE"; then
  die "El servicio '$SERVICE' no está corriendo (via docker compose)"
fi

TABLES=$(docker compose exec -T "$SERVICE" \
  mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
  -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';")

MIN_TABLES=10
if [[ "$TABLES" -lt "$MIN_TABLES" ]]; then
  red "❌ Solo $TABLES tablas en ${DB_NAME}. Cancelando backup."
  exit 1
fi
cyan "La base contiene $TABLES tablas — procede el dump."

if ! docker compose exec -T "$SERVICE" \
  mysqldump --skip-lock-tables --single-transaction --quick \
  -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$BACKUP_FILE"; then
  rm -f "$BACKUP_FILE"; die "mysqldump falló"
fi

[ -s "$BACKUP_FILE" ] || { rm -f "$BACKUP_FILE"; die "Dump vacío"; }
green "✔ Backup generado: $(basename "$BACKUP_FILE") ($(du -h "$BACKUP_FILE" | cut -f1))"

# === Git ===
cyan "💾 Guardando cambios en Git..."
git add "$BACKUP_FILE"
git add -A                # ← ahora incluye archivos NUEVOS además de los modificados

if git diff --cached --quiet; then
  green "😎 No hay cambios que guardar. Código limpio."
  exit 0
fi

MSG=${1:-"Auto-commit: $(date '+%Y-%m-%d %H:%M:%S')"}
git commit -m "$MSG"
git push -u origin dev && green "✔ Cambios guardados y enviados al repo"
du -h "$BACKUP_FILE"
