# Gambeta - Sistema de Gestión de Canchas

Sistema web para gestionar reservas de canchas deportivas. Desarrollado con Laravel 12, Livewire 3 y MySQL, containerizado con Docker.

## Stack Tecnológico

- Laravel 12 + Livewire 3
- MySQL 8.0
- PHP 8.2 + Apache 2.4
- Docker + Docker Compose
- Bootstrap (via Laravel UI)
- Spatie Permission para roles
- DomPDF para comprobantes

---

## Desarrollo Local

### Instalación rápida

```bash
git clone <URL_DEL_REPOSITORIO>
cd gestionador_canchas
./setup.sh
```

El script configura automáticamente Docker, dependencias, base de datos y permisos.

### Instalación manual

```bash
# 1. Configurar permisos Docker
cp .env.docker.example .env.docker
echo "UID=$(id -u)" >> .env.docker
echo "GID=$(id -g)" >> .env.docker

# 2. Levantar contenedores
docker-compose up -d

# 3. Setup Laravel (dentro del contenedor)
docker-compose exec app bash -c "cd gambeta && composer install"
docker-compose exec app bash -c "cd gambeta && cp .env.example .env"
docker-compose exec app bash -c "cd gambeta && php artisan key:generate"
docker-compose exec app bash -c "cd gambeta && php artisan migrate --seed"
docker-compose exec app bash -c "cd gambeta && npm install && npm run build"
```

### Acceso

- **App**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8082 (root/rootpass)

Usuarios de prueba (si ejecutaste seeders):
- Admin: admin@gambeta.com / password
- Empleado: empleado@gambeta.com / password

### Comandos útiles

```bash
# Levantar/detener
docker-compose up -d
docker-compose down

# Ver logs
docker-compose logs -f app

# Ejecutar Artisan
docker-compose exec app bash -c "cd gambeta && php artisan [comando]"

# Limpiar y reiniciar
docker-compose down -v
docker-compose up -d
```

---

## Despliegue en Render

### Pasos para desplegar

1. **Push a GitHub**
   ```bash
   git add .
   git commit -m "Deploy to Render"
   git push origin main
   ```

2. **Conectar en Render**
   - Ve a https://render.com
   - New → Blueprint
   - Conecta tu repositorio de GitHub
   - Render detectará automáticamente `render.yaml`
   - Confirma la creación del servicio web y base de datos

3. **Esperar el despliegue**
   - El build tarda 5-10 minutos
   - Render ejecuta las migraciones automáticamente
   - La base de datos MySQL se crea automáticamente

### Variables de entorno

Las variables se configuran automáticamente desde `render.yaml`:
- `APP_NAME`, `APP_ENV`, `APP_DEBUG`
- `APP_KEY` (se genera automáticamente)
- `DB_*` (se configuran desde la base de datos de Render)

### Solución de problemas en Render

**Ver logs:**
- Render Dashboard → Tu servicio → Logs

**Error 500:**
```bash
# En Render Shell
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
```

**Error de base de datos:**
- Verificar que la BD en Render esté activa (tarda 1-2 min en iniciar)
- Las credenciales se configuran automáticamente

---

## Estructura del proyecto

```
gestionador_canchas/
├── dockerfile              # Docker para desarrollo local
├── Dockerfile.production   # Docker para Render (producción)
├── docker-compose.yml      # Compose para desarrollo local
├── render.yaml             # Configuración de Render
├── .dockerignore           # Archivos excluidos del build
├── .env.docker.example     # Variables para desarrollo
└── proyectos/gambeta/      # Aplicación Laravel
```

## Funcionalidades

- Gestión de canchas (CRUD, múltiples imágenes, histórico de precios)
- Sistema de reservas con calendario interactivo
- Registro de clientes y su historial
- Generación de comprobantes en PDF
- Control de pagos y adelantos
- Roles de usuario (Admin/Empleado)
- Bloqueo de horarios para mantenimiento

## Base de datos

**Tablas principales:**
- `users` - Usuarios del sistema (con roles)
- `canchas` - Canchas deportivas
- `cancha_imagenes` - Múltiples imágenes por cancha
- `cancha_precios` - Histórico de precios
- `bloqueos_horarios` - Bloqueos de tiempo
- `clientes` - Clientes del complejo
- `reservas` - Reservas (con estados)
- `pagos` - Registro de pagos
- `reservas_estados_historial` - Auditoría

---

## Notas importantes

- **Desarrollo**: Usa `docker-compose.yml` + `dockerfile`
- **Producción**: Usa `Dockerfile.production` (automático en Render)
- Los archivos de desarrollo NO afectan el despliegue
- Render usa `render.yaml` para configuración automática

---

**Última actualización:** Diciembre 2024
