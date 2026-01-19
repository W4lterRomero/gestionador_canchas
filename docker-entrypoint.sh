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

# Ejecutar seeders solo si es necesario (evitar duplicados)
echo "==> Verificando necesidad de seeders..."
# Intentar contar usuarios. Si falla (ej: tabla no existe), asumimos que algo malio sal, pero el seed no deberia correr si migrate fallo
if php artisan migrate:status > /dev/null 2>&1; then
    USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | grep -E '^[0-9]+$' | tail -n1)
    # Si grep falla o user_count es vacio, defaultear a algo seguro (no seed)
    USER_COUNT=${USER_COUNT:-1} 
    
    # Hacemos una verificación más robusta intentando leer la salida limpia
    # Pero para simplificar en bash, si la tabla está vacía count es 0.
    # Usaremos una forma más directa con tinker para evitar basura en stdout
    USER_COUNT=$(php artisan tinker --execute="echo (int)\App\Models\User::exists();" 2>/dev/null | tail -n1)
    
    if [ "$USER_COUNT" == "0" ]; then
        echo "==> Base de datos parece vacía. Ejecutando seeders..."
        php artisan db:seed --force
    else
        echo "==> Datos detectados. Saltando seeders."
    fi
fi

# Optimizar Laravel
echo "==> Optimizando Laravel..."
php artisan config:clear > /dev/null 2>&1
php artisan config:cache > /dev/null 2>&1 || true
php artisan route:cache > /dev/null 2>&1 || true
php artisan view:cache > /dev/null 2>&1 || true

# Configurar Storage (Imágenes)
echo "==> Configurando Storage..."
php artisan storage:link || true

# Asegurar que existe una imagen por defecto para los seeders
mkdir -p storage/app/public/canchas
if [ ! -f storage/app/public/canchas/cancha.png ]; then
    echo "==> Creando imagen dummy para canchas..."
    # Crear un PNG vacío simple de 1x1 si no tenemos imagen real, o copiar de assets si existieran
    # Aquí usaremos base64 para crear un png válido mínimo (red dot)
    echo "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==" | base64 -d > storage/app/public/canchas/cancha.png
fi

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
