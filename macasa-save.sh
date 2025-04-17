#!/bin/bash

PROYECTO=~/macasa-erp
cd $PROYECTO || exit

# Verificar si hay cambios para guardar
if git diff --quiet && git diff --cached --quiet; then
  echo "😎 ¿Qué le quieres actualizar? Si ya está actualizado."
  exit 0
fi

COMMIT_MSG=${1:-"Auto-commit: $(date '+%Y-%m-%d %H:%M:%S')"}
echo "📦 [macasa-save] Agregando y haciendo commit con mensaje: $COMMIT_MSG"
git add .
git commit -m "$COMMIT_MSG"
git push origin dev
echo "✅ Cambios guardados y enviados a 'dev'."
