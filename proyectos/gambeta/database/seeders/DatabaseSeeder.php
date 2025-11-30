<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta los seeders de catálogo necesarios para pruebas
        $this->call([
            RolePermissionSeeder::class,
            CanchaSeeder::class,
            CanchaPrecioSeeder::class,
            ClienteSeeder::class,
        ]);

        // Ensure Spatie roles exist with consistent names
        $spatieAdminRole = \Spatie\Permission\Models\Role::findOrCreate('admin');
        $spatieEmployeeRole = \Spatie\Permission\Models\Role::findOrCreate('empleado');

        // Create one admin and one empleado with password '1234'
        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'role_id' => $spatieAdminRole->id,
            'name' => 'Administrador',
            'password' => Hash::make('1234'),
            'activo' => true,
        ]);

        User::updateOrCreate([
            'email' => 'empleado@example.com',
        ], [
            'role_id' => $spatieEmployeeRole->id,
            'name' => 'Empleado',
            'password' => Hash::make('1234'),
            'activo' => true,
        ]);

        // Assign Spatie roles to the created users
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser && ! $adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        $empleadoUser = User::where('email', 'empleado@example.com')->first();
        if ($empleadoUser && ! $empleadoUser->hasRole('empleado')) {
            $empleadoUser->assignRole('empleado');
        }

        $this->call([
            BloqueoHorarioSeeder::class,
            ReservaSeeder::class,
            ReservaEstadoHistorialSeeder::class,
            PagoSeeder::class,
        ]);
    }
}
