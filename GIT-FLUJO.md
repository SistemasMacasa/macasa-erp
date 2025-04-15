
# ✅ FLUJO COMPLETO DE TRABAJO EN GIT (EJECUTAR COMANDOS EN EL WSL DE LA MAQUINA HOST)

## 🟩 Inicio de proyecto (una sola vez)
```bash
git init
git remote add origin <url>
```

## 🔁 Inicio del día (mantente actualizado)
```bash
git checkout dev
git fetch
git pull
```

## 🌿 Crear una nueva rama para trabajar
```bash
git checkout -b feature/nombre-de-la-tarea
```

## 🛠 Hacer cambios y guardarlos
```bash
git add .
git commit -m "Descripción del cambio"
```

## 🚀 Subir tus avances a GitHub
```bash
git push -u origin feature/nombre-de-la-tarea
# Luego solo necesitas:
git push
```

## 🔀 Terminar tu tarea y unirla a `dev`
```bash
git checkout dev
git pull
git merge feature/nombre-de-la-tarea
git push
```

## 🧼 Limpiar ramas ya fusionadas
```bash
git branch -d feature/nombre-de-la-tarea
```

## ❗ Si hay conflictos
1. Edita el archivo afectado
2. Borra las marcas conflictivas
3. Guarda y ejecuta:
```bash
git add archivo
git commit -m "Resuelto conflicto"
git push
```

## 🧠 BONUS TIPS
```bash
git status            # Ver el estado actual
git log --oneline     # Ver historial compacto
git branch -r         # Ver ramas remotas
git pull origin dev   # Pull explícito
```
