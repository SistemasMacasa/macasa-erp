#!/bin/bash

PROYECTO=~/macasa-erp
cd $PROYECTO || exit

echo "💥 [macasa-reset] Lanzando Genkidama sobre Docker..."

# 1. Apagar y eliminar contenedores
echo "🛑 Deteniendo y eliminando todos los contenedores..."
docker rm -f $(docker ps -aq) 2>/dev/null || echo "⚠️ No había contenedores."

# 2. Eliminar volúmenes relevantes
echo "🧹 Eliminando volumen 'mariadb_data' si existe..."
docker volume rm mariadb_data 2>/dev/null || echo "⚠️ No se pudo eliminar 'mariadb_data' o no existe."

# 3. Limpiar volúmenes y redes sin uso
docker volume prune -f
docker network prune -f

# 4. Volver a crear la red (si es externa)
echo "🔁 Verificando red externa 'macasa-red-docker'..."
if ! docker network ls | grep -q macasa-red-docker; then
  docker network create macasa-red-docker
  echo "🌐 Red 'macasa-red-docker' creada."
else
  echo "✅ Red 'macasa-red-docker' ya existe."
fi

# 5. Reconstruir el entorno
echo "🔨 Reconstruyendo entorno desde las cenizas..."
docker compose up -d --build

# 6. Restaurar la base de datos si backup existe
BACKUP="$PROYECTO/database/backup-latest.sql"
if [ -f "$BACKUP" ]; then
  echo "🧠 Restaurando base de datos desde backup-latest.sql..."
  docker exec -i macasa_mariadb mysql -umacasa_user -pmacasa123 erp_ecommerce_db < "$BACKUP"
  echo "✅ Base de datos restaurada correctamente."
else
  echo "⚠️ No se encontró backup-latest.sql, la base de datos no se restauró."
fi

# 7. Crear usuario de emergencia (si no existe)
echo "🧙 Verificando usuario ancla"
docker exec -it macasa_erp php artisan tinker --execute \
"App\\Models\\Usuario::firstOrCreate(['email' => 'sistemas@macasahs.com.mx'], ['name' => 'ancla', 'password' => bcrypt('Macasa2019$', 'es_admin' => 1]);"

echo "🌅 Entorno restaurado, base lista y acceso disponible en http://localhost"
