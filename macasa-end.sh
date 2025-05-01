#!/usr/bin/env bash
set -euo pipefail

# === CONFIG ===
PROYECTO="${HOME}/macasa-erp"
SERVICE_DB="mariadb"

# === UI helpers ===
c(){ printf "\e[1;36m%s\e[0m\n" "$*"; }
g(){ printf "\e[1;32m%s\e[0m\n" "$*"; }
r(){ printf "\e[1;31m%s\e[0m\n" "$*" >&2; }
die(){ r "✖ $*"; exit 1; }

cd "$PROYECTO" || die "No se pudo entrar a $PROYECTO"

# === 1. Ejecutar macasa-save.sh (dump + commit + push) ===
SCRIPT_DIR="$(dirname "$(readlink -f "$0")")"

if [[ -x "$SCRIPT_DIR/macasa-save" || -x "$SCRIPT_DIR/macasa-save.sh" ]]; then
  SAVE_SCRIPT="$(find "$SCRIPT_DIR" -name 'macasa-save*' -executable | head -n 1)"
else
  die "No encontré macasa-save(.sh) en $SCRIPT_DIR o no es ejecutable"
fi

c "💾 [macasa-end] Ejecutando $(basename "$SAVE_SCRIPT")…"
"$SAVE_SCRIPT" "$@"  # pasa mensaje de commit si se dio

# === 2. Parar contenedores ===
c "🧼 Deteniendo contenedores Docker…"
docker compose down

# === 3. Cerrar apps Windows (ignorar si no existen) ===
c "🧊 Cerrando aplicaciones de Windows…"
if grep -qi "microsoft" /proc/version; then
  powershell.exe -Command "Stop-Process -Name 'Docker Desktop','Code','GitHubDesktop' -Force -ErrorAction SilentlyContinue"
fi

# === 4. Despedida legendaria ===
g "🚀 Proyecto a salvo, contenedores apagados."
echo -e "\e[1;35m💌 ¡Buen trabajo, Zerezo! 🧠⚡ Disfruta tu merecido descanso.\e[0m"
