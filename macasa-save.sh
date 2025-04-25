#!/bin/bash

PROYECTO=~/macasa-erp
cd $PROYECTO || exit

# Exportar base de datos
EXPORT_DIR="$PROYECTO/database"
EXPORT_TIMESTAMP="$EXPORT_DIR/backup-$(date '+%Y-%m-%d_%H-%M-%S').sql"
EXPORT_LATEST="$EXPORT_DIR/backup-latest.sql"

echo "🧠 Exportando base de datos a: $EXPORT_TIMESTAMP"
docker exec macasa_mariadb sh -c 'exec mysqldump -umacasa_user -pmacasa123 erp_ecommerce_db' > "$EXPORT_TIMESTAMP"

# Copiar como respaldo principal
cp "$EXPORT_TIMESTAMP" "$EXPORT_LATEST"

# Limpiar backups antiguos (mantener solo los 3 más recientes)
echo "🧹 Limpiando respaldos antiguos..."
cd "$EXPORT_DIR"
ls -1t backup-*.sql | tail -n +4 | xargs -r rm -v
echo "🧹 Respaldo más antiguo eliminado."

# Verificar si hay cambios para guardar
echo "💾 Guardando en GitHub..."
if git diff --quiet && git diff --cached --quiet; then
  echo "😎 ¿Qué le quieres actualizar? Si ya está actualizado."
  exit 0
fi

# Agregar y hacer commit
COMMIT_MSG=${1:-"Auto-commit: $(date '+%Y-%m-%d %H:%M:%S')"}
echo "📦 [macasa-save] Agregando cambios con mensaje: $COMMIT_MSG"
git add -A
git commit -m "$COMMIT_MSG"
git push origin dev

echo "✅ Cambios guardados, respaldo actualizado, y basura eliminada 😄"
