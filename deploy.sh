#!/bin/bash
# Script de deploy automático para Render.com
# Se ejecuta automáticamente por GitHub Actions

set -e

echo "🚀 Iniciando deploy en producción..."

# Instalar dependencias
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

echo "📦 Instalando dependencias Node..."
npm install --omit=dev

# Construir assets
echo "🎨 Compilando assets..."
npm run build

# Migrar base de datos
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Seedear datos iniciales (si es primera ejecución)
echo "🌱 Ejecutando seeders..."
php artisan db:seed --force || true

# Limpiar cache
echo "🧹 Limpiando caché..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Deploy completado exitosamente!"
