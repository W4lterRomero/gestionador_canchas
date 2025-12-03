# Gambeta – Gestión de reservas deportivas

Este repositorio contiene la infraestructura Docker y el código de **Gambeta**, un panel interno para administrar canchas, reservas, clientes y bloqueos de horario. El proyecto principal está desarrollado en Laravel 12 + Livewire 3 y vive dentro de `proyectos/gambeta`.

## Estructura del repositorio

- `docker-compose.yml` y `dockerfile`: entorno de desarrollo local (PHP 8.2 + Apache + MySQL 8.0).  
- `Dockerfile.production`: build sin volúmenes para Render u otra plataforma Docker.  
- `.env.docker(.example)`: UID/GID del host para evitar problemas de permisos en desarrollo.  
- `proyectos/gambeta`: aplicación Laravel completa.  
- `docs/legacy/`: documentación histórica sobre despliegues o instalación. Úsala solo como referencia; no forma parte del flujo actual.

## Requisitos

- Docker Desktop 4.x (o Docker Engine + Docker Compose v2).  
- Node.js 20+ (solo para compilar los assets con Vite).  
- Make sure `id -u` y `id -g` funcionan en tu shell (Linux/macOS o WSL2).

## Puesta en marcha con Docker

```bash
# 1. Clonar el repo
git clone <url> gestionador_canchas
cd gestionador_canchas

# 2. Crear .env.docker con tus IDs
cat <<EOF > .env.docker
UID=$(id -u)
GID=$(id -g)
EOF

# 3. Levantar contenedores
docker compose up -d --build

# 4. Instalar dependencias de Laravel dentro del contenedor
docker compose exec app bash -lc "cd gambeta && composer install"

# 5. Configurar entorno de Laravel (solo primera vez)
docker compose exec app bash -lc 'cd gambeta && cp .env.example .env && php artisan key:generate && php artisan migrate --seed && php artisan storage:link'

# 6. Compilar assets (desde tu máquina, no en el contenedor)
cd proyectos/gambeta
npm install
npm run dev   # o npm run build para producción
```

La aplicación queda disponible en http://localhost:8080 y MySQL en el puerto 3307 (usuario `appuser`, contraseña `apppass`, base `appdb`).

## Credenciales sembradas

Al ejecutar `php artisan migrate --seed` obtienes dos usuarios iniciales:

| Rol      | Correo               | Contraseña |
|----------|----------------------|------------|
| Admin    | `admin@example.com`  | `1234`     |
| Empleado | `empleado@example.com` | `1234`   |

Los roles y permisos se gestionan con `spatie/laravel-permission`.

## Flujo diario de trabajo

```bash
# Encender
docker compose up -d

# Apagar
docker compose down

# Logs en vivo
docker compose logs -f app

# Ejecutar comandos artisan/composer
docker compose exec app bash -lc "cd gambeta && php artisan test"
```

## Configuraciones activas

- **Solo** `dockerfile` + `docker-compose.yml` se utilizan para desarrollo local.  
- `Dockerfile.production` se usa en integraciones continuas o despliegues externos.  
- Las guías antiguas (`docs/legacy/*`) se conservaron como referencia histórica y no deben seguirse para nuevas configuraciones.

## Documentación adicional

| Archivo | Descripción |
|---------|-------------|
| `docs/legacy/DESPLIEGUE_RENDER.md` | Pasos antiguos para Render. Útil como referencia rápida si se vuelve a usar esa plataforma. |
| `docs/legacy/GUIA_INSTALACION.md` | Guía paso a paso para instalar Docker/WSL. |
| `docs/legacy/SOLUCION_PERMISOS_DOCKER.md` | Explicación del ajuste de UID/GID en contenedores. |

Mantén este README como fuente de verdad. Si cambian los comandos o la infraestructura, actualízalo aquí y mueve cualquier nota obsoleta a `docs/legacy/`.
