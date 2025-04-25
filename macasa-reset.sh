#!/usr/bin/env bash
set -euo pipefail

# === CONFIG ===============================================================
PROYECTO="${HOME}/macasa-erp"
SERVICE_DB="mariadb"                 # nombre del servicio en docker-compose
DB_NAME="erp_ecommerce_db"
DB_USER="${DB_USER:-macasa_user}"
DB_PASS="${DB_PASS:-macasa123}"
RED_EXTERNA="macasa-red-docker"
BACKUP_DIR="${PROYECTO}/database"
BACKUP_FILE="${BACKUP_DIR}/backup-latest.sql.gz"

# === UI helpers ===========================================================
c()  { printf "\e[1;36m%s\e[0m\n" "▶ $*"; }     # cyan
g()  { printf "\e[1;32m%s\e[0m\n" "✔ $*"; }     # green
r()  { printf "\e[1;31m%s\e[0m\n" "✖ $*" >&2; } # red
die(){ r "$*"; exit 1; }

cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

############################################################################
c "💥  [macasa-reset] Desatando la GENKIDAMA sobre Docker…"

### 1. Derribo controlado de stack
c "🛑 Deteniendo contenedores del proyecto…"
docker compose down --remove-orphans || true

c "🗑️  Eliminando contenedores sueltos…"
docker ps -aq | xargs -r docker rm -f

### 2. Limpiar volúmenes y redes huérfanas
c "🧹 Borrando volumen de MariaDB…"
docker volume rm -f mariadb_data 2>/dev/null || true
docker volume prune -f
docker network prune -f

### 3. Garantizar que la red externa exista
c "🌐 Verificando red externa '${RED_EXTERNA}'…"
docker network inspect "$RED_EXTERNA" &>/dev/null || {
  docker network create "$RED_EXTERNA"
  g "Red '${RED_EXTERNA}' creada."
}

### 4. Levantar el stack de cero
c "🔨 Reconstruyendo contenedores…"
docker compose up -d --build

### 5. Esperar a MariaDB
c "⌛ Esperando a MariaDB…"
until docker compose exec -T "$SERVICE_DB" \
        mysqladmin ping -p"$DB_PASS" --silent &>/dev/null; do
  sleep 2
done
g "MariaDB lista."

### 6. Restaurar backup (si existe y la BD está vacía)
TABLAS=$(docker compose exec -T "$SERVICE_DB" \
  mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
  -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME';")

if [ "$TABLAS" -eq 0 ] && [ -s "$BACKUP_FILE" ]; then
  c "🧠 Restaurando backup-latest.sql.gz…"
  gunzip -c "$BACKUP_FILE" | \
    docker compose exec -T "$SERVICE_DB" \
      mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
  g "Base restaurada."
else
  c "⚠️  Sin backup válido o la base ya tiene tablas."
fi

### 7. Usuario “ancla” de emergencia
c "🧙 Creando usuario ancla (si falta)…"
docker compose exec -T erp \
  php artisan tinker --execute \
  "App\\Models\\Usuario::firstOrCreate(
      ['email'=>'sistemas@macasahs.com.mx'],
      ['name'=>'ancla','password'=>bcrypt('Macasa2019$'),'es_admin'=>1]
  );"

############################################################################
g "🌅 GENKIDAMA completada: entorno limpio, base restaurada y listo en http://localhost"
