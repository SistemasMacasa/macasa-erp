#!/usr/bin/env bash
set -euo pipefail

# === Config ===
PROYECTO="${HOME}/macasa-erp"
BRANCH="${1:-dev}"           # permite: macasa-update main
TMP_LOG=$(mktemp)

# === UI helpers ===
cyan()  { printf "\e[1;36m%s\e[0m\n" "$*"; }  # cyan bold
green() { printf "\e[1;32m%s\e[0m\n" "$*"; }
red()   { printf "\e[1;31m%s\e[0m\n" "$*" >&2; }
die()   { red "✖ $*"; exit 1; }

trap 'rm -f "$TMP_LOG"' EXIT

cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

# === 1. Git pull ===
cyan "📥 [macasa-update] git pull '$BRANCH'…"
if ! git pull origin "$BRANCH" 2>&1 | tee "$TMP_LOG"; then
  die "git pull falló"
fi

if grep -q "Already up to date" "$TMP_LOG"; then
  green "✅ Proyecto ya está actualizado. Nada que reconstruir."
  exit 0
fi

# === 2. Reconstruir servicios Docker ===
cyan "📦 Cambios detectados → reconstruyendo contenedores…"
docker compose up -d --build   # up-gradeado preserva caché; no hace falta down completo
green "🚀 Contenedores actualizados con la última versión del código."

# Script para actualizar el entorno del proyecto Macasa ERP.
# Uso:
#   macasa-update            # Actualiza el entorno utilizando la rama 'dev' por defecto.
#   macasa-update main       # Actualiza el entorno utilizando la rama 'main'.
# Notas:
# - Asegúrate de tener los permisos necesarios para ejecutar este script.

