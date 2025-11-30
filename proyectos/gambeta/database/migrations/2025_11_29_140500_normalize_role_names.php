<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Canonical names we want to keep
        $canonicalMap = [
            'administrador' => 'admin',
            'administrators' => 'admin',
            'adminstrador' => 'admin',
            'admin' => 'admin',
            'empleado' => 'empleado',
            'empleados' => 'empleado',
            'worker' => 'empleado',
            'workers' => 'empleado',
        ];

        // Fetch all roles with their ids
        $roles = DB::table('roles')->select('id','name','guard_name')->get();

        // Determine canonical role IDs (existing or to create)
        $adminRole = $roles->first(fn($r) => $r->name === 'admin');
        $empleadoRole = $roles->first(fn($r) => $r->name === 'empleado');

        // Create canonical roles if missing
        if (! $adminRole) {
            $adminRoleId = DB::table('roles')->insertGetId([
                'name' => 'admin',
                'description' => 'Rol administrador unificado',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $adminRole = (object)['id' => $adminRoleId, 'name' => 'admin'];
        }
        if (! $empleadoRole) {
            $empleadoRoleId = DB::table('roles')->insertGetId([
                'name' => 'empleado',
                'description' => 'Rol empleado unificado',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $empleadoRole = (object)['id' => $empleadoRoleId, 'name' => 'empleado'];
        }

        // Reassign and delete variant roles
        foreach ($roles as $role) {
            $lower = strtolower($role->name);
            if (! isset($canonicalMap[$lower])) {
                // Not a variant we care about
                continue;
            }
            $target = $canonicalMap[$lower];
            // Skip if already canonical row
            if ($role->name === $target) {
                continue;
            }
            // Determine target role id
            $targetRoleId = $target === 'admin' ? $adminRole->id : $empleadoRole->id;

            // Move pivot assignments (model_has_roles)
            DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->update(['role_id' => $targetRoleId]);

            // Delete the old (variant) role row
            DB::table('roles')->where('id', $role->id)->delete();
        }
    }

    public function down(): void
    {
        // Irreversible normalization; we do not restore old variant names.
    }
};
