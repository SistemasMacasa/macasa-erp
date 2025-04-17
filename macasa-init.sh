#!/bin/bash

PROYECTO=~/macasa-erp
cd $PROYECTO || exit

echo "📥 [macasa-init] Haciendo git pull desde 'dev'..."
git pull origin dev 2>&1 | tee /tmp/gitlog
if grep -q "Already up to date" /tmp/gitlog; then
  echo "✅ El proyecto ya está actualizado."
else
  echo "📦 Cambios aplicados desde la rama 'dev'."
fi
rm /tmp/gitlog

echo "🖥️ [macasa-init] Iniciando Docker Desktop (si aplica)..."
powershell.exe -Command "Start-Process 'C:\\Program Files\\Docker\\Docker\\Docker Desktop.exe'" 2>/dev/null

echo "💻 [macasa-init] Abriendo VS Code en $PROYECTO..."
code $PROYECTO

echo "🐳 [macasa-init] Levantando contenedores Docker..."
docker compose up -d --build

echo "✅ Entorno iniciado exitosamente."
