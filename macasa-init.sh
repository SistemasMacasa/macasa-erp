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

# Esperar hasta que docker esté disponible
wait_for_docker() {
  echo "⌛ Esperando a que Docker esté listo..."
  local retry=0
  while ! docker info &> /dev/null; do
    sleep 1
    ((retry++))
    if [ "$retry" -gt 20 ]; then
      echo "❌ Docker no está listo después de 20 segundos."
      return 1
    fi
  done
  echo "✅ Docker está listo."
}

wait_for_docker || exit 1

echo "💻 [macasa-init] Abriendo VS Code en $PROYECTO..."

# Detectar si estamos DENTRO de WSL (entonces usamos code .)
if grep -qi "microsoft" /proc/version; then
  code .
else
  # Estamos en Windows, usar apertura remota con distro correcta
  DISTRO=$(wsl -l --quiet | grep -i ubuntu | head -n 1)
  if [ -z "$DISTRO" ]; then
    echo "❌ No se encontró una distro Ubuntu activa en WSL."
  else
    code --remote "wsl+$DISTRO" "$PROYECTO"
  fi
fi

echo "🧼 [macasa-init] Limpiando contenedores Docker..."
docker compose down --remove-orphans

echo "🐳 [macasa-init] Levantando contenedores Docker..."
docker compose up -d --build

echo "✅ Entorno iniciado exitosamente."
