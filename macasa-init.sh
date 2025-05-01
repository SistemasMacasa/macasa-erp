#!/usr/bin/env bash
set -euo pipefail

PROYECTO="${HOME}/macasa-erp"
DB_NAME="erp_ecommerce_db"
DB_USER="${DB_USER:-macasa_user}"
DB_PASS="${DB_PASS:-macasa123}"
SERVICE_DB="mariadb"
EXPORT_DIR="$PROYECTO/database"

FORCE_RESTORE=false
if [[ "${1:-}" == "--force-db" ]]; then
  FORCE_RESTORE=true
fi

cd "$PROYECTO" || { echo "✖ No se pudo entrar a $PROYECTO"; exit 1; }

# === Helpers UI ===
cyan()  { printf "\e[1;36m%s\e[0m\n" "$*"; }
green() { printf "\e[1;32m%s\e[0m\n" "$*"; }
red()   { printf "\e[1;31m%s\e[0m\n" "$*"; }
die()   { red "✖ $*"; exit 1; }

# === Git Pull ===
cyan "📥 Haciendo git pull desde 'dev'..."
if git pull origin dev 2>&1 | tee /tmp/gitlog | grep -q "Already up to date"; then
  green "✅ El proyecto ya está actualizado."
else
  green "📦 Cambios aplicados desde la rama 'dev'."
fi
rm -f /tmp/gitlog

# === Docker Desktop (solo si estás en WSL) ===
cyan "🖥️ Verificando Docker Desktop..."

if grep -qi "microsoft" /proc/version; then
  if ! pgrep -f "Docker Desktop.exe" > /dev/null; then
    echo "🫿 Iniciando Docker Desktop..."
    powershell.exe -Command "Start-Process 'C:\Program Files\Docker\Docker\Docker Desktop.exe'" 2>/dev/null || echo "⚠️ No se pudo iniciar Docker Desktop."

    # Esperar a que Docker esté listo ANTES de continuar
    echo "⌛ Esperando a que Docker Desktop esté listo..."
    retry=0
    until docker info &>/dev/null; do
      sleep 1 || true
      ((retry++)) || true
      if [ "$retry" -gt 120 ]; then
        die "Docker Desktop no inició después de 120 segundos."
      fi
    done
    green "✅ Docker Desktop está listo."
  else
    green "🐋 Docker Desktop ya estaba corriendo."
  fi
fi

# === Abrir VS Code ===
cyan "💻 Abriendo VS Code en $PROYECTO..."
if command -v code &> /dev/null; then
  code .
else
  red "⚠️ VS Code no está disponible como 'code'. ¿Está instalado en WSL?"
fi

# === Docker Compose Up ===
cyan "📄 Limpiando contenedores antiguos..."
docker compose down --remove-orphans

cyan "🐳 Levantando contenedores Docker..."
docker compose up -d --build

# === Restaurar base si corresponde ===
cyan "💄 Verificando si la base de datos necesita restaurarse…"

DB_READY() {
  docker compose exec -T "$SERVICE_DB" \
    mysqladmin ping -p"$DB_PASS" --silent &>/dev/null
}

until DB_READY; do
  echo "⌛ Esperando a MariaDB…"
  sleep 2
done
green "✅ MariaDB responde."

TABLE_COUNT=$(docker compose exec -T "$SERVICE_DB" \
  mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
  -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME';")

if $FORCE_RESTORE && [ -s "$EXPORT_DIR/backup-latest.sql.gz" ]; then
  cyan "⚠️  Modo forzado activado: eliminando todas las tablas existentes..."

  TABLAS=$(docker compose exec -T "$SERVICE_DB" \
    mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
    -e "SELECT table_name FROM information_schema.tables WHERE table_schema = '$DB_NAME';" | tr '\n' ',' | sed 's/,\$//')

  if [[ -z "$TABLAS" ]]; then
    red "❌ No se encontraron tablas para eliminar."
  else
    SQL="SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS $TABLAS; SET FOREIGN_KEY_CHECKS = 1;"
    echo "🩨 Eliminando tablas: $TABLAS"
    echo "$SQL" | docker compose exec -T "$SERVICE_DB" \
      mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
    green "✔ Tablas eliminadas."
  fi

  cyan "🔄 Restaurando backup-latest.sql.gz…"
  zcat "$EXPORT_DIR/backup-latest.sql.gz" | docker compose exec -i "$SERVICE_DB" \
    mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
  green "✅ Restauración completada."

elif [ "$TABLE_COUNT" -eq 0 ] && [ -s "$EXPORT_DIR/backup-latest.sql.gz" ]; then
  cyan "🔄 Restaurando backup-latest.sql.gz…"
  zcat "$EXPORT_DIR/backup-latest.sql.gz" | docker compose exec -i "$SERVICE_DB" \
    mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
  green "✅ Restauración completada."
else
  echo "📂 La base ya contiene tablas o no existe backup válido; se omite la restauración."
fi

green "✅ [macasa-init] Entorno iniciado exitosamente. ¡Hora de programar! 😎"
