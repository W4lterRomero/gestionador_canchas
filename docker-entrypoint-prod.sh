#!/bin/bash
set -e

echo "==================================="
echo "Iniciando Gambeta en producción"
echo "==================================="

# Verificar variables de entorno críticas
echo "Verificando configuración..."
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY no configurada, generando..."
    php artisan key:generate --force
fi

echo "✓ APP_ENV: $APP_ENV"
echo "✓ DB_HOST: $DB_HOST"
echo "✓ DB_DATABASE: $DB_DATABASE"

# Esperar a que la base de datos esté lista
echo ""
echo "Esperando base de datos..."
max_retries=30
retry=0

while [ $retry -lt $max_retries ]; do
    if php artisan migrate:status &> /dev/null 2>&1; then
        echo "✓ Base de datos conectada"
        break
    fi
    retry=$((retry+1))
    if [ $((retry % 5)) -eq 0 ]; then
        echo "   Intento $retry/$max_retries..."
    fi
    sleep 2
done

if [ $retry -eq $max_retries ]; then
    echo "❌ No se pudo conectar a la base de datos después de $max_retries intentos"
    echo "Verificar configuración de BD en Render"
    # No hacer exit, intentar iniciar Apache de todos modos
fi

# Ejecutar migraciones (si la BD está disponible)
if [ $retry -lt $max_retries ]; then
    echo ""
    echo "Ejecutando migraciones..."
    php artisan migrate --force --no-interaction || echo "⚠️  Error en migraciones (puede ser normal si ya existen)"
fi

# Crear symlink de storage
echo ""
echo "Configurando storage..."
if [ ! -L public/storage ]; then
    php artisan storage:link || echo "⚠️  Storage link ya existe o no se pudo crear"
fi

# Optimizar Laravel (solo si tenemos BD)
if [ $retry -lt $max_retries ]; then
    echo ""
    echo "Optimizando Laravel..."
    php artisan config:cache || echo "⚠️  No se pudo cachear config"
    php artisan route:cache || echo "⚠️  No se pudo cachear rutas"
    php artisan view:cache || echo "⚠️  No se pudo cachear vistas"
fi

echo ""
echo "==================================="
echo "✓ Gambeta configurado"
echo "==================================="
echo ""

# Verificar que index.php existe
if [ -f public/index.php ]; then
    echo "✓ index.php encontrado en public/"
else
    echo "❌ ERROR: index.php NO encontrado en public/"
    ls -la public/
fi

# Iniciar Apache
echo "Iniciando Apache..."
exec apache2-foreground
