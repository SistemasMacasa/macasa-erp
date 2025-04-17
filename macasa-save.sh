#!/bin/bash

# Verifica si se proporcionó un mensaje de commit
if [ -z "$1" ]; then
    # Si no se proporcionó, usa la fecha y hora actuales
    COMMIT_MSG="Auto-commit: $(date '+%Y-%m-%d %H:%M:%S')"
else
    COMMIT_MSG="$1"
fi

echo "🔄 Agregando cambios al repositorio..."
git add .

echo "📝 Haciendo commit con el mensaje: \"$COMMIT_MSG\""
git commit -m "$COMMIT_MSG"

echo "📤 Haciendo push al repositorio remoto..."
git push origin HEAD

echo "✅ Cambios guardados y enviados exitosamente."
