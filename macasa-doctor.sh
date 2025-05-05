#!/usr/bin/env bash
set -uo pipefail

# === Helpers de salida ====================================================
WARN=0
FAIL=0

ok()   { printf "\e[1;32m✔ %s\e[0m\n" "$*"; }
warn() { printf "\e[1;33m⚠ %s\e[0m\n" "$*"; ((WARN++)); return 0; }
err()  { printf "\e[1;31m✖ %s\e[0m\n" "$*" >&2; ((FAIL++)); return 0; }
info() { printf "\e[1;36m▶ %s\e[0m\n" "$*"; }

# === A. Sistema & Docker ==================================================

info "A) Sistema y Docker"

# 1. ¿WSL o Linux?
if grep -qi microsoft /proc/version; then
  ok "WSL detectado"
else
  ok "Linux nativo detectado"
fi

# 2. Comandos docker / compose
docker --version >/dev/null && ok "docker instalado" || err "docker NO instalado"
docker compose version >/dev/null 2>&1 && ok "docker compose instalado" || err "docker compose NO instalado"


# 3. ¿Docker corriendo?
if docker info &>/dev/null; then
  ok "Daemon Docker activo"
else
  err "Docker NO está corriendo"
fi

# 4. ¿Usuario pertenece al grupo docker?
if id -nG | grep -qw docker; then
  ok "Usuario pertenece al grupo docker"
else
  warn "Usuario no está en grupo docker (puede requerir sudo)"
fi

# Separador visual
echo -e "\e[1;34m-------------------------------------------------\e[0m"

# === B. Contenedores del stack ============================================

STACK_OK=1
info "B) Stack MACASA (red, volumen, servicios)"

# 1. Archivo docker-compose.yml
[[ -f docker-compose.yml ]] && ok "docker-compose.yml presente" \
                             || { err "No se encontró docker-compose.yml"; STACK_OK=0; }

# 2. Red externa
if docker network inspect macasa-red-docker &>/dev/null; then
  ok "Red macasa-red-docker existente"
else
  err "Falta red macasa-red-docker"
  STACK_OK=0
fi

# 3. Volumen mariadb_data
if docker volume inspect mariadb_data &>/dev/null; then
  ok "Volumen mariadb_data presente"
else
  warn "Volumen mariadb_data no existe (se creará al levantar el stack)"
fi

# 4. Servicios activos
for svc in mariadb erp nginx; do
  if docker compose ps --services --filter "status=running" 2>/dev/null | grep -qx "$svc"; then
      ok "Servicio $svc activo"
  else
      err "Servicio $svc NO está corriendo"
      STACK_OK=0
  fi
done

[[ $STACK_OK -eq 1 ]] || FAIL=1

# -------------------------------------------------
echo -e "\e[1;34m-------------------------------------------------\e[0m"

# === C. Base de datos (MariaDB) ===========================================
info "C) Base de datos"

DB_NAME="erp_ecommerce_db"
DB_USER="${DB_USER:-macasa_user}"
DB_PASS="${DB_PASS:-macasa123}"
SERVICE_DB="mariadb"
BACKUP_FILE="./database/backup-latest.sql.gz"

DB_OK=1

# 1. ¿Se puede conectar el usuario?
if docker compose exec -T "$SERVICE_DB" \
     mysql -u"$DB_USER" -p"$DB_PASS" -e "SELECT 1" &>/dev/null; then
  ok "Conexión con $DB_USER exitosa"
else
  err "No se pudo conectar con $DB_USER / contraseña"
  DB_OK=0
fi

# 2. Conteo de tablas
if [[ $DB_OK -eq 1 ]]; then
  TABLAS=$(docker compose exec -T "$SERVICE_DB" \
    mysql -N -s -u"$DB_USER" -p"$DB_PASS" \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';") || TABLAS="?"
  ok "La base ${DB_NAME} tiene ${TABLAS} tablas"
fi

# 3. Backup más reciente
if [[ -f $BACKUP_FILE ]]; then
  if [[ -s $BACKUP_FILE ]]; then
    SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    ok "Backup encontrado ($SIZE)"
  else
    err "El archivo $BACKUP_FILE está vacío"
    DB_OK=0
  fi
else
  warn "No existe $BACKUP_FILE"
fi

[[ $DB_OK -eq 1 ]] || FAIL=1

echo -e "\e[1;34m-------------------------------------------------\e[0m"

# -------------------------------------------------
# === D. Laravel ============================================================
info "D) Laravel"

APP_CONTAINER="erp"          # serviceName de tu aplicación PHP
ENV_FILE=".env"
LARAVEL_OK=1

# 1. ¿existe .env?
if [[ -f $ENV_FILE ]]; then
  ok ".env encontrado"
else
  err "FALTA el archivo .env"
  LARAVEL_OK=0
fi

# 2. ¿APP_KEY presente?
if [[ -f $ENV_FILE ]] && grep -q '^APP_KEY=' "$ENV_FILE" && \
   [[ $(grep '^APP_KEY=' "$ENV_FILE" | cut -d= -f2) != "" ]]; then
  ok "APP_KEY definido"
else
  err "APP_KEY ausente o vacío"
  LARAVEL_OK=0
fi

# 3. ¿Artisan responde?
if docker compose exec -T "$APP_CONTAINER" php artisan --version &>/dev/null; then
  VER=$(docker compose exec -T "$APP_CONTAINER" php artisan --version 2>/dev/null)
  ok "Artisan responde ($VER)"
else
  err "No se pudo ejecutar php artisan"
  LARAVEL_OK=0
fi

# 4. ¿Migraciones coherentes?
if [[ $LARAVEL_OK -eq 1 ]]; then
  if docker compose exec -T "$APP_CONTAINER" \
       php artisan migrate:status --no-interaction &>/dev/null; then
    ok "Migraciones en buen estado"
  else
    warn "Migraciones pendientes o con error"
  fi
fi

[[ $LARAVEL_OK -eq 1 ]] || FAIL=1
# 5. ¿Tabla sessions?
docker compose exec -T "$SERVICE_DB" \
  mysql -N -s -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" \
  -e "SHOW TABLES LIKE 'sessions';" | grep -q sessions \
  && ok "Tabla sessions presente" \
  || warn "Falta tabla sessions (¿ejecutaste migraciones?)"

echo -e "\e[1;34m-------------------------------------------------\e[0m"
# -------------------------------------------------
# === E. Git ============================================================== #
info "E) Git"

GIT_OK=1

# 1. ¿El proyecto es un repo?
if [[ -d .git ]]; then
  ok "Repositorio Git detectado"
else
  err "Este directorio NO es un repositorio Git"
  GIT_OK=0
fi

# 2. Rama actual
if [[ $GIT_OK -eq 1 ]]; then
  BRANCH=$(git symbolic-ref --short HEAD 2>/dev/null || echo "¿desconocida?")
  ok "Rama actual: $BRANCH"
fi

# 3. ¿Cambios sin guardar?
if [[ $GIT_OK -eq 1 ]]; then
  if git diff --quiet && git diff --cached --quiet; then
    ok "Working tree limpio"
  else
    warn "Hay cambios sin commitear / sin agregar"
  fi
fi

# 4. ¿Conflictos?
if [[ $GIT_OK -eq 1 ]]; then
  if [[ $(git ls-files -u | wc -l) -gt 0 ]]; then
    err "Existen archivos con CONFLICTO"
    GIT_OK=0
  else
    ok "Sin conflictos de fusión"
  fi
fi

# 5. ¿Adelantado/atrasado vs. origin?
if [[ $GIT_OK -eq 1 ]]; then
  STATUS=$(git status -sb 2>/dev/null | head -n1)
  # Ejemplo: ## dev...origin/dev [ahead 2, behind 1]
  if grep -q "ahead\|behind" <<< "$STATUS"; then
    warn "$STATUS"
  else
    ok "Repositorio sincronizado con remoto"
  fi
fi

[[ $GIT_OK -eq 1 ]] || FAIL=1
echo -e "\e[1;34m-------------------------------------------------\e[0m"


echo -e "\e[1;34m-------------------------------------------------\e[0m"

# ==============================================================
echo -e "\e[1;34m==================  RESUMEN MACASA-DOCTOR  ==================\e[0m"

if [[ $FAIL -eq 0 && $WARN -eq 0 ]]; then
  printf "\e[1;32m🟢  TODO OK — ¡A codear sin miedo! 🚀\e[0m\n"
  EXIT=0
elif [[ $FAIL -eq 0 ]]; then
  printf "\e[1;33m🟡  Advertencias: %d  |  Fallos críticos: %d\e[0m\n" "$WARN" "$FAIL"
  printf "\e[1;33mRevisa los ⚠ arriba, pero nada grave.\e[0m\n"
  EXIT=0
else
  printf "\e[1;31m🔴  Advertencias: %d  |  Fallos críticos: %d\e[0m\n" "$WARN" "$FAIL"
  printf "\e[1;31mAtiende los ✖ para recuperar el entorno.\e[0m\n"
  EXIT=1
fi

exit "$EXIT"
