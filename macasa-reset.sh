#!/usr/bin/env bash
set -euo pipefail

# === CONFIG ===============================================================
PROYECTO="${HOME}/macasa-erp"
SERVICE_DB="mariadb"
DB_NAME="erp_ecommerce_db"
DB_USER="${DB_USER:-macasa_user}"
DB_PASS="${DB_PASS:-macasa123}"
RED_EXTERNA="macasa-red-docker"
BACKUP_DIR="${PROYECTO}/database"
BACKUP_FILE="${BACKUP_DIR}/backup-latest.sql.gz"

# === UI helpers ===========================================================
cyan()  { printf "\e[1;36m▶ %s\e[0m\n" "$*"; }
green() { printf "\e[1;32m✔ %s\e[0m\n" "$*"; }
red()   { printf "\e[1;31m✖ %s\e[0m\n" "$*" >&2; }
die()   { red "$*"; exit 1; }

cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

############################################################################
cyan "💥  [macasa-reset] Desatando la GENKIDAMA sobre Docker…"

### 1. Derribo controlado de stack
cyan "🛑 Deteniendo contenedores del proyecto…"
docker compose down --remove-orphans || true

cyan "🗑️  Eliminando contenedores sueltos…"
docker ps -aq | xargs -r docker rm -f || true

### 2. Limpiar volúmenes y redes huérfanas
cyan "🧹 Borrando volumen de MariaDB…"
docker volume rm -f mariadb_data 2>/dev/null || true
docker volume prune -f
docker network prune -f

### 3. Garantizar que la red externa exista
cyan "🌐 Verificando red externa '${RED_EXTERNA}'…"
docker network inspect "$RED_EXTERNA" &>/dev/null || {
  docker network create "$RED_EXTERNA"
  green "Red '${RED_EXTERNA}' creada."
}

### 4. Levantar el stack de cero
cyan "🔨 Reconstruyendo contenedores…"
docker compose up -d --build

### 5. Esperar a MariaDB
cyan "⌛ Esperando a que '$DB_USER' pueda iniciar sesión…"
until docker compose exec -T "$SERVICE_DB" \
        mysql -u"$DB_USER" -p"$DB_PASS" -e "SELECT 1" &>/dev/null; do
  sleep 2
done
green "MariaDB lista y $DB_USER activo."

### 6. Restaurar backup (si existe y la BD está vacía)
TABLAS=$(docker compose exec -T "$SERVICE_DB" \
  mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
  -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME';")

if [ "$TABLAS" -eq 0 ] && [ -s "$BACKUP_FILE" ]; then
  cyan "🧠 Restaurando backup-latest.sql.gz…"
  gunzip -c "$BACKUP_FILE" | \
    docker compose exec -T "$SERVICE_DB" \
      mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
  green "Base restaurada."
else
  cyan "⚠️  Sin backup válido o la base ya tiene tablas."
fi

### 7. Usuario “ancla” de emergencia
cyan "🧙 Creando usuario ancla (si falta)…"
docker compose exec -T erp \
  php artisan tinker --execute \
  "App\\Models\\Usuario::firstOrCreate(
      ['email'=>'sistemas@macasahs.com.mx'],
      ['name'=>'ancla','password'=>bcrypt('Macasa2019$'),'es_admin'=>1]
  );"

# 1) Ejecutar migraciones faltantes
cyan "🔧 Sincronizando esquema (migraciones)…"
if docker compose exec -T erp php artisan migrate --force --no-interaction; then
  green "Migraciones al día."
else
  warn "Migraciones con error; revisa manualmente."
fi

# 2) (Opcional) seed inicial de tablas críticas
# docker compose exec -T erp php artisan db:seed --class=InitialSeeder

############################################################################
green "🌅 GENKIDAMA completada: entorno limpio, base restaurada y listo en http://localhost"
