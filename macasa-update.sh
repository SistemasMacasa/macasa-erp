#!/bin/bash

PROYECTO=~/macasa-erp
cd $PROYECTO || exit

echo "📥 [macasa-update] Haciendo git pull desde 'dev'..."
git pull origin dev 2>&1 | tee /tmp/gitlog

if grep -q "Already up to date" /tmp/gitlog; then
  echo "✅ El proyecto ya está actualizado. No se reconstruirán los contenedores."
else
  echo "📦 Cambios detectados. Reconstruyendo entorno Docker..."
  docker compose down
  docker compose up -d --build
  echo "✅ Proyecto actualizado y contenedores reconstruidos."
fi

rm /tmp/gitlog
