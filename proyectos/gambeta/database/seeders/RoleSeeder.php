<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Crea roles base para catálogos del sistema.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso total al sistema, administración de canchas, reservas y reportes.',
            ],
            [
                'nombre' => 'Empleado',
                'descripcion' => 'Crear reservas, ver calendario, modificar estados y registrar pagos. ',
            ],
        ];

        foreach ($roles as $role) {
            Role::factory()->fromSeeder($role)->create();
        }
    }
}
