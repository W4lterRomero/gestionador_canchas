#!/bin/bash

# Script de configuración automática para Gambeta
# Este script configura todo el entorno Docker + Laravel automáticamente

set -e  # Detener si hay errores

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funciones de utilidad
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_step() {
    echo -e "\n${BLUE}==>${NC} $1\n"
}

# Banner
echo -e "${GREEN}"
cat << "EOF"
╔════════════════════════════════════════════════╗
║         GAMBETA - Setup Automático             ║
║    Sistema de Reservación de Canchas          ║
╚════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

# Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    print_error "Error: No se encuentra docker-compose.yml"
    print_error "Por favor ejecuta este script desde la raíz del proyecto"
    exit 1
fi

# Paso 1: Verificar Docker
print_step "Paso 1: Verificando Docker"
if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado. Por favor instala Docker Desktop primero."
    print_info "Descarga: https://www.docker.com/products/docker-desktop"
    exit 1
fi

if ! docker compose version &> /dev/null; then
    print_error "Docker Compose no está instalado o no funciona."
    exit 1
fi

print_success "Docker instalado: $(docker --version)"
print_success "Docker Compose instalado: $(docker compose version --short)"

# Paso 2: Configurar .env.docker
print_step "Paso 2: Configurando permisos Docker (.env.docker)"

if [ ! -f ".env.docker" ]; then
    print_info "Creando .env.docker con tu UID y GID..."

    USER_UID=$(id -u)
    USER_GID=$(id -g)

    cat > .env.docker << EOL
# Configuración de usuario para Docker
# Generado automáticamente el $(date)
UID=${USER_UID}
GID=${USER_GID}
EOL

    print_success ".env.docker creado con UID=${USER_UID} y GID=${USER_GID}"
else
    print_warning ".env.docker ya existe. Verificando valores..."
    cat .env.docker
fi

# Paso 3: Construir contenedores
print_step "Paso 3: Construyendo imágenes Docker"
print_info "Esto puede tardar 5-10 minutos la primera vez..."

if docker compose build; then
    print_success "Imágenes construidas correctamente"
else
    print_error "Error al construir las imágenes Docker"
    exit 1
fi

# Paso 4: Levantar contenedores
print_step "Paso 4: Levantando contenedores"

if docker compose up -d; then
    print_success "Contenedores iniciados"
else
    print_error "Error al iniciar los contenedores"
    exit 1
fi

# Esperar a que MySQL esté listo
print_info "Esperando a que MySQL esté listo..."
sleep 10

MAX_RETRIES=30
RETRY=0
while [ $RETRY -lt $MAX_RETRIES ]; do
    if docker compose exec -T db mysql -u root -prootpass -e "SELECT 1" &> /dev/null; then
        print_success "MySQL está listo"
        break
    fi
    RETRY=$((RETRY+1))
    echo -n "."
    sleep 2
done

if [ $RETRY -eq $MAX_RETRIES ]; then
    print_error "MySQL no está respondiendo después de 60 segundos"
    print_info "Revisa los logs con: docker compose logs db"
    exit 1
fi

# Paso 5: Configurar Laravel
print_step "Paso 5: Configurando Laravel"

# Verificar si .env existe, si no, crearlo desde .env.example
if docker compose exec -T app bash -c "cd gambeta && [ ! -f .env ]"; then
    print_info "Creando archivo .env desde .env.example..."
    docker compose exec -T app bash -c "cd gambeta && cp .env.example .env"
    print_success "Archivo .env creado"
else
    print_warning "El archivo .env ya existe, no se sobreescribirá"
fi

# Instalar dependencias de Composer
print_info "Instalando dependencias de Composer (puede tardar 2-5 minutos)..."
if docker compose exec -T app bash -c "cd gambeta && composer install --no-interaction --prefer-dist --optimize-autoloader"; then
    print_success "Dependencias de Composer instaladas"
else
    print_error "Error al instalar dependencias de Composer"
    exit 1
fi

# Generar APP_KEY
print_info "Generando clave de aplicación..."
if docker compose exec -T app bash -c "cd gambeta && php artisan key:generate"; then
    print_success "APP_KEY generada"
else
    print_error "Error al generar APP_KEY"
    exit 1
fi

# Configurar permisos
print_info "Configurando permisos..."
docker compose exec -T app chown -R www-data:www-data /var/www/html
docker compose exec -T app bash -c "cd gambeta && chmod -R 775 storage bootstrap/cache"
print_success "Permisos configurados"

# Paso 6: Base de datos
print_step "Paso 6: Configurando base de datos"

# Verificar si ya hay migraciones ejecutadas
TABLES_COUNT=$(docker compose exec -T db mysql -u root -prootpass appdb -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'appdb'" 2>/dev/null || echo "0")

if [ "$TABLES_COUNT" -gt "1" ]; then
    print_warning "La base de datos ya tiene tablas. ¿Quieres recrearla?"
    read -p "¿Ejecutar migraciones desde cero? Esto BORRARÁ todos los datos (s/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        print_info "Ejecutando migraciones desde cero..."
        docker compose exec -T app bash -c "cd gambeta && php artisan migrate:fresh --seed --force"
        print_success "Base de datos recreada con datos de prueba"
    else
        print_info "Migraciones omitidas"
    fi
else
    print_info "Ejecutando migraciones..."
    if docker compose exec -T app bash -c "cd gambeta && php artisan migrate --seed --force"; then
        print_success "Migraciones ejecutadas con datos de prueba"
    else
        print_error "Error al ejecutar migraciones"
        exit 1
    fi
fi

# Paso 7: Compilar assets
print_step "Paso 7: Compilando assets frontend"

# Verificar si node_modules existe
if docker compose exec -T app bash -c "cd gambeta && [ ! -d node_modules ]"; then
    print_info "Instalando dependencias de Node (puede tardar 3-5 minutos)..."
    if docker compose exec -T app bash -c "cd gambeta && npm install --no-audit"; then
        print_success "Dependencias de Node instaladas"
    else
        print_warning "Error al instalar dependencias de Node (no crítico)"
    fi
else
    print_warning "node_modules ya existe, omitiendo npm install"
fi

print_info "Compilando assets..."
if docker compose exec -T app bash -c "cd gambeta && npm run build"; then
    print_success "Assets compilados"
else
    print_warning "Error al compilar assets (no crítico)"
fi

# Paso 8: Verificación final
print_step "Paso 8: Verificación final"

echo ""
docker compose ps

# Resumen final
echo -e "\n${GREEN}"
cat << "EOF"
╔════════════════════════════════════════════════╗
║          ✓ Instalación Completada             ║
╚════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

print_success "Gambeta está listo para usar\n"

echo -e "${BLUE}Accede a la aplicación:${NC}"
echo -e "  → Aplicación: ${GREEN}http://localhost:8080${NC}"
echo -e "  → phpMyAdmin: ${GREEN}http://localhost:8082${NC}"
echo -e "      Usuario: root"
echo -e "      Contraseña: rootpass\n"

echo -e "${BLUE}Credenciales de prueba (si ejecutaste seeders):${NC}"
echo -e "  → Admin: ${GREEN}admin@gambeta.com${NC} / password"
echo -e "  → Empleado: ${GREEN}empleado@gambeta.com${NC} / password\n"

echo -e "${BLUE}Comandos útiles:${NC}"
echo -e "  → Ver logs: ${YELLOW}docker compose logs -f app${NC}"
echo -e "  → Detener: ${YELLOW}docker compose down${NC}"
echo -e "  → Reiniciar: ${YELLOW}docker compose restart${NC}"
echo -e "  → Entrar al contenedor: ${YELLOW}docker compose exec app bash${NC}\n"

echo -e "${BLUE}Documentación:${NC}"
echo -e "  → README.md - Guía completa"
echo -e "  → GUIA_INSTALACION.md - Instalación detallada"
echo -e "  → SOLUCION_PERMISOS_DOCKER.md - Troubleshooting\n"

print_success "¡Listo para desarrollar! 🚀"
