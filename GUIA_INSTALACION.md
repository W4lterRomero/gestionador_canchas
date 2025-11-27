# 🚀 Guía de Instalación - Proyecto Gambeta

Esta guía te llevará paso a paso para configurar el proyecto en tu máquina, ya sea Windows, macOS o Linux.

---

## 📋 Tabla de Contenidos

1. [Requisitos previos](#requisitos-previos)
2. [Instalación en Windows (con WSL)](#instalación-en-windows-con-wsl)
3. [Instalación en macOS](#instalación-en-macos)
4. [Instalación en Linux](#instalación-en-linux)
5. [Configuración del proyecto](#configuración-del-proyecto)
6. [Verificación de la instalación](#verificación-de-la-instalación)
7. [Solución de problemas](#solución-de-problemas)

---

## ✅ Requisitos previos

Antes de comenzar, asegúrate de tener instalado:

- **Git** - Para clonar el repositorio
- **Docker Desktop** - Para ejecutar los contenedores
- **Editor de código** - VSCode recomendado

---

## 💻 Instalación en Windows (con WSL)

### ⚠️ IMPORTANTE para usuarios de Windows

Este proyecto **requiere WSL2 (Windows Subsystem for Linux)** para funcionar correctamente. Docker en Windows sin WSL tiene problemas con los permisos de archivos.

### Paso 1: Instalar WSL2

```powershell
# Abrir PowerShell como Administrador y ejecutar:
wsl --install

# Reiniciar tu computadora cuando se te indique
```

Después del reinicio, configurarás tu usuario y contraseña de Ubuntu.

**Documentación oficial:** https://learn.microsoft.com/es-es/windows/wsl/install

### Paso 2: Instalar Docker Desktop

1. Descargar Docker Desktop desde: https://www.docker.com/products/docker-desktop
2. Durante la instalación, **asegurarse de habilitar WSL2**
3. Abrir Docker Desktop
4. Ir a **Settings → General**
5. Verificar que esté marcado: ✅ **"Use the WSL 2 based engine"**
6. Ir a **Settings → Resources → WSL Integration**
7. Activar: ✅ **"Enable integration with my default WSL distro"**

### Paso 3: Abrir WSL (Ubuntu)

```powershell
# En PowerShell o CMD, escribir:
wsl
```

Ahora estarás dentro de Ubuntu Linux. **Todos los comandos siguientes se ejecutan aquí.**

### Paso 4: Instalar Git en WSL (si no lo tienes)

```bash
sudo apt update
sudo apt install git -y
```

### Paso 5: Clonar el proyecto

```bash
# Navegar a tu carpeta home
cd ~

# Crear carpeta para proyectos
mkdir -p proyectos
cd proyectos

# Clonar el repositorio
git clone <URL_DEL_REPOSITORIO> gambeta
cd gambeta
```

### Paso 6: Obtener tu UID y GID

```bash
# Ejecutar estos comandos y anotar los resultados
id -u
# Resultado ejemplo: 1000

id -g
# Resultado ejemplo: 1000
```

### Paso 7: Crear tu archivo `.env.docker`

```bash
# Crear el archivo con tus valores
cat > .env.docker << EOF
USER_ID=1000
GROUP_ID=1000
EOF

# Reemplaza 1000 con los valores que obtuviste de id -u e id -g
```

O editar manualmente:

```bash
nano .env.docker
```

Escribir:
```
USER_ID=1000
GROUP_ID=1000
```

Guardar con `Ctrl + O`, Enter, salir con `Ctrl + X`

### Paso 8: Construir y levantar Docker

```bash
# Construir las imágenes (puede tardar 5-10 minutos la primera vez)
docker-compose build --no-cache

# Levantar los contenedores
docker-compose up -d

# Ver los logs para verificar que todo inició bien
docker-compose logs -f app
# Presionar Ctrl + C para salir de los logs
```

### Paso 9: Verificar que funciona

```bash
# Verificar que los permisos están correctos
docker-compose exec app id
# Debe mostrar: uid=1000(www-data) gid=1000(www-data)

# Verificar que los contenedores están corriendo
docker-compose ps
# Todos deben estar "Up" o "healthy"
```

### Paso 10: Crear el proyecto Laravel 12

```bash
# Entrar al contenedor
docker-compose exec app bash

# Dentro del contenedor, crear Laravel 12
composer create-project laravel/laravel:^12.0 .

# Salir del contenedor
exit
```

### Paso 11: Configurar Laravel

Editar el archivo `.env` de Laravel:

```bash
# Desde WSL
nano proyectos/.env
```

O abrirlo con VSCode:
```bash
code proyectos/.env
```

Actualizar estas líneas:
```env
APP_NAME=Gambeta
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=appdb
DB_USERNAME=appuser
DB_PASSWORD=apppass
```

### Paso 12: Instalar Livewire v3

```bash
docker-compose exec app composer require livewire/livewire
```

### Paso 13: Ejecutar migraciones

```bash
docker-compose exec app php artisan migrate
```

### ✅ ¡Listo! Acceder a la aplicación

- **Laravel:** http://localhost:8080
- **PhpMyAdmin:** http://localhost:8082

---

## 🍎 Instalación en macOS

### Paso 1: Instalar Homebrew (si no lo tienes)

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### Paso 2: Instalar Git

```bash
brew install git
```

### Paso 3: Instalar Docker Desktop

1. Descargar desde: https://www.docker.com/products/docker-desktop
2. Arrastrar Docker.app a Aplicaciones
3. Abrir Docker Desktop
4. Esperar a que inicie completamente

### Paso 4: Clonar el proyecto

```bash
# En Terminal
cd ~
mkdir -p proyectos
cd proyectos

git clone <URL_DEL_REPOSITORIO> gambeta
cd gambeta
```

### Paso 5: Obtener tu UID y GID

```bash
id -u  # Anota el resultado (ejemplo: 501)
id -g  # Anota el resultado (ejemplo: 20)
```

### Paso 6: Crear `.env.docker`

```bash
cat > .env.docker << EOF
USER_ID=501
GROUP_ID=20
EOF

# Reemplaza con tus valores reales
```

### Paso 7: Construir y levantar Docker

```bash
docker-compose build --no-cache
docker-compose up -d
docker-compose logs -f app
# Ctrl + C para salir
```

### Paso 8: Crear Laravel 12

```bash
docker-compose exec app bash
composer create-project laravel/laravel:^12.0 .
exit
```

### Paso 9: Configurar y ejecutar

```bash
# Editar .env de Laravel
nano proyectos/.env
# Actualizar las credenciales de DB como en Windows

# Instalar Livewire
docker-compose exec app composer require livewire/livewire

# Migrar base de datos
docker-compose exec app php artisan migrate
```

### ✅ Acceder a la aplicación

- **Laravel:** http://localhost:8080
- **PhpMyAdmin:** http://localhost:8082

---

## 🐧 Instalación en Linux (Ubuntu/Debian)

### Paso 1: Instalar Docker

```bash
# Actualizar paquetes
sudo apt update

# Instalar dependencias
sudo apt install apt-transport-https ca-certificates curl software-properties-common -y

# Agregar clave GPG de Docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# Agregar repositorio de Docker
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker
sudo apt update
sudo apt install docker-ce docker-ce-cli containerd.io docker-compose-plugin -y

# Agregar tu usuario al grupo docker
sudo usermod -aG docker $USER

# Cerrar sesión y volver a iniciar sesión para aplicar cambios
```

### Paso 2: Instalar Docker Compose (standalone)

```bash
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### Paso 3: Verificar instalación

```bash
docker --version
docker-compose --version
```

### Paso 4: Clonar el proyecto

```bash
cd ~
mkdir -p proyectos
cd proyectos

git clone <URL_DEL_REPOSITORIO> gambeta
cd gambeta
```

### Paso 5: Obtener UID y GID

```bash
id -u  # Ejemplo: 1000
id -g  # Ejemplo: 1000
```

### Paso 6: Crear `.env.docker`

```bash
cat > .env.docker << EOF
USER_ID=1000
GROUP_ID=1000
EOF
```

### Paso 7: Construir y levantar

```bash
docker-compose build --no-cache
docker-compose up -d
```

### Paso 8: Crear Laravel 12

```bash
docker-compose exec app bash
composer create-project laravel/laravel:^12.0 .
exit
```

### Paso 9: Configurar y ejecutar

```bash
# Editar .env
nano proyectos/.env
# Configurar DB

# Instalar Livewire
docker-compose exec app composer require livewire/livewire

# Migrar
docker-compose exec app php artisan migrate
```

### ✅ Acceder

- **Laravel:** http://localhost:8080
- **PhpMyAdmin:** http://localhost:8082

---

## 🔧 Configuración del proyecto

### Estructura del archivo `.env` de Laravel

Después de crear el proyecto Laravel, edita `proyectos/.env`:

```env
APP_NAME=Gambeta
APP_ENV=local
APP_KEY=base64:... (se genera automáticamente)
APP_DEBUG=true
APP_TIMEZONE=America/Mexico_City
APP_URL=http://localhost:8080

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=appdb
DB_USERNAME=appuser
DB_PASSWORD=apppass

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### Instalar dependencias adicionales

```bash
# Livewire v3 (obligatorio para el proyecto)
docker-compose exec app composer require livewire/livewire

# Spatie Permission (para roles: Administrador, Empleado)
docker-compose exec app composer require spatie/laravel-permission

# Laravel Debugbar (para desarrollo)
docker-compose exec app composer require barryvdh/laravel-debugbar --dev

# DomPDF (para generar comprobantes PDF)
docker-compose exec app composer require barryvdh/laravel-dompdf
```

---

## ✅ Verificación de la instalación

### Checklist completo

Ejecuta cada comando y verifica que funcione:

```bash
# 1. Contenedores corriendo
docker-compose ps
# Todos deben estar "Up" o "healthy"

# 2. Permisos correctos
docker-compose exec app id
# Debe mostrar tu UID/GID

# 3. Composer funciona
docker-compose exec app composer --version

# 4. PHP funciona
docker-compose exec app php --version
# Debe mostrar PHP 8.2.x

# 5. Laravel funciona
docker-compose exec app php artisan --version
# Debe mostrar Laravel Framework 12.x

# 6. Base de datos conecta
docker-compose exec app php artisan migrate:status
# Debe mostrar las migraciones sin error

# 7. Livewire instalado
docker-compose exec app composer show livewire/livewire
# Debe mostrar la versión instalada

# 8. Acceder desde el navegador
# http://localhost:8080 → Debe mostrar la página de bienvenida de Laravel
# http://localhost:8082 → Debe mostrar phpMyAdmin
```

### Crear un archivo de prueba

```bash
# Crear un modelo de prueba
docker-compose exec app php artisan make:model Cancha -m

# Verificar que puedes editar el archivo sin problemas
ls -l proyectos/app/Models/Cancha.php
# Debe mostrar tu usuario como dueño (no root, no www-data con UID 33)
```

---

## 🐛 Solución de problemas

### Problema 1: "docker-compose: command not found" (Windows WSL)

**Causa:** Docker Desktop no está integrado con WSL.

**Solución:**
1. Abrir Docker Desktop
2. Settings → Resources → WSL Integration
3. Activar tu distribución de Ubuntu
4. Reiniciar WSL: `wsl --shutdown` en PowerShell, luego volver a abrir

### Problema 2: "Permission denied" al crear archivos

**Causa:** `.env.docker` tiene UID/GID incorrecto.

**Solución:**
```bash
# Verificar tu UID actual
id -u
id -g

# Actualizar .env.docker
nano .env.docker
# Cambiar a tus valores reales

# Reconstruir
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Problema 3: Puerto 8080 ya está en uso

**Síntoma:**
```
Error: bind: address already in use
```

**Solución:**
```bash
# Opción 1: Cambiar puerto en docker-compose.yml
# Cambiar "8080:80" por "8081:80"

# Opción 2: Detener el proceso que usa el puerto
# Windows:
netstat -ano | findstr :8080
taskkill /PID <PID> /F

# Linux/macOS:
sudo lsof -i :8080
sudo kill -9 <PID>
```

### Problema 4: Base de datos no conecta

**Síntoma:**
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solución:**
```bash
# Verificar que MySQL esté healthy
docker-compose ps
# db debe mostrar "healthy", no "starting"

# Si está "starting", esperar 30 segundos y verificar logs
docker-compose logs db

# Si persiste, recrear la base de datos
docker-compose down -v
docker-compose up -d
```

### Problema 5: Composer muy lento

**Causa:** Composer descarga desde internet cada vez.

**Solución:**
```bash
# Activar caché de Composer
docker-compose exec app composer config --global cache-files-dir /tmp
```

### Problema 6: No puedo editar archivos desde Windows

**Causa (Windows):** El proyecto está en el sistema de archivos de Windows, no de WSL.

**Solución:**
```bash
# El proyecto DEBE estar en WSL, NO en /mnt/c/
# Ubicación correcta: /home/tu-usuario/proyectos/gambeta
# Ubicación incorrecta: /mnt/c/Users/tu-usuario/proyectos/gambeta

# Mover el proyecto a WSL
cd ~
mv /mnt/c/Users/tu-usuario/proyectos/gambeta ~/proyectos/
```

### Problema 7: "exec format error" al iniciar contenedor

**Causa:** El archivo `docker-entrypoint.sh` tiene formato Windows (CRLF).

**Solución:**
```bash
# Convertir a formato Unix (LF)
sudo apt install dos2unix -y
dos2unix docker-entrypoint.sh

# Reconstruir
docker-compose build --no-cache
docker-compose up -d
```

### Problema 8: WSL muy lento en Windows

**Solución:**
```bash
# Agregar .wslconfig en Windows
# En PowerShell:
notepad C:\Users\TU_USUARIO\.wslconfig

# Agregar:
[wsl2]
memory=4GB
processors=2
swap=2GB
```

Reiniciar WSL:
```powershell
wsl --shutdown
wsl
```

---

## 🎯 Comandos útiles del día a día

### Iniciar proyecto

```bash
cd ~/proyectos/gambeta  # o tu ruta
docker-compose up -d
```

### Detener proyecto

```bash
docker-compose down
```

### Ver logs en tiempo real

```bash
docker-compose logs -f app
docker-compose logs -f db
```

### Ejecutar comandos de Laravel

```bash
# Forma 1: Entrar al contenedor
docker-compose exec app bash
php artisan make:model Reserva
exit

# Forma 2: Ejecutar directo
docker-compose exec app php artisan make:model Reserva
```

### Limpiar todo y empezar de cero

```bash
# ⚠️ CUIDADO: Esto BORRA la base de datos
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

---

## 📞 ¿Necesitas ayuda?

Si después de seguir esta guía sigues teniendo problemas:

1. Verifica que completaste TODOS los pasos
2. Revisa la sección de [Solución de problemas](#solución-de-problemas)
3. Consulta [SOLUCION_PERMISOS_DOCKER.md](SOLUCION_PERMISOS_DOCKER.md) para más detalles técnicos
4. Contacta al equipo

---

## 🎓 Próximos pasos

Una vez que tengas todo funcionando:

1. Lee el [README.md](README.md) del proyecto
2. Familiarízate con los comandos de Laravel y Livewire
3. Empieza a desarrollar las funcionalidades de Gambeta
4. Haz commits frecuentes a GitHub

---

**¡Bienvenido al equipo de desarrollo de Gambeta! 🏟️⚽**
