# Gambeta (Laravel + Livewire)

Aplicación interna para administrar las reservas del complejo deportivo Gambeta. Incluye módulos para canchas, clientes, calendarios, bloqueos de horario y gestión de pagos con flujos paso a paso en Livewire.

## Stack

- Laravel 12 (PHP 8.2)  
- Livewire 3 y componentes Alpine externos mínimos  
- Tailwind vía Vite  
- MySQL 8.0  
- Spatie Laravel Permission para roles `admin` y `empleado`

## Características destacadas

- **Flujo de reservas guiado**: selección de cancha, horario, cliente y confirmación de pago en un wizard Livewire.  
- **Calendario visual**: `App\Livewire\CalendarioReserva` muestra la disponibilidad real combinando reservas existentes y `BloqueoHorario`.  
- **Bloqueos administrativos**: desde el panel de admin se definen ventanas de mantenimiento que impiden tomar turnos.  
- **Gestión de clientes frecuentes**: historial, banderas y estadísticas básicas.  
- **PDF de comprobantes**: gracias a `barryvdh/laravel-dompdf`.

## Preparar el proyecto (sin Docker)

> Si estás usando el Docker del repositorio raíz, ejecuta los mismos comandos dentro del contenedor `app`.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

npm install
npm run dev   # o npm run build
```

Las migraciones crean los usuarios `admin@example.com` y `empleado@example.com` con contraseña `1234`.

## Scripts útiles

- `composer run setup`: instala dependencias, genera `.env`, ejecuta migraciones y compila assets.  
- `composer run dev`: arranca servidor artisan, cola de trabajos, logs de pail y Vite de manera concurrente (requiere `npx concurrently`).  
- `npm run dev` / `npm run build`: compila assets con Vite.

## Pruebas

```bash
php artisan test          # test suite de Laravel
```

## Dónde tocar

- **Livewire / UI**: `app/Livewire` y `resources/views/livewire`.  
- **Modelos/negocio**: `app/Models` y `app/Services` (si aplica).  
- **Policies y permisos**: `app/Providers/AuthServiceProvider.php` + `database/seeders/RolePermissionSeeder.php`.

Mantén esta guía sincronizada con el código antes de tocar producción o el Docker raíz.
