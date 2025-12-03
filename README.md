# Gambeta - Sistema de Gestión de Canchas

Sistema web para gestionar reservas de canchas deportivas. Desarrollado con Laravel 12, Livewire 3 y MySQL, containerizado con Docker.

## Qué hace

Aplicación para administrar un complejo deportivo:
- Gestión de canchas (CRUD, múltiples imágenes, precios por cancha)
- Sistema de reservas con calendario interactivo
- Registro de clientes y su historial
- Generación de comprobantes en PDF
- Control de pagos y adelantos
- Roles de usuario (Admin/Empleado)
- Bloqueo de horarios para mantenimiento

## Stack

- Laravel 12 + Livewire 3
- MySQL 8.0
- PHP 8.2 + Apache 2.4
- Docker + Docker Compose
- Bootstrap (via Laravel UI)
- Spatie Permission para roles
- DomPDF para comprobantes

## Requisitos

- Docker Desktop
- Git
- WSL2 (si estás en Windows)

## Instalación

### Opción 1: Script automático (recomendado)

```bash
git clone <URL_DEL_REPOSITORIO>
cd gestionador_canchas
./setup.sh
```

El script configura todo: Docker, dependencias, base de datos, permisos, etc. Tarda unos 10-15 minutos.

### Opción 2: Manual

Si el script falla o prefieres hacerlo paso a paso:

```bash
# 1. Clonar repo
git clone <URL_DEL_REPOSITORIO>
cd gestionador_canchas

# 2. Configurar permisos Docker (Linux/macOS/WSL)
cp .env.docker.example .env.docker
echo "UID=$(id -u)" >> .env.docker
echo "GID=$(id -g)" >> .env.docker

# 3. Levantar contenedores
docker compose build
docker compose up -d

# 4. Setup Laravel
docker compose exec app bash -c "cd gambeta && composer install"
docker compose exec app bash -c "cd gambeta && cp .env.example .env"
docker compose exec app bash -c "cd gambeta && php artisan key:generate"
docker compose exec app chown -R www-data:www-data /var/www/html
docker compose exec app bash -c "cd gambeta && chmod -R 775 storage bootstrap/cache"

# 5. Base de datos
docker compose exec app bash -c "cd gambeta && php artisan migrate --seed"

# 6. Frontend
docker compose exec app bash -c "cd gambeta && npm install && npm run build"
```

### Acceso

- App: http://localhost:8080
- phpMyAdmin: http://localhost:8082 (root/rootpass)

Si ejecutaste seeders:
- Admin: admin@gambeta.com / password
- Empleado: empleado@gambeta.com / password

## Estructura del proyecto

```
gestionador_canchas/
├── dockerfile              # Imagen Docker desarrollo
├── docker-compose.yml      # Orquestación contenedores
├── setup.sh               # Script instalación automática
└── proyectos/gambeta/     # App Laravel
    ├── app/
    │   ├── Http/Controllers/  # 16 controladores
    │   ├── Livewire/          # 7 componentes
    │   └── Models/            # 11 modelos
    ├── database/migrations/   # 17 migraciones
    ├── resources/views/       # Vistas Blade
    └── routes/web.php         # Rutas
```

## Base de datos

**Tablas principales:**
- `users` - Usuarios del sistema (con roles)
- `canchas` - Canchas deportivas
- `cancha_imagenes` - Múltiples imágenes por cancha
- `cancha_precios` - Histórico de precios
- `bloqueos_horarios` - Bloqueos de tiempo
- `clientes` - Clientes del complejo
- `reservas` - Reservas (con estados: pendiente, confirmada, finalizada, cancelada)
- `pagos` - Registro de pagos
- `reservas_estados_historial` - Auditoría de cambios

## Comandos útiles

### Docker

```bash
# Levantar/detener
docker compose up -d
docker compose down

# Ver logs
docker compose logs -f app

# Entrar al contenedor
docker compose exec app bash

# Reiniciar todo desde cero
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### Laravel

```bash
# Ejecutar artisan
docker compose exec app bash -c "cd gambeta && php artisan [comando]"

# Ejemplos
docker compose exec app bash -c "cd gambeta && php artisan migrate"
docker compose exec app bash -c "cd gambeta && php artisan route:list"
docker compose exec app bash -c "cd gambeta && php artisan make:controller NombreController"
docker compose exec app bash -c "cd gambeta && php artisan make:livewire ComponenteNombre"

# Limpiar cachés
docker compose exec app bash -c "cd gambeta && php artisan cache:clear"
docker compose exec app bash -c "cd gambeta && php artisan config:clear"
docker compose exec app bash -c "cd gambeta && php artisan view:clear"
```

### Composer

```bash
docker compose exec app bash -c "cd gambeta && composer require paquete/nombre"
docker compose exec app bash -c "cd gambeta && composer update"
docker compose exec app bash -c "cd gambeta && composer dump-autoload"
```

### Base de datos

```bash
# Backup
docker compose exec db mysqldump -u appuser -papppass appdb > backup.sql

# Restaurar
docker compose exec -T db mysql -u appuser -papppass appdb < backup.sql

# Acceder a MySQL
docker compose exec db mysql -u root -prootpass
```

## Troubleshooting

### Error: "Permission denied" al editar archivos

Los archivos pertenecen a otro usuario. Solución:

```bash
# Verificar tu UID/GID
id -u
id -g

# Actualizar .env.docker con los valores correctos
nano .env.docker

# Reconstruir
docker compose down
docker compose build --no-cache
docker compose up -d

# Arreglar permisos existentes
docker compose exec app chown -R www-data:www-data /var/www/html
```

### Error: "Port already in use"

Cambiar puerto en `docker-compose.yml`:

```yaml
services:
  app:
    ports:
      - "8081:80"  # cambiar 8080 a 8081
```

O detener el proceso que usa el puerto:

```bash
# Linux/macOS
sudo lsof -i :8080
sudo kill -9 [PID]

# Windows
netstat -ano | findstr :8080
taskkill /PID [PID] /F
```

### MySQL no conecta

```bash
# Ver estado
docker compose ps

# Ver logs
docker compose logs db

# Esperar a que esté healthy (puede tardar 30-60 segundos)
watch docker compose ps

# Si persiste, recrear
docker compose down -v
docker compose up -d
```

### Error "Class not found"

```bash
docker compose exec app bash -c "cd gambeta && composer dump-autoload"
docker compose exec app bash -c "cd gambeta && php artisan cache:clear"
```

### Error 500

```bash
# Ver logs Laravel
docker compose exec app bash -c "cd gambeta && tail -f storage/logs/laravel.log"

# Ver logs Apache
docker compose logs app

# Verificar permisos
docker compose exec app bash -c "cd gambeta && chmod -R 775 storage bootstrap/cache"
docker compose exec app chown -R www-data:www-data /var/www/html
```

## Desarrollo

### Git workflow

```bash
# Antes de empezar
git pull origin main

# Crear rama
git checkout -b feature/nombre-feature

# Commits
git add .
git commit -m "Add: descripción"

# Push
git push origin feature/nombre-feature
```

### Convención de commits

- `Add:` Nueva funcionalidad
- `Fix:` Corrección de bugs
- `Update:` Actualización de feature
- `Refactor:` Refactorización
- `Docs:` Documentación

### Qué NO hacer

- NO commitear `.env` o `.env.docker`
- NO commitear `vendor/` o `node_modules/`
- NO hacer `chmod -R 777`
- NO hacer `git push --force` en `main`
- NO trabajar directo en `main`, usar ramas

## Testing

```bash
docker compose exec app bash -c "cd gambeta && php artisan test"
docker compose exec app bash -c "cd gambeta && php artisan test --filter=NombreTest"
docker compose exec app bash -c "cd gambeta && php artisan test --coverage"
```

## Producción

Ver [DESPLIEGUE_RENDER.md](DESPLIEGUE_RENDER.md) para deploy en Render.com.

Checklist antes de deploy:
- `APP_ENV=production`
- `APP_DEBUG=false`
- Generar nueva `APP_KEY`
- Configurar DB de producción
- `composer install --optimize-autoloader --no-dev`
- `npm run build`
- Configurar HTTPS/SSL
- Configurar backups

## Documentación adicional

- [GUIA_INSTALACION.md](GUIA_INSTALACION.md) - Instalación detallada por OS
- [SOLUCION_PERMISOS_DOCKER.md](SOLUCION_PERMISOS_DOCKER.md) - Troubleshooting permisos
- [DESPLIEGUE_RENDER.md](DESPLIEGUE_RENDER.md) - Deploy producción

## Recursos

- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Livewire v3 Docs](https://livewire.laravel.com/docs)
- [Spatie Permission](https://spatie.be/docs/laravel-permission/v6)
- [Docker Compose Reference](https://docs.docker.com/compose/)

## Licencia

Proyecto educativo - Trabajo Práctico Integrador (TPI)

---

**Última actualización:** Diciembre 2025
**Laravel:** 12.x | **Livewire:** 3.7 | **PHP:** 8.2
