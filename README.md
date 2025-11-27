# 🏟️ Gambeta - Sistema de Reservación de Canchas Deportivas

Sistema de gestión de reservas para el complejo deportivo Gambeta.

**Stack:** Laravel 12 + Livewire v3 + MySQL 8.0 + Docker

---

## 📦 Requisitos Previos

- **Docker Desktop** instalado y corriendo
- **Git** instalado
- **WSL2** (solo si usas Windows)

---

## 🚀 Instalación para Nuevos Miembros del Equipo

### Paso 1: Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd proyectoTPI
```

### Paso 2: Configurar tu UID y GID

**¿Por qué?** Para que los archivos creados dentro del contenedor te pertenezcan y puedas editarlos sin problemas de permisos.

```bash
# Obtener tu UID y GID
id -u  # Anota este número (ejemplo: 1000)
id -g  # Anota este número (ejemplo: 1000)
```

### Paso 3: Crear tu archivo `.env.docker`

```bash
# Copiar la plantilla
cp .env.docker.example .env.docker

# Editar con tus valores
nano .env.docker
```

Actualiza el archivo con TUS valores:

```bash
UID=1000  # Reemplaza con el resultado de 'id -u'
GID=1000  # Reemplaza con el resultado de 'id -g'
```

**Importante:** Este archivo es personal, NO lo subas a GitHub.

### Paso 4: Construir y levantar los contenedores

```bash
# Construir las imágenes (primera vez, puede tardar 5-10 min)
docker-compose build

# Levantar los contenedores
docker-compose up -d

# Verificar que estén corriendo
docker-compose ps
```

Deberías ver 3 contenedores: `laravel_app`, `mysql80`, `phpmyadmin`

### Paso 5: Instalar dependencias de Laravel

```bash
# Instalar dependencias de Composer
docker-compose exec app bash -c "cd gambeta && composer install"

# Configurar permisos (importante)
docker-compose exec app chown -R www-data:www-data /var/www/html

# Generar key de Laravel
docker-compose exec app bash -c "cd gambeta && php artisan key:generate"
```

**Nota:** El proyecto Laravel ya está en `proyectos/gambeta/` y Apache está configurado para apuntar automáticamente a `gambeta/public`.























### Paso 7: Ejecutar migraciones

```bash
docker-compose exec app bash -c "cd gambeta && php artisan migrate"
```

**Nota:** Si te pregunta si quieres crear la base de datos, responde `yes`.

### ✅ ¡Listo! Ahora accede a:

- **Aplicación Laravel:** http://localhost:8080
- **PhpMyAdmin:** http://localhost:8082
  - Usuario: `root`
  - Contraseña: `rootpass`

---

## 🛠️ Comandos del Día a Día

### Iniciar/Detener el proyecto

```bash
# Levantar contenedores
docker-compose up -d

# Detener contenedores
docker-compose down

# Ver logs en tiempo real
docker-compose logs -f app
```

### Trabajar con Laravel

```bash
# Ejecutar comandos artisan (desde fuera del contenedor)
docker-compose exec app bash -c "cd gambeta && php artisan make:model Cancha -m"
docker-compose exec app bash -c "cd gambeta && php artisan migrate"
docker-compose exec app bash -c "cd gambeta && php artisan route:list"

# O entrar al contenedor y trabajar dentro
docker-compose exec app bash
cd gambeta
php artisan make:controller ReservaController
php artisan make:livewire CalendarioReservas
exit
```

### Trabajar con Composer

```bash
# Instalar paquetes
docker-compose exec app bash -c "cd gambeta && composer require paquete/nombre"

# Actualizar dependencias
docker-compose exec app bash -c "cd gambeta && composer update"

# Ver paquetes instalados
docker-compose exec app bash -c "cd gambeta && composer show"
```

### Limpiar caché de Laravel

```bash
docker-compose exec app bash -c "cd gambeta && php artisan cache:clear"
docker-compose exec app bash -c "cd gambeta && php artisan config:clear"
docker-compose exec app bash -c "cd gambeta && php artisan view:clear"
docker-compose exec app bash -c "cd gambeta && php artisan route:clear"
```

---

## ⚙️ Cómo Funciona la Solución de Permisos

### El Problema

Cuando usas Docker, normalmente los archivos creados dentro del contenedor pertenecen a un usuario diferente (root o www-data con UID 33). Esto causa que no puedas editarlos desde tu editor de código sin hacer `chmod -R 777` (lo cual es inseguro).

### La Solución

Este proyecto configura el usuario `www-data` dentro del contenedor para que tenga **el mismo UID y GID que tu usuario** en tu máquina.

```
Tu máquina (host)          Contenedor Docker
─────────────────          ─────────────────
Usuario: Walter            Usuario: www-data
UID: 1000          ════>   UID: 1000 (¡mismo!)
GID: 1000                  GID: 1000 (¡mismo!)

Resultado: Los archivos te pertenecen en ambos lados ✅
```

### ¿Qué hace el sistema automáticamente?

1. Lee tu `UID` y `GID` desde `.env.docker`
2. Construye la imagen Docker con esos valores
3. Modifica el usuario `www-data` para que use tu UID/GID
4. Apache ejecuta como `www-data` (que ahora tiene tus permisos)
5. Todos los archivos creados tienen el dueño correcto

### Beneficios

- ✅ Puedes editar cualquier archivo sin `sudo` o `chmod`
- ✅ No hay conflictos de permisos entre el contenedor y tu máquina
- ✅ Cada miembro del equipo configura su propio UID/GID
- ✅ Funciona en Windows (WSL), macOS y Linux
- ✅ Compatible con Apache, Nginx, y cualquier servidor web

---

## 🔧 Solución de Problemas Comunes

### Problema 1: "Permission denied" al editar archivos en VSCode

**Causa:** Los archivos dentro de `proyectos/` pertenecen a `root` en lugar de a tu usuario.

**Esto pasa si:** Creaste el proyecto Laravel ANTES de reconstruir el contenedor con `.env.docker`.

**Solución:**

```bash
# Desde tu terminal, fuera del contenedor:
# Cambiar dueño de todos los archivos
docker-compose exec app chown -R www-data:www-data /var/www/html

# Verificar que ahora pertenecen a tu usuario
ls -la proyectos/gambeta/
# Deberías ver tu usuario (ej: Walter) como dueño
```

**Prevención:** Siempre sigue este orden:
1. Crear `.env.docker`
2. Ejecutar `docker-compose build`
3. Ejecutar `docker-compose up -d`
4. **LUEGO** crear el proyecto Laravel

---

### Problema 2: ".env.docker con UID/GID incorrecto"

**Síntoma:** Archivos nuevos no tienen el dueño correcto.

**Solución:**

```bash
# Verificar tus valores reales
id -u
id -g

# Verificar lo que tiene .env.docker
cat .env.docker

# Si son diferentes, editar
nano .env.docker

# Reconstruir contenedores
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

Edita `docker-compose.yml` y cambia el puerto:

```yaml
ports:
  - "8081:80"  # Cambiar 8080 por 8081
```

### Problema 4: MySQL no conecta

**Síntoma:**

```
SQLSTATE[HY000] [2002] Connection refused
```

**Solución:**

```bash
# Verificar que MySQL esté healthy
docker-compose ps

# Si no está healthy, espera 30 segundos y vuelve a verificar
# O revisa los logs
docker-compose logs db

# Si persiste, recrear desde cero
docker-compose down -v
docker-compose up -d
```

### Problema 5: Composer muy lento

**Solución:**

```bash
# Habilitar cache de Composer
docker-compose exec app composer global config cache-files-maxsize 2048MiB
```

### Problema 6: No puedo editar archivos desde VSCode (Windows)

**Causa:** El proyecto está en el sistema de archivos de Windows, no de WSL.

**Solución:**

El proyecto DEBE estar en WSL, no en `/mnt/c/`:

```bash
# ✅ Correcto (dentro de WSL)
/home/tu-usuario/proyectos/gambeta

# ❌ Incorrecto (en Windows)
/mnt/c/Users/tu-usuario/proyectos/gambeta
```

Si está en Windows, muévelo a WSL:

```bash
cd ~
mkdir -p proyectos
mv /mnt/c/Users/tu-usuario/proyectos/gambeta ~/proyectos/
```

---

## 📁 Estructura del Proyecto

```
proyectoTPI/
├── dockerfile                  # Imagen Docker para desarrollo
├── Dockerfile.production       # Imagen Docker para producción
├── docker-compose.yml          # Orquestación de contenedores
├── .env.docker.example         # Plantilla de configuración (✅ en Git)
├── .env.docker                 # Tu configuración personal (❌ NO en Git)
├── .gitignore                  # Archivos ignorados por Git
├── README.md                   # Este archivo
├── GUIA_INSTALACION.md         # Guía detallada por sistema operativo
├── DESPLIEGUE_RENDER.md        # Guía para desplegar en producción
└── proyectos/                  # Proyecto Laravel 12
    ├── app/
    ├── resources/
    ├── routes/
    ├── database/
    └── ...
```

---

## 📚 Requerimientos del Proyecto Gambeta

### Funcionalidades a Implementar

1. **Gestión de canchas**
   - Registrar canchas (nombre, tipo, precio/hora)
   - Subir fotografías de canchas
   - Editar y eliminar canchas

2. **Calendario de reservas**
   - Vista de calendario interactiva con Livewire
   - Mostrar horarios disponibles
   - Selección de fecha, hora y duración
   - Validación automática de choques de horario

3. **Gestión de reservas**
   - Registrar cliente (nombre, teléfono, equipo/grupo)
   - Crear reserva con precio total calculado
   - Cambiar estados:
     - Pendiente
     - Confirmada
     - Cancelada
     - Finalizada

4. **Pago y comprobantes**
   - Registrar pagos o adelantos
   - Generar comprobantes en PDF descargables

5. **Panel de administración**
   - Ver todas las reservas por fecha y cancha
   - Bloquear horarios para mantenimiento/eventos
   - Gestionar precios de canchas

6. **Sistema de roles**
   - **Administrador:** Acceso total
   - **Empleado de recepción:** Crear reservas, ver calendario, cambiar estados. NO puede eliminar canchas ni cambiar precios.

7. **Historial**
   - Todas las reservas de cada cancha
   - Registro de clientes frecuentes

### Stack Tecnológico

| Componente | Tecnología |
|------------|------------|
| Framework Backend | Laravel 12 |
| Frontend Reactivo | Livewire v3 |
| Base de Datos | MySQL 8.0 |
| Servidor Web | Apache 2.4 |
| PHP | 8.2 |
| Contenedores | Docker + Docker Compose |
| Autenticación | Laravel Breeze/Jetstream |
| PDFs | barryvdh/laravel-dompdf |
| Permisos/Roles | spatie/laravel-permission |

---

## 🎯 Convenciones del Equipo

### Git Workflow

```bash
# 1. Antes de empezar a trabajar, actualiza
git pull origin main

# 2. Crea una rama para tu feature
git checkout -b feature/calendario-reservas

# 3. Haz commits descriptivos
git add .
git commit -m "Add: Vista de calendario con Livewire"

# 4. Push a tu rama
git push origin feature/calendario-reservas

# 5. Crea Pull Request en GitHub
```

### Nombres de Commits

- `Add:` - Nueva funcionalidad
- `Fix:` - Corrección de bugs
- `Update:` - Actualización de funcionalidad existente
- `Refactor:` - Refactorización de código
- `Docs:` - Cambios en documentación

### Estructura de Base de Datos

**Tablas principales:**

- `canchas` - Información de canchas
- `reservas` - Reservas realizadas
- `clientes` - Datos de clientes
- `pagos` - Registro de pagos
- `users` - Usuarios del sistema (admin/empleados)

---

## 🚫 Qué NO Hacer

- ❌ NO subir `.env.docker` a GitHub (es personal)
- ❌ NO ejecutar `chmod -R 777` (el sistema maneja permisos automáticamente)
- ❌ NO hacer `git push --force` en `main`
- ❌ NO commitear archivos `vendor/` o `node_modules/`
- ❌ NO trabajar directo en `main`, usar ramas
- ❌ NO modificar `docker-compose.yml` sin avisar al equipo

---

## ✅ Checklist de Primera Vez

Usa esto para verificar que todo está correcto:

- [ ] Docker Desktop instalado y corriendo
- [ ] WSL2 configurado (solo Windows)
- [ ] Repositorio clonado
- [ ] `.env.docker` creado con MI UID/GID
- [ ] `docker-compose build` ejecutado sin errores
- [ ] `docker-compose up -d` levanta 3 contenedores
- [ ] Laravel 12 instalado en `proyectos/gambeta/`
- [ ] `proyectos/gambeta/.env` configurado con credenciales de DB
- [ ] Livewire v3 instalado
- [ ] Migraciones ejecutadas sin errores
- [ ] http://localhost:8080 muestra Laravel
- [ ] http://localhost:8082 muestra phpMyAdmin
- [ ] Puedo crear archivos desde el contenedor y editarlos sin problemas

---

## 📞 ¿Necesitas Ayuda?

### Documentación adicional

- **[GUIA_INSTALACION.md](GUIA_INSTALACION.md)** - Instalación paso a paso para Windows/macOS/Linux
- **[DESPLIEGUE_RENDER.md](DESPLIEGUE_RENDER.md)** - Cómo desplegar en producción

### Comandos de ayuda

```bash
# Ver estado de contenedores
docker-compose ps

# Ver logs de la aplicación
docker-compose logs -f app

# Ver logs de MySQL
docker-compose logs -f db

# Reiniciar todo desde cero
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

### Contacto

Si tienes problemas que no puedes resolver:

1. Revisa la sección de [Solución de Problemas](#solución-de-problemas-comunes)
2. Consulta [GUIA_INSTALACION.md](GUIA_INSTALACION.md)
3. Busca el error en los logs: `docker-compose logs app`
4. Contacta al equipo

---

## 🎓 Recursos de Aprendizaje

### Laravel 12
- [Documentación Oficial](https://laravel.com/docs/12.x)
- [Laravel Bootcamp](https://bootcamp.laravel.com)

### Livewire v3
- [Documentación Oficial](https://livewire.laravel.com/docs)
- [Screencasts](https://laracasts.com/series/livewire-uncovered)

### Docker
- [Docker para Desarrolladores](https://docs.docker.com/get-started/)
- [Docker Compose](https://docs.docker.com/compose/)

---
