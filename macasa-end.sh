#!/bin/bash

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
