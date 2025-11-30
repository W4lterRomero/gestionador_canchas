<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use Spatie helper to avoid duplicate-role exceptions
        $admin = Role::findOrCreate('admin');
        if (empty($admin->description)) {
            $admin->description = 'Administrador del sistema con todos los permisos.';
            $admin->save();
        }

        $empleado = Role::findOrCreate('empleado');
        if (empty($empleado->description)) {
            $empleado->description = 'Empleado del sistema con permisos limitados.';
            $empleado->save();
        }
    }
}
