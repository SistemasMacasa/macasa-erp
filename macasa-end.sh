#!/bin/bash

PROYECTO=~/macasa-erp
cd $PROYECTO || exit

# Exportar la base de datos antes de cerrar
EXPORT_DIR="$PROYECTO/database"
EXPORT_TIMESTAMP="$EXPORT_DIR/backup-$(date '+%Y-%m-%d_%H-%M-%S').sql"
EXPORT_LATEST="$EXPORT_DIR/backup-latest.sql"

echo "💾 [macasa-end] Respaldando base de datos antes de apagar..."
docker exec macasa_mariadb sh -c 'exec mysqldump -umacasa_user -pmacasa123 erp_ecommerce_db' > "$EXPORT_TIMESTAMP"
cp "$EXPORT_TIMESTAMP" "$EXPORT_LATEST"

# Limpiar backups antiguos
echo "🧹 Eliminando backups antiguos..."
cd "$EXPORT_DIR"
ls -1t backup-*.sql | tail -n +4 | xargs -r rm -v

echo "🧼 [macasa-end] Deteniendo contenedores Docker..."
docker compose down

echo "🧊 [macasa-end] Cerrando Docker Desktop (si está abierto)..."
powershell.exe -Command "Stop-Process -Name 'Docker Desktop' -Force" 2>/dev/null

echo "🧊 [macasa-end] Cerrando GitHub Desktop (si está abierto)..."
powershell.exe -Command "Stop-Process -Name 'GitHubDesktop' -Force" 2>/dev/null

echo "💌 [macasa-end] ¡Buen trabajo, Zerezo! Te rifaste hoy. 🧠💪"
echo "⏳ Cerrando VS Code en 5 segundos... Presiona Ctrl+C para cancelar."
sleep 5

echo "🛑 [macasa-end] Cerrando Visual Studio Code (interfaz gráfica)..."
powershell.exe -Command "Stop-Process -Name 'Code' -Force" 2>/dev/null

echo "👋 Quod Erat Demonstrandum"
sleep 1