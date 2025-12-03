# Gambeta - Sistema de Reservación de Canchas Deportivas

Sistema completo de gestión de reservas para complejos deportivos, desarrollado con las últimas tecnologías web.

## Descripción del Proyecto

**Gambeta** es una aplicación web moderna diseñada para gestionar reservas de canchas deportivas de forma eficiente. Permite administrar canchas, clientes, reservas, pagos y generar comprobantes en PDF. El sistema incluye un calendario interactivo en tiempo real usando Livewire, sistema de roles (Administrador/Empleado), y gestión completa de precios por cancha.

### Características Principales

- **Gestión de Canchas**: CRUD completo con múltiples imágenes por cancha
- **Sistema de Reservas**: Calendario interactivo con validación de choques horarios
- **Gestión de Clientes**: Registro de clientes frecuentes con historial
- **Generación de PDFs**: Comprobantes de reserva descargables
- **Sistema de Roles**: Control de permisos con Spatie Permission
- **Panel Administrativo**: Dashboard con estadísticas y reportes
- **Bloqueo de Horarios**: Para mantenimiento o eventos especiales
- **Gestión de Pagos**: Registro de adelantos y saldos

---

## Stack Tecnológico

| Componente | Tecnología | Versión |
|------------|------------|---------|
| **Framework Backend** | Laravel | 12.x |
| **Frontend Reactivo** | Livewire | 3.7 |
| **Base de Datos** | MySQL | 8.0 |
| **Servidor Web** | Apache | 2.4 |
| **Lenguaje** | PHP | 8.2 |
| **Contenedores** | Docker + Docker Compose | Latest |
| **UI Framework** | Laravel UI (Bootstrap) | 4.6 |
| **Generador de PDFs** | barryvdh/laravel-dompdf | 3.1 |
| **Roles y Permisos** | spatie/laravel-permission | 6.23 |
| **Build Tool** | Vite | Latest |

---

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **Docker Desktop** (v20.10 o superior) - [Descargar aquí](https://www.docker.com/products/docker-desktop)
- **Git** (v2.30 o superior) - [Descargar aquí](https://git-scm.com/)
- **WSL2** (solo Windows) - [Guía de instalación](https://docs.microsoft.com/es-es/windows/wsl/install)
- Al menos **4GB de RAM** disponible para Docker
- Al menos **5GB de espacio** en disco

### Verificar instalación

```bash
# Verificar Docker
docker --version
docker compose version

# Verificar Git
git --version

# Verificar WSL2 (solo Windows)
wsl --status
```

---

## Instalación

### Opción 1: Instalación Automática (Recomendado)

Para una instalación en **un solo comando** que configura todo automáticamente:

```bash
# 1. Clonar el repositorio
git clone <URL_DEL_REPOSITORIO>
cd gestionador_canchas

# 2. Ejecutar script de setup automático
./setup.sh
```

El script `setup.sh` hará automáticamente:
- ✅ Verificar Docker y dependencias
- ✅ Crear archivo `.env.docker` con tu UID/GID
- ✅ Construir imágenes Docker
- ✅ Levantar contenedores
- ✅ Crear archivo `.env` de Laravel desde `.env.example`
- ✅ Instalar dependencias de Composer
- ✅ Generar APP_KEY
- ✅ Configurar permisos
- ✅ Ejecutar migraciones
- ✅ Instalar dependencias de Node y compilar assets

**Tiempo estimado**: 10-15 minutos (dependiendo de tu conexión)

Una vez completado, accede a:
- **Aplicación**: [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin**: [http://localhost:8082](http://localhost:8082)

---

### Opción 2: Instalación Manual (Paso a Paso)

Si prefieres instalar manualmente o el script automático falla, sigue estos pasos:

#### 1. Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd gestionador_canchas
```

#### 2. Configurar permisos de Docker

**Linux/macOS/WSL:**

```bash
# Crear archivo de configuración con tu UID/GID
cp .env.docker.example .env.docker

# Obtener tu UID y GID
echo "UID=$(id -u)" >> .env.docker
echo "GID=$(id -g)" >> .env.docker

# Verificar
cat .env.docker
```

Este paso es crucial para evitar problemas de permisos. El archivo `.env.docker` NO debe subirse a Git (ya está en `.gitignore`).

#### 3. Construir y levantar los contenedores

```bash
# Construir imágenes Docker (primera vez, puede tardar 5-10 minutos)
docker compose build

# Levantar los contenedores en segundo plano
docker compose up -d

# Verificar que los 3 contenedores estén corriendo
docker compose ps
```

Deberías ver:
- `laravel_app` - Estado: Up
- `mysql80` - Estado: Up (healthy)
- `phpmyadmin` - Estado: Up

#### 4. Configurar Laravel

```bash
# Instalar dependencias de Composer
docker compose exec app bash -c "cd gambeta && composer install"

# Copiar archivo de entorno
docker compose exec app bash -c "cd gambeta && cp .env.example .env"

# Generar clave de aplicación
docker compose exec app bash -c "cd gambeta && php artisan key:generate"

# Configurar permisos correctos
docker compose exec app chown -R www-data:www-data /var/www/html
docker compose exec app bash -c "cd gambeta && chmod -R 775 storage bootstrap/cache"
```

#### 5. Configurar base de datos

El archivo `.env` de Laravel ya está configurado con los valores correctos para Docker, pero verifica que contenga:

```bash
# Ver configuración actual
docker compose exec app bash -c "cd gambeta && cat .env | grep DB_"
```

Debería mostrar:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=appdb
DB_USERNAME=appuser
DB_PASSWORD=apppass
```

#### 6. Ejecutar migraciones

```bash
# Ejecutar migraciones y seeders
docker compose exec app bash -c "cd gambeta && php artisan migrate --seed"
```

Si pregunta "Do you really wish to run this command?", responde `yes`.

#### 7. Compilar assets frontend

```bash
# Instalar dependencias de Node
docker compose exec app bash -c "cd gambeta && npm install"

# Compilar assets (desarrollo)
docker compose exec app bash -c "cd gambeta && npm run build"
```

#### 8. Acceder a la aplicación

- **Aplicación Laravel**: [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin**: [http://localhost:8082](http://localhost:8082)
  - Usuario: `root`
  - Contraseña: `rootpass`

**Credenciales de prueba** (si ejecutaste seeders):
- Admin: `admin@gambeta.com` / `password`
- Empleado: `empleado@gambeta.com` / `password`

---

## Estructura del Proyecto

```
gestionador_canchas/
├── dockerfile                      # Imagen Docker desarrollo
├── Dockerfile.production           # Imagen Docker producción
├── docker-compose.yml              # Orquestación de contenedores
├── .env.docker.example             # Plantilla configuración Docker
├── .env.docker                     # Tu configuración (NO en Git)
├── .dockerignore                   # Exclusiones Docker
├── .gitignore                      # Exclusiones Git
├── uploads.ini                     # Configuración PHP uploads
├── render.yaml                     # Deploy Render.com
├── README.md                       # Este archivo
├── GUIA_INSTALACION.md             # Guía detallada por SO
├── DESPLIEGUE_RENDER.md            # Guía producción
├── SOLUCION_PERMISOS_DOCKER.md     # Troubleshooting permisos
│
└── proyectos/
    └── gambeta/                    # Aplicación Laravel 12
        ├── app/
        │   ├── Http/
        │   │   ├── Controllers/    # 16 controladores
        │   │   └── Middleware/     # Middleware custom
        │   ├── Livewire/           # 7 componentes Livewire
        │   ├── Models/             # 11 modelos Eloquent
        │   ├── Providers/          # Service providers
        │   └── View/               # View composers
        │
        ├── bootstrap/
        ├── config/                 # Archivos de configuración
        ├── database/
        │   ├── migrations/         # 17 migraciones
        │   ├── factories/          # Model factories
        │   └── seeders/            # Datos de prueba
        │
        ├── public/                 # Assets públicos
        │   ├── images/             # Imágenes de canchas
        │   ├── css/                # Estilos compilados
        │   └── js/                 # Scripts compilados
        │
        ├── resources/
        │   ├── views/              # 32+ vistas Blade
        │   │   ├── layouts/        # Layouts principales
        │   │   ├── livewire/       # Vistas Livewire
        │   │   ├── administracion/ # Panel admin
        │   │   ├── estadios/       # Gestión canchas
        │   │   ├── reservas/       # Sistema reservas
        │   │   ├── clientes/       # Gestión clientes
        │   │   ├── pdf/            # Plantillas PDF
        │   │   └── auth/           # Autenticación
        │   ├── css/                # Estilos fuente
        │   └── js/                 # Scripts fuente
        │
        ├── routes/
        │   ├── web.php             # Rutas principales
        │   ├── api.php             # API routes
        │   └── console.php         # Comandos artisan
        │
        ├── storage/
        │   ├── app/                # Archivos aplicación
        │   ├── framework/          # Caché, sesiones, vistas
        │   └── logs/               # Logs de Laravel
        │
        ├── tests/                  # Tests PHPUnit
        ├── vendor/                 # Dependencias Composer
        ├── .env                    # Variables entorno Laravel
        ├── .env.example            # Plantilla .env
        ├── artisan                 # CLI Laravel
        ├── composer.json           # Dependencias PHP
        ├── composer.lock           # Lock dependencias
        ├── package.json            # Dependencias NPM
        ├── vite.config.js          # Configuración Vite
        └── phpunit.xml             # Configuración tests
```

---

## Base de Datos

### Tablas Principales

| Tabla | Descripción | Campos Clave |
|-------|-------------|--------------|
| **users** | Usuarios del sistema | id, name, email, password, activo |
| **roles** | Roles de usuario (Spatie) | id, name, guard_name |
| **permissions** | Permisos (Spatie) | id, name, guard_name |
| **canchas** | Canchas deportivas | id, nombre, tipo, precio_hora, activa |
| **cancha_imagenes** | Imágenes de canchas | id, cancha_id, imagen_url |
| **cancha_precios** | Histórico de precios | id, cancha_id, precio, vigencia_desde |
| **bloqueos_horarios** | Bloqueos de tiempo | id, cancha_id, fecha_inicio, fecha_fin |
| **clientes** | Clientes del complejo | id, nombre, telefono, email, frecuente |
| **reservas** | Reservas realizadas | id, cancha_id, cliente_id, fecha_inicio, estado |
| **pagos** | Pagos y adelantos | id, reserva_id, monto, tipo_pago |
| **reservas_estados_historial** | Auditoría de cambios | id, reserva_id, estado_anterior, estado_nuevo |

### Diagrama de Relaciones

```
users (1) ─── (N) reservas (creador)
roles (1) ─── (N) users
canchas (1) ─── (N) reservas
canchas (1) ─── (N) cancha_imagenes
canchas (1) ─── (N) cancha_precios
canchas (1) ─── (N) bloqueos_horarios
clientes (1) ─── (N) reservas
reservas (1) ─── (N) pagos
reservas (1) ─── (N) reservas_estados_historial
```

---

## Comandos Útiles

### Docker

```bash
# Levantar contenedores
docker compose up -d

# Detener contenedores
docker compose down

# Ver logs en tiempo real
docker compose logs -f app

# Ver logs de MySQL
docker compose logs -f db

# Reiniciar un servicio específico
docker compose restart app

# Reconstruir imágenes
docker compose build --no-cache

# Eliminar contenedores y volúmenes (CUIDADO: borra BD)
docker compose down -v

# Entrar al contenedor de Laravel
docker compose exec app bash

# Ver estado de contenedores
docker compose ps
```

### Laravel (Artisan)

```bash
# Ejecutar comandos artisan (desde el host)
docker compose exec app bash -c "cd gambeta && php artisan [comando]"

# O entrar al contenedor primero
docker compose exec app bash
cd gambeta

# Crear modelo con migración
php artisan make:model NombreModelo -m

# Crear controlador
php artisan make:controller NombreController

# Crear componente Livewire
php artisan make:livewire NombreComponente

# Ejecutar migraciones
php artisan migrate

# Rollback última migración
php artisan migrate:rollback

# Refrescar base de datos (CUIDADO: borra datos)
php artisan migrate:fresh --seed

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ver rutas registradas
php artisan route:list

# Ver configuración actual
php artisan config:show database

# Ejecutar tinker (REPL)
php artisan tinker
```

### Composer

```bash
# Instalar dependencia
docker compose exec app bash -c "cd gambeta && composer require vendor/package"

# Actualizar dependencias
docker compose exec app bash -c "cd gambeta && composer update"

# Actualizar autoload
docker compose exec app bash -c "cd gambeta && composer dump-autoload"

# Ver paquetes instalados
docker compose exec app bash -c "cd gambeta && composer show"

# Buscar paquetes
docker compose exec app bash -c "cd gambeta && composer search keyword"
```

### NPM / Vite

```bash
# Instalar dependencias
docker compose exec app bash -c "cd gambeta && npm install"

# Compilar assets (desarrollo)
docker compose exec app bash -c "cd gambeta && npm run dev"

# Compilar assets (producción)
docker compose exec app bash -c "cd gambeta && npm run build"

# Watch mode (auto-recompilación)
docker compose exec app bash -c "cd gambeta && npm run dev --watch"
```

### Base de Datos

```bash
# Backup de base de datos
docker compose exec db mysqldump -u appuser -papppass appdb > backup_$(date +%Y%m%d).sql

# Restaurar backup
docker compose exec -T db mysql -u appuser -papppass appdb < backup_20250101.sql

# Entrar a MySQL CLI
docker compose exec db mysql -u root -prootpass

# Ver bases de datos
docker compose exec db mysql -u root -prootpass -e "SHOW DATABASES;"
```

---

## Solución de Problemas Comunes

### Error: "Permission denied" al editar archivos

**Causa**: Los archivos pertenecen a un usuario diferente (root o www-data:33)

**Solución**:

```bash
# Verificar UID/GID actual
id -u
id -g

# Verificar .env.docker
cat .env.docker

# Si no coinciden, actualizar y reconstruir
nano .env.docker  # Editar con valores correctos
docker compose down
docker compose build --no-cache
docker compose up -d

# Arreglar permisos existentes
docker compose exec app chown -R www-data:www-data /var/www/html
```

### Error: "Port already in use"

**Solución 1**: Cambiar puertos en `docker-compose.yml`

```yaml
services:
  app:
    ports:
      - "8081:80"  # Cambiar 8080 a 8081
```

**Solución 2**: Detener proceso que usa el puerto

```bash
# Linux/macOS
sudo lsof -i :8080
sudo kill -9 [PID]

# Windows (PowerShell)
netstat -ano | findstr :8080
taskkill /PID [PID] /F
```

### Error: "Connection refused" MySQL

**Causa**: MySQL no está listo o tiene problemas

**Solución**:

```bash
# Verificar estado
docker compose ps

# Ver logs
docker compose logs db

# Esperar a que esté healthy
watch docker compose ps

# Si persiste, recrear
docker compose down -v
docker compose up -d
```

### Error: "Class not found"

**Solución**:

```bash
# Regenerar autoload
docker compose exec app bash -c "cd gambeta && composer dump-autoload"

# Limpiar caché
docker compose exec app bash -c "cd gambeta && php artisan cache:clear"
docker compose exec app bash -c "cd gambeta && php artisan config:clear"
```

### Error: "SQLSTATE[HY000] [2002]"

**Causa**: Laravel intenta conectar antes de que MySQL esté listo

**Solución**:

```bash
# Esperar 30 segundos y reintentar
docker compose restart app

# Verificar configuración .env
docker compose exec app bash -c "cd gambeta && cat .env | grep DB_"
```

### Composer muy lento

**Solución**:

```bash
# Habilitar cache de Composer
docker compose exec app composer config -g cache-files-maxsize 2048MiB

# Usar mirror de Packagist
docker compose exec app composer config -g repos.packagist composer https://packagist.org
```

### Error 500 en Laravel

**Pasos de diagnóstico**:

```bash
# Ver logs de Laravel
docker compose exec app bash -c "cd gambeta && tail -f storage/logs/laravel.log"

# Ver logs de Apache
docker compose logs app

# Verificar permisos
docker compose exec app bash -c "cd gambeta && ls -la storage/"

# Arreglar permisos
docker compose exec app bash -c "cd gambeta && chmod -R 775 storage bootstrap/cache"
docker compose exec app chown -R www-data:www-data /var/www/html
```

---

## Desarrollo y Contribución

### Git Workflow

```bash
# 1. Actualizar main
git checkout main
git pull origin main

# 2. Crear rama feature
git checkout -b feature/nombre-feature

# 3. Hacer cambios y commits
git add .
git commit -m "Add: descripción del cambio"

# 4. Push a tu rama
git push origin feature/nombre-feature

# 5. Crear Pull Request en GitHub
```

### Convención de Commits

- `Add:` - Nueva funcionalidad
- `Fix:` - Corrección de bugs
- `Update:` - Actualización de feature existente
- `Refactor:` - Refactorización sin cambiar funcionalidad
- `Docs:` - Cambios en documentación
- `Test:` - Agregar o modificar tests
- `Style:` - Cambios de formato (no afectan lógica)

### Buenas Prácticas

- **NO** commitear `.env` o `.env.docker`
- **NO** commitear `vendor/` o `node_modules/`
- **NO** hacer `chmod -R 777` (usar permisos correctos)
- **NO** usar `git push --force` en `main`
- **SÍ** escribir tests para nuevas funcionalidades
- **SÍ** documentar cambios importantes
- **SÍ** revisar código antes de hacer PR

---

## Testing

```bash
# Ejecutar todos los tests
docker compose exec app bash -c "cd gambeta && php artisan test"

# Ejecutar test específico
docker compose exec app bash -c "cd gambeta && php artisan test --filter=NombreTest"

# Con coverage
docker compose exec app bash -c "cd gambeta && php artisan test --coverage"

# Tests en paralelo
docker compose exec app bash -c "cd gambeta && php artisan test --parallel"
```

---

## Deployment (Producción)

Ver documentación detallada en:

- [DESPLIEGUE_RENDER.md](DESPLIEGUE_RENDER.md) - Deploy en Render.com
- [Dockerfile.production](Dockerfile.production) - Imagen optimizada para producción

**Checklist producción**:

- [ ] `APP_ENV=production` en `.env`
- [ ] `APP_DEBUG=false` en `.env`
- [ ] Generar `APP_KEY` nueva
- [ ] Configurar base de datos de producción
- [ ] Ejecutar `composer install --optimize-autoloader --no-dev`
- [ ] Ejecutar `npm run build`
- [ ] Configurar HTTPS/SSL
- [ ] Configurar backups automáticos
- [ ] Configurar logs y monitoreo

---

## Documentación Adicional

- **[GUIA_INSTALACION.md](GUIA_INSTALACION.md)** - Guía detallada por sistema operativo
- **[SOLUCION_PERMISOS_DOCKER.md](SOLUCION_PERMISOS_DOCKER.md)** - Troubleshooting de permisos
- **[DESPLIEGUE_RENDER.md](DESPLIEGUE_RENDER.md)** - Deploy en Render.com

### Recursos Externos

- [Documentación Laravel 12](https://laravel.com/docs/12.x)
- [Documentación Livewire v3](https://livewire.laravel.com/docs)
- [Spatie Permission Docs](https://spatie.be/docs/laravel-permission/v6)
- [Docker Compose Reference](https://docs.docker.com/compose/)
- [Laravel Bootcamp](https://bootcamp.laravel.com)

---

## Licencia

Este proyecto es parte de un Trabajo Práctico Integrador (TPI) con fines educativos.

---

## Soporte

Si encuentras problemas:

1. Revisa la sección [Solución de Problemas](#solución-de-problemas-comunes)
2. Consulta los logs: `docker compose logs app`
3. Revisa la documentación adicional
4. Contacta al equipo de desarrollo

---

**Última actualización**: Diciembre 2025
**Versión Laravel**: 12.x
**Versión Livewire**: 3.7
**Versión PHP**: 8.2
