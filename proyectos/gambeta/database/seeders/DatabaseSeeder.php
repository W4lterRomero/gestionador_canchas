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
            RoleSeeder::class,
            CanchaSeeder::class,
            CanchaPrecioSeeder::class,
            ClienteSeeder::class,
        ]);

        $roles = Role::pluck('id', 'nombre');

        $adminRoleId = $roles->get('Administrador') ?? Role::factory()->create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol creado automáticamente.',
        ])->id;

        $employeeRoleId = $roles->get('Empleado') ?? Role::factory()->create([
            'nombre' => 'Empleado',
            'descripcion' => 'Rol creado automáticamente.',
        ])->id;

        User::factory()->create([
            'role_id' => $adminRoleId,
            'nombre' => 'Administrador1',
            'email' => 'admin@example.com',
            'password' => Hash::make('Administrador1'),
            'activo' => true,
        ]);

        User::factory()->create([
            'role_id' => $employeeRoleId,
            'nombre' => 'Empleado1',
            'email' => 'empleado1@example.com',
            'password' => Hash::make('Empleado1'),
            'activo' => true,
        ]);

        User::factory()->create([
            'role_id' => $employeeRoleId,
            'nombre' => 'Empleado2',
            'email' => 'empleado2@example.com',
            'password' => Hash::make('Empleado2'),
            'activo' => true,
        ]);

        $this->call([
            BloqueoHorarioSeeder::class,
            ReservaSeeder::class,
            PagoSeeder::class,
        ]);
    }
}
