#!/usr/bin/env bash
set -euo pipefail    # aborta ante cualquier fallo inesperado

# === CONFIG ===
PROYECTO="${HOME}/macasa-erp"
DB_NAME="erp_ecommerce_db"
DB_USER="${DB_USER:-macasa_user}"
DB_PASS="${DB_PASS:-macasa123}"
SERVICE_DB="mariadb"
EXPORT_DIR="${PROYECTO}/database"
mkdir -p "$EXPORT_DIR"

STAMP=$(date '+%Y-%m-%d_%H-%M-%S')
DUMP_FILE="${EXPORT_DIR}/backup-${STAMP}.sql.gz"
LATEST="${EXPORT_DIR}/backup-latest.sql.gz"

# === UI helpers ===
c()  { printf "\e[1;36m%s\e[0m\n" "$*"; }  # cyan
g()  { printf "\e[1;32m%s\e[0m\n" "$*"; }  # green
r()  { printf "\e[1;31m%s\e[0m\n" "$*" >&2; }
die(){ r "✖ $*"; exit 1; }

cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

# === 1. Backup antes de apagar ===
c "💾 [macasa-end] Respaldando base de datos…"

# ¿Está corriendo el servicio?
if ! docker compose ps --services --filter "status=running" | grep -qx "$SERVICE_DB"; then
  r "⚠️  El servicio $SERVICE_DB no está activo; salto respaldo."
else
  if docker compose exec -T "$SERVICE_DB" \
        mysqldump -u"$DB_USER" -p"$DB_PASS" --quick "$DB_NAME" \
        | gzip > "$DUMP_FILE"; then
      [ -s "$DUMP_FILE" ] || die "Dump vacío"
      ln -fs "$(basename "$DUMP_FILE")" "$LATEST"
      g "✔ Backup creado: $(basename "$DUMP_FILE")  ($(du -h "$DUMP_FILE" | cut -f1))"
  else
      rm -f "$DUMP_FILE"; die "mysqldump falló"
  fi

  # Rotación (mantener 3 más recientes)
  ls -1t "$EXPORT_DIR"/backup-*.sql.gz | tail -n +4 | xargs -r rm -v
fi

# === 2. Parar contenedores ===
c "🧼 Deteniendo contenedores Docker…"
docker compose down

# === 3. Cerrar apps Windows (ignorar si no existen) ===
c "🧊 Cerrando aplicaciones de Windows…"
if grep -qi "microsoft" /proc/version; then
  powershell.exe -Command "Stop-Process -Name 'Docker Desktop','Code','GitHubDesktop' -Force -ErrorAction SilentlyContinue"
fi

# === 4. Despedida legendaria ===
g "🚀 Proyecto a salvo, contenedores apagados."
echo -e "\e[1;35m💌 ¡Buen trabajo, Zerezo! 🧠⚡ Disfruta tu merecido descanso.\e[0m"
