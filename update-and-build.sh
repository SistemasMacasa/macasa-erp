#!/bin/bash

echo "🚀 Haciendo git pull..."
git pull origin dev

echo "🔄 Reconstruyendo contenedores Docker..."
docker compose down
docker compose up -d --build

echo "✨ Todo listo, entorno actualizado y reconstruido!"
