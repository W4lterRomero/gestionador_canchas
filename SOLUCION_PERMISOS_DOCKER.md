# 🔧 Solución de Permisos en Docker para Laravel

## 📋 Tabla de Contenidos
1. [¿Cuál era el problema?](#cuál-era-el-problema)
2. [¿Cómo funciona la solución?](#cómo-funciona-la-solución)
3. [Archivos modificados](#archivos-modificados)
4. [Implementación paso a paso](#implementación-paso-a-paso)
5. [Explicación técnica detallada](#explicación-técnica-detallada)
6. [Solución de problemas](#solución-de-problemas)

---

## 🎯 ¿Cuál era el problema?

### El problema de permisos en Docker

Cuando usas Docker, los archivos creados dentro del contenedor pertenecen al usuario **root** o al usuario **www-data** (UID 33). Sin embargo, en tu máquina (el host), tu usuario tiene un UID diferente (generalmente 1000).

**Ejemplo del problema:**
```bash
# Dentro del contenedor Docker
$ whoami
www-data

$ id -u
33  # UID del usuario www-data en el contenedor

# En tu máquina (host)
$ whoami
Walter

$ id -u
1000  # Tu UID en el host
```

### ¿Por qué causaba problemas?

1. **Archivos creados en el contenedor** → UID 33 (www-data)
2. **Tu usuario en el host** → UID 1000 (Walter)
3. **Resultado:** No puedes editar los archivos sin `sudo` o `chmod -R 777`

**Esto sucedía cuando:**
- Laravel crea archivos de caché en `storage/framework`
- Composer instala paquetes en `vendor/`
- Se generan archivos de log
- Se ejecutan migraciones que crean archivos
- Se suben imágenes de canchas

---

## 🚀 ¿Cómo funciona la solución?

La solución hace que el usuario **www-data** dentro del contenedor tenga el **mismo UID y GID** que tu usuario en el host.

### Concepto clave: Sincronización de UID/GID

```
┌─────────────────────┐         ┌─────────────────────┐
│   TU MÁQUINA (HOST) │         │  CONTENEDOR DOCKER  │
├─────────────────────┤         ├─────────────────────┤
│                     │         │                     │
│  Usuario: Walter    │  ═══>   │  Usuario: www-data  │
│  UID: 1000          │         │  UID: 1000 (¡igual!)│
│  GID: 1000          │         │  GID: 1000 (¡igual!)│
│                     │         │                     │
└─────────────────────┘         └─────────────────────┘
         ↓                               ↓
    Los archivos tienen UID 1000 para ambos
    ✅ No hay conflictos de permisos
```

---

## 📁 Archivos modificados

### 1. `dockerfile` (modificado)

**Cambios principales:**
- Se agregaron argumentos `ARG USER_ID` y `GROUP_ID`
- Se modificó el usuario `www-data` para usar tu UID/GID
- Se agregó un script de entrada personalizado

```dockerfile
# NUEVO: Argumentos para recibir UID y GID desde docker-compose
ARG USER_ID=1000
ARG GROUP_ID=1000

# NUEVO: Modificar www-data para usar el mismo UID/GID que tu usuario
RUN groupmod -o -g ${GROUP_ID} www-data && \
    usermod -o -u ${USER_ID} -g www-data www-data

# NUEVO: Script de entrada que configura permisos automáticamente
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
```

### 2. `docker-compose.yml` (modificado)

**Cambios principales:**
- Se pasaron variables de entorno al build
- Se configuró el usuario del contenedor
- Se agregaron variables de entorno

```yaml
services:
  app:
    build:
      context: .
      args:
        USER_ID: ${USER_ID:-1000}      # Pasa el UID al Dockerfile
        GROUP_ID: ${GROUP_ID:-1000}    # Pasa el GID al Dockerfile
    environment:
      - USER_ID=${USER_ID:-1000}
      - GROUP_ID=${GROUP_ID:-1000}
    user: "${USER_ID:-1000}:${GROUP_ID:-1000}"  # Ejecuta como tu usuario
```

### 3. `.env.docker` (nuevo archivo)

Este archivo contiene la configuración de tu usuario:

```bash
USER_ID=1000
GROUP_ID=1000
```

### 4. `docker-entrypoint.sh` (nuevo archivo)

Script que se ejecuta cada vez que inicia el contenedor y configura permisos automáticamente:

```bash
#!/bin/bash
# Configura permisos de storage, bootstrap/cache, etc.
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage
# ... y más
```

---

## 🛠️ Implementación paso a paso

### Para ti (Walter) - Primera configuración

1. **Obtén tu UID y GID:**
```bash
id -u  # Debería devolver 1000
id -g  # Debería devolver 1000
```

2. **Verifica que `.env.docker` tenga tus valores:**
```bash
cat .env.docker
```

3. **Reconstruye los contenedores:**
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

4. **Verifica que funcione:**
```bash
docker-compose exec app id
# Debería mostrar: uid=1000(www-data) gid=1000(www-data)
```

### Para tus compañeros - Configuración en sus máquinas

1. **Clonar el repositorio:**
```bash
git clone <tu-repositorio>
cd proyectoTPI
```

2. **Obtener su UID y GID:**
```bash
id -u  # Ejemplo: 1001
id -g  # Ejemplo: 1001
```

3. **Editar el archivo `.env.docker`:**
```bash
nano .env.docker
```

Cambiar a sus valores:
```bash
USER_ID=1001    # Su UID
GROUP_ID=1001   # Su GID
```

4. **Construir y ejecutar:**
```bash
docker-compose build
docker-compose up -d
```

5. **¡Listo!** Ya no necesitarán hacer `chmod -R 777`

---

## 🧠 Explicación técnica detallada

### 1. ¿Qué es UID y GID?

- **UID (User ID):** Número único que identifica a un usuario en Linux
- **GID (Group ID):** Número único que identifica a un grupo en Linux

Los permisos de archivos se basan en estos números, NO en los nombres de usuario.

**Ejemplo:**
```bash
$ ls -ln proyectos/
-rw-r--r-- 1 1000 1000 4096 Nov 27 10:00 archivo.php
              ↑    ↑
             UID  GID
```

### 2. ¿Por qué `groupmod` y `usermod`?

```dockerfile
RUN groupmod -o -g ${GROUP_ID} www-data && \
    usermod -o -u ${USER_ID} -g www-data www-data
```

**Explicación:**
- `groupmod -o -g ${GROUP_ID} www-data`: Cambia el GID del grupo www-data
  - `-o`: Permite GID duplicados
  - `-g ${GROUP_ID}`: El nuevo GID (1000)

- `usermod -o -u ${USER_ID} -g www-data www-data`: Cambia el UID del usuario www-data
  - `-o`: Permite UID duplicados
  - `-u ${USER_ID}`: El nuevo UID (1000)
  - `-g www-data`: Asegura que pertenezca al grupo www-data

### 3. ¿Para qué sirve `docker-entrypoint.sh`?

Es un script que se ejecuta **cada vez** que el contenedor inicia, ANTES de Apache.

**Flujo de ejecución:**
```
1. Docker inicia el contenedor
2. Ejecuta docker-entrypoint.sh
   ├─ Configura permisos de storage/
   ├─ Configura permisos de bootstrap/cache/
   └─ Configura permisos de public/storage/
3. Ejecuta Apache (apache2-foreground)
```

**Ventaja:** Si agregas nuevos archivos o directorios, se configuran automáticamente.

### 4. ¿Qué significa `${USER_ID:-1000}`?

Es una variable con valor por defecto:
- Si `USER_ID` está definida, usa ese valor
- Si NO está definida, usa `1000` por defecto

**Ejemplo:**
```yaml
USER_ID: ${USER_ID:-1000}
```
- Si `.env.docker` tiene `USER_ID=1005` → usa 1005
- Si no existe `.env.docker` → usa 1000 por defecto

### 5. ¿Por qué `user: "${USER_ID:-1000}:${GROUP_ID:-1000}"`?

Esta línea en `docker-compose.yml` hace que los procesos dentro del contenedor se ejecuten con tu UID/GID.

**Sin esto:**
```bash
$ docker-compose exec app whoami
root  # ❌ Se ejecuta como root
```

**Con esto:**
```bash
$ docker-compose exec app whoami
www-data  # ✅ Se ejecuta como www-data (con tu UID)
```

---

## 🔍 Comparación: Antes vs Después

### ANTES (con problemas)

```bash
# Crear un archivo desde Laravel
$ docker-compose exec app php artisan make:model Cancha

# Ver permisos en el host
$ ls -l proyectos/app/Models/
-rw-r--r-- 1 33 33 512 Nov 27 10:00 Cancha.php
                ↑  ↑
              UID GID (www-data en el contenedor)

# Intentar editar
$ nano proyectos/app/Models/Cancha.php
# ❌ Error: Permiso denegado

# Solución temporal (mala práctica)
$ chmod -R 777 proyectos/
```

### DESPUÉS (con la solución)

```bash
# Crear un archivo desde Laravel
$ docker-compose exec app php artisan make:model Cancha

# Ver permisos en el host
$ ls -l proyectos/app/Models/
-rw-r--r-- 1 1000 1000 512 Nov 27 10:00 Cancha.php
                  ↑    ↑
                UID  GID (¡tu usuario!)

# Editar sin problemas
$ nano proyectos/app/Models/Cancha.php
# ✅ Funciona perfectamente

# Ya no necesitas chmod
```

---

## 🐛 Solución de problemas

### Problema 1: "Permission denied" al crear archivos

**Síntoma:**
```bash
$ docker-compose exec app composer install
Permission denied
```

**Solución:**
```bash
# Reconstruir los contenedores
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Problema 2: UID/GID incorrectos

**Verificar:**
```bash
# En el host
$ id -u && id -g
1000
1000

# En el contenedor
$ docker-compose exec app id
uid=1000(www-data) gid=1000(www-data)  # Deben coincidir
```

**Si no coinciden:**
1. Verifica `.env.docker`
2. Reconstruye: `docker-compose build --no-cache`

### Problema 3: Archivos antiguos con permisos incorrectos

**Solución:**
```bash
# Ejecutar el script de permisos manualmente
$ docker-compose exec app chown -R www-data:www-data /var/www/html
$ docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Problema 4: Diferentes UIDs entre compañeros

**Situación:**
- Walter tiene UID 1000
- Compañero 1 tiene UID 1001
- Compañero 2 tiene UID 1002

**Solución:**
Cada uno debe:
1. Editar su propio `.env.docker` con su UID
2. Reconstruir su contenedor localmente
3. **NO commitear** `.env.docker` al repositorio (agregarlo a `.gitignore`)

---

## 📚 Conceptos clave para aprender

### 1. Volúmenes de Docker

```yaml
volumes:
  - ./proyectos:/var/www/html:delegated
```

- `./proyectos`: Carpeta en tu máquina (host)
- `/var/www/html`: Carpeta en el contenedor
- `:delegated`: Optimización de rendimiento (macOS/Windows)

**Importante:** Los archivos son los MISMOS en ambos lados, por eso los permisos deben coincidir.

### 2. Build args vs Environment variables

**Build args (ARG):**
- Se usan DURANTE la construcción de la imagen
- No están disponibles en tiempo de ejecución
```dockerfile
ARG USER_ID=1000  # Solo para build
```

**Environment variables (ENV):**
- Están disponibles cuando el contenedor se ejecuta
```yaml
environment:
  - USER_ID=1000  # Disponible en runtime
```

### 3. ENTRYPOINT vs CMD

```dockerfile
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
```

- **ENTRYPOINT:** Comando principal que siempre se ejecuta
- **CMD:** Argumentos pasados al ENTRYPOINT (pueden sobrescribirse)

**Resultado:** Ejecuta `docker-entrypoint.sh apache2-foreground`

### 4. Permisos en Linux

```bash
chmod 775 archivo
  ↓
  7  7  5
  │  │  │
  │  │  └─ Otros: read+execute (r-x)
  │  └─── Grupo: read+write+execute (rwx)
  └───── Dueño: read+write+execute (rwx)
```

- `775`: Común para directorios compartidos (storage/)
- `644`: Común para archivos (archivos.php)
- `755`: Común para ejecutables (scripts)

---

## ✅ Checklist de verificación

Después de implementar, verifica que:

- [ ] `docker-compose build` funciona sin errores
- [ ] `docker-compose exec app id` muestra tu UID/GID
- [ ] Puedes crear archivos desde el contenedor y editarlos desde el host
- [ ] `storage/` y `bootstrap/cache/` tienen permisos 775
- [ ] Composer y Artisan funcionan sin `sudo`
- [ ] Las imágenes de canchas se pueden subir correctamente
- [ ] Los logs se generan sin errores de permisos

---

## 📖 Recursos adicionales

- [Docker User Namespaces](https://docs.docker.com/engine/security/userns-remap/)
- [Linux File Permissions](https://www.linux.com/training-tutorials/understanding-linux-file-permissions/)
- [Docker Compose Environment Variables](https://docs.docker.com/compose/environment-variables/)

---

## 🎓 Resumen para tus compañeros

**¿Qué hacer cuando clonen el proyecto?**

1. Ejecutar: `id -u` y `id -g`
2. Editar `.env.docker` con sus valores
3. Ejecutar: `docker-compose build && docker-compose up -d`
4. ¡Listo! Ya no más `chmod -R 777`

**¿Por qué funciona?**

Porque el usuario dentro del contenedor ahora tiene el mismo ID que tú, entonces todos los archivos creados te pertenecen automáticamente.

---

*Creado para el proyecto Gambeta - Sistema de Reservación de Canchas Deportivas*
