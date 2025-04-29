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
cyan()  { printf "\e[1;36m%s\e[0m\n" "$*"; }
red()   { printf "\e[1;31m%s\e[0m\n" "$*" >&2; }
green() { printf "\e[1;32m%s\e[0m\n" "$*"; }
die()   { red "✖ $*"; exit 1; }

# === Validación de entorno ===
cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

cyan "▶ Exportando base de datos → ${EXPORT_FILE}"

if ! docker compose ps --services --filter "status=running" | grep -qx "$SERVICE"; then
  die "El servicio '$SERVICE' no está corriendo (via docker compose)"
fi

# ------------------------------------------------------------------
# 1. Verificar que la base esté “sana” antes de respaldar
TABLES=$(docker compose exec -T "$SERVICE" \
  mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
  -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';")

MIN_TABLES=10  # ajusta según tu esquema mínimo aceptable
if [[ "$TABLES" -lt "$MIN_TABLES" ]]; then
  red "❌ Solo $TABLES tablas en ${DB_NAME}. Cancelando backup para no sobre-escribir uno bueno."
  exit 1
fi
cyan "La base contiene $TABLES tablas — procede el dump."

# ------------------------------------------------------------------
# 2. Dump + compresión (sin LOCK TABLES)
if ! docker compose exec -T "$SERVICE" \
  mysqldump \
    --skip-lock-tables \
    --single-transaction \
    --quick \
    -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" \
    | gzip > "$EXPORT_FILE"; then
  rm -f "$EXPORT_FILE"
  die "mysqldump falló"
fi

# 3. Validar que el archivo exista y pese >0
[ -s "$EXPORT_FILE" ] || { rm -f "$EXPORT_FILE"; die "Dump vacío"; }

# 4. Enlace simbólico a backup-latest y mensaje
rm -f "$LATEST_FILE"
ln -s  "$(basename "$EXPORT_FILE")" "$LATEST_FILE"
green "✔ Backup creado: $(basename "$EXPORT_FILE") ($(du -h "$EXPORT_FILE" | cut -f1))"

# 5. Rotar backups (mantener los 3 más recientes)
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
git push -u origin dev && green "✔ Cambios guardados y enviados al repo"
du -h "$EXPORT_FILE"
