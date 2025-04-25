#!/bin/bash

PROYECTO=~/macasa-erp
cd "$PROYECTO" || exit

echo "📥 [macasa-init] Haciendo git pull desde 'dev'..."
git pull origin dev 2>&1 | tee /tmp/gitlog
if grep -q "Already up to date" /tmp/gitlog; then
  echo "✅ El proyecto ya está actualizado."
else
  echo "📦 Cambios aplicados desde la rama 'dev'."
fi
rm /tmp/gitlog

echo "🖥️ [macasa-init] Verificando Docker Desktop..."

# Solo iniciar Docker Desktop si no está corriendo
if ! pgrep -f "Docker Desktop.exe" > /dev/null; then
  echo "🪟 Iniciando Docker Desktop..."
  powershell.exe -Command "Start-Process 'C:\\Program Files\\Docker\\Docker\\Docker Desktop.exe'" 2>/dev/null
else
  echo "🐋 Docker Desktop ya estaba corriendo."
fi

# Esperar a que Docker esté disponible
wait_for_docker() {
  echo "⌛ Esperando a que Docker esté listo..."
  local retry=0
  while ! docker info &> /dev/null; do
    sleep 1
    ((retry++))
    if [ "$retry" -gt 20 ]; then
      echo "❌ Docker no está listo después de 20 segundos."
      return 1
    fi
  done
  echo "✅ Docker está listo."
}

wait_for_docker || exit 1

# Abrir VS Code
echo "💻 [macasa-init] Abriendo VS Code en $PROYECTO..."

if grep -qi "microsoft" /proc/version; then
  if command -v code &> /dev/null; then
    code .
  else
    echo "⚠️ VS Code no está disponible como 'code'. ¿Está instalado en WSL?"
  fi
else
  DISTRO=$(wsl -l --quiet | grep -i ubuntu | head -n 1)
  if [ -z "$DISTRO" ]; then
    echo "❌ No se encontró una distro Ubuntu activa en WSL."
  else
    code --remote "wsl+$DISTRO" "$PROYECTO"
  fi
fi

echo "🧼 Limpiando contenedores antiguos..."
docker compose down --remove-orphans

echo "🐳 Levantando contenedores Docker..."
docker compose up -d --build

echo "🗄️  Verificando si la base de datos necesita restaurarse…"

DB_READY() {
  docker exec macasa_mariadb mysqladmin ping -pmacasa123 --silent &> /dev/null
}

until DB_READY; do
  echo "⌛ Esperando a MariaDB…"
  sleep 2
done
echo "✅ MariaDB responde."

TABLE_COUNT=$(docker exec macasa_mariadb \
  mysql -N -s -umacasa_user -pmacasa123 \
  -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='erp_ecommerce_db';")

if [ "$TABLE_COUNT" -eq 0 ] && [ -s "$PROYECTO/database/backup-latest.sql" ]; then
  echo "🔄 Restaurando backup-latest.sql…"
  docker exec -i macasa_mariadb \
    mysql -umacasa_user -pmacasa123 erp_ecommerce_db \
    < "$PROYECTO/database/backup-latest.sql"
  echo "✅ Restauración completada."
else
  echo "📂 La base ya contiene tablas o no existe backup válido; se omite la restauración."
fi


echo "✅ [macasa-init] Entorno iniciado exitosamente. ¡Hora de programar!"
