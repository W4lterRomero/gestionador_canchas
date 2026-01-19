#!/bin/bash
set -e

# Esperar a que MySQL esté disponible
echo "Esperando conexión a MySQL..."
max_retries=30
counter=0
until php artisan db:monitor --databases=mysql > /dev/null 2>&1 || [ $counter -eq $max_retries ]; do
    echo "Intento $counter de $max_retries..."
    sleep 2
    counter=$((counter + 1))
done

if [ $counter -eq $max_retries ]; then
    echo "No se pudo conectar a MySQL después de $max_retries intentos"
fi

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y cachear configuración
echo "Optimizando Laravel..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache
echo "Iniciando Apache..."
exec apache2-foreground
