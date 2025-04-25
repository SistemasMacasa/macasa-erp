#!/bin/bash

PROYECTO=~/macasa-erp
cd "$PROYECTO" || exit

# Exportar base de datos
EXPORT_DIR="$PROYECTO/database"
EXPORT_TIMESTAMP="$EXPORT_DIR/backup-$(date '+%Y-%m-%d_%H-%M-%S').sql"
EXPORT_LATEST="$EXPORT_DIR/backup-latest.sql"

echo "💾 [macasa-end] Respaldando base de datos antes de cerrar..."
docker exec macasa_mariadb sh -c 'exec mysqldump -umacasa_user -pmacasa123 erp_ecommerce_db' > "$EXPORT_TIMESTAMP"
cp "$EXPORT_TIMESTAMP" "$EXPORT_LATEST"

# Limpiar backups antiguos (mantener los 3 más recientes)
echo "🧹 Eliminando backups antiguos..."
cd "$EXPORT_DIR"
ls -1t backup-*.sql | tail -n +4 | xargs -r rm -v

# Apagar contenedores
echo "🧼 Deteniendo contenedores Docker..."
docker compose down

# Cerrar apps Windows (Docker Desktop, GitHub Desktop, VS Code)
echo "🧊 Cerrando aplicaciones en segundo plano (si están abiertas)..."
powershell.exe -Command "Stop-Process -Name 'Docker Desktop' -Force" 2>/dev/null
powershell.exe -Command "Stop-Process -Name 'GitHubDesktop' -Force" 2>/dev/null
powershell.exe -Command "Stop-Process -Name 'Code' -Force" 2>/dev/null

# Despedida legendaria
echo "💌 [macasa-end] ¡Buen trabajo, Zerezo! Hoy diste cátedra 🧠⚡"
echo "⏳ Cerrando Visual Studio Code en 5 segundos... (Ctrl+C para abortar)"
sleep 5

echo "👋 [macasa-end] Hasta mañana, crack. ¡Proyecto a salvo!"
sleep 1
