#!/usr/bin/env bash
set -euo pipefail

# === CONFIG ===
PROYECTO="${HOME}/macasa-erp"
SERVICE_DB="mariadb"
RED_EXTERNA="macasa-red-docker"

# === UI helpers ===
cyan()  { printf "\e[1;36m▶ %s\e[0m\n" "$*"; }
green() { printf "\e[1;32m✔ %s\e[0m\n" "$*"; }
red()   { printf "\e[1;31m✖ %s\e[0m\n" "$*" >&2; }
die()   { red "$*"; exit 1; }

cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

############################################################################
cyan "💥  [macasa-reset] Desatando la GENKIDAMA sobre Docker…"

# 1. Detener contenedores
cyan "🛑 Deteniendo contenedores del proyecto…"
docker compose down --remove-orphans || true

cyan "🧊 Cerrando aplicaciones de Windows…"
if grep -qi "microsoft" /proc/version; then
  powershell.exe -Command "Start-Job { Stop-Process -Name 'Docker Desktop','Code','GitHubDesktop' -Force -ErrorAction SilentlyContinue }" 2>/dev/null || true
  sleep 1
fi

# 2. Limpiar contenedores, volúmenes y redes
cyan "🗑️  Eliminando contenedores sueltos…"
docker ps -aq | xargs -r docker rm -f || true

cyan "🧹 Borrando volumen de MariaDB y redes huérfanas…"
docker volume rm -f mariadb_data 2>/dev/null || true
docker volume prune -f
docker network prune -f

# 3. Crear red externa si no existe
cyan "🌐 Verificando red externa '${RED_EXTERNA}'…"
docker network inspect "$RED_EXTERNA" &>/dev/null || {
  docker network create "$RED_EXTERNA"
  green "Red '${RED_EXTERNA}' creada."
}

# 4. Levantar el stack de nuevo
cyan "⌛ Esperando a que Docker esté listo…"
retry=0
until docker info &>/dev/null; do
  sleep 1
  ((retry++))
  if [ "$retry" -gt 120 ]; then
    die "Docker no está listo después de 120 segundos."
  fi
done
green "🐋 Docker Desktop está listo."


cyan "🔨 Reconstruyendo contenedores…"
docker compose up -d --build

# 5. Esperar a MariaDB
cyan "⌛ Esperando a que MariaDB responda…"
until docker compose exec -T "$SERVICE_DB" \
        mysqladmin ping -p"macasa123" --silent &>/dev/null; do
  sleep 2
done
green "MariaDB lista."

# 6. Restaurar backup-main.sql.gz si existe (a menos que --menos-la-base esté activo)
if [[ "${1:-}" != "--menos-la-base" && -s "$PROYECTO/database/backup-main.sql.gz" ]]; then
  cyan "🧠 Restaurando backup-main.sql.gz…"

  TABLAS=$(docker compose exec -T "$SERVICE_DB" \
    mysql -N -s -u"macasa_user" -p"macasa123" \
    -e "SELECT table_name FROM information_schema.tables WHERE table_schema = 'erp_ecommerce_db';" \
    | tr '
' ',' | sed 's/,\$//')

  if [[ -z "$TABLAS" || "$TABLAS" =~ ^,*$ ]]; then
    red "❌ No se encontraron tablas válidas para eliminar."
  else
    SQL="SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS $TABLAS; SET FOREIGN_KEY_CHECKS = 1;"
    echo "🩨 Eliminando tablas existentes: $TABLAS"
    echo "$SQL" | docker compose exec -T "$SERVICE_DB" \
      mysql -umacasa_user -pmacasa123 erp_ecommerce_db
    green "✔ Tablas eliminadas."
  fi

  zcat "$PROYECTO/database/backup-main.sql.gz" | docker compose exec -T "$SERVICE_DB" \
    mysql -umacasa_user -pmacasa123 erp_ecommerce_db
  green "✔ Base restaurada."
elif [[ "${1:-}" == "--menos-la-base" ]]; then
  cyan "⏭️  Restauración de base de datos omitida (--menos-la-base)."
else
  red "⚠️ No se encontró backup-main.sql.gz para restaurar la base."
fi
elif [[ "${1:-}" == "--menos-la-base" ]]; then
  cyan "⏭️  Restauración de base de datos omitida (--menos-la-base)."
else
  red "⚠️ No se encontró backup-main.sql.gz para restaurar la base."
fi

# 6. Usuario ancla
cyan "🧙 Creando usuario ancla (si falta)…"
docker compose exec -T erp \
  php artisan tinker --execute \
  "App\\Models\\Usuario::firstOrCreate(\n      ['email'=>'sistemas@macasahs.com.mx'],\n      ['username'=>'sistemas','password'=>bcrypt('Macasa2019\$'),'es_admin'=>1]\n  );"

# 7. Notificación de integridad final
if [[ "${1:-}" == "--menos-la-base" ]]; then
  echo -e "\e[1;33m⚠️  La base de datos NO fue restaurada. Continúas con los datos existentes.\e[0m"
fi

# 7. Migraciones (comentadas por ahora)
# cyan "🔧 Ejecutando migraciones…"
# docker compose exec -T erp php artisan migrate --force --no-interaction && \
#   green "Migraciones al día."
