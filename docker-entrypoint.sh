#!/bin/bash
set -e

# Configurar el puerto (Railway provee $PORT)
export PORT=${PORT:-80}
echo "Configurando Apache en puerto $PORT..."

# Reemplazar el puerto en la configuración de Apache
sed -i "s/\${PORT:-80}/$PORT/g" /etc/apache2/ports.conf
sed -i "s/\${PORT:-80}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Esperar a que MySQL esté disponible (máximo 60 segundos)
echo "Esperando conexión a MySQL..."
max_retries=30
counter=0
until php artisan migrate:status > /dev/null 2>&1 || [ $counter -eq $max_retries ]; do
    echo "Intento $((counter + 1)) de $max_retries..."
    sleep 2
    counter=$((counter + 1))
done

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force || echo "Migraciones fallaron o ya estaban aplicadas"

# Limpiar y cachear configuración
echo "Optimizando Laravel..."
php artisan config:clear
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Iniciar Apache
echo "Iniciando Apache en puerto $PORT..."
exec apache2-foreground
