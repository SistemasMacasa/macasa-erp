#!/bin/bash

echo "🐳 Iniciando contenedores Docker..."
docker compose up -d --build

echo "🧹 Limpiando caché de Laravel..."
docker exec -it macasa_erp php artisan config:clear
docker exec -it macasa_erp php artisan route:clear
docker exec -it macasa_erp php artisan view:clear

echo "✅ Entorno iniciado y caché limpiada exitosamente."
