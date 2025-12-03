#!/bin/bash
set -e

echo "🚀 Iniciando Gambeta en producción..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando base de datos..."
max_retries=30
retry=0
while [ $retry -lt $max_retries ]; do
    if php artisan migrate:status &> /dev/null; then
        echo "✓ Base de datos conectada"
        break
    fi
    retry=$((retry+1))
    echo "   Intento $retry/$max_retries..."
    sleep 2
done

if [ $retry -eq $max_retries ]; then
    echo "❌ No se pudo conectar a la base de datos"
    exit 1
fi

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force --no-interaction

# Crear symlink de storage
if [ ! -L public/storage ]; then
    echo "🔗 Creando symlink de storage..."
    php artisan storage:link
fi

# Optimizar Laravel
echo "⚡ Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✓ Gambeta listo"

# Iniciar Apache
exec apache2-foreground
