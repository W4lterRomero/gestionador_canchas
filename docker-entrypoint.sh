#!/bin/bash
set -e

# Configurar el puerto (Railway provee $PORT)
PORT=${PORT:-80}
echo "==> Configurando Apache en puerto $PORT..."

# Actualizar ports.conf para escuchar en el puerto correcto
echo "Listen $PORT" > /etc/apache2/ports.conf

# Esperar a que MySQL esté disponible
echo "==> Esperando conexión a MySQL..."
max_retries=15
counter=0
until php artisan migrate:status > /dev/null 2>&1 || [ $counter -eq $max_retries ]; do
    echo "    Intento $((counter + 1)) de $max_retries..."
    sleep 2
    counter=$((counter + 1))
done

# Ejecutar migraciones
echo "==> Ejecutando migraciones..."
php artisan migrate --force || echo "    Migraciones fallaron o ya estaban aplicadas"

# Optimizar Laravel
echo "==> Optimizando Laravel..."
php artisan config:clear > /dev/null 2>&1
php artisan config:cache > /dev/null 2>&1 || true
php artisan route:cache > /dev/null 2>&1 || true
php artisan view:cache > /dev/null 2>&1 || true

# Asegurar que solo mpm_prefork está habilitado (Fix para AH00534)
echo "==> Verificando módulos MPM..."
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf
# Asegurar prefork
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ]; then
    ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
    ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
fi

echo "==> Módulos MPM habilitados:"
ls -1 /etc/apache2/mods-enabled/mpm_*.load || true

# Iniciar Apache
echo "==> Iniciando Apache en puerto $PORT..."
exec apache2-foreground
