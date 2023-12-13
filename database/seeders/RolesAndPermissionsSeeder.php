<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $roles = ['admin', 'area_proyecto', 'finanzas', 'area_compras', 'inventario', 'rrhh'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Asignar permisos a los roles
        $permissions = [
            'admin' => ['gestion_usuarios', 'gestion_roles'],
            'area_proyecto' => ['ver_proyectos', 'crear_proyectos', 'editar_proyectos', 'eliminar_proyectos'],
            'finanzas' => ['ver_finanzas', 'exportar_finanzas'],
            'area_compras' => ['ver_compras', 'aprobar_compras'],
            'inventario' => ['ver_inventario', 'ajustar_inventario'],
            'rrhh' => ['ver_empleados', 'gestion_permisos']
        ];

        foreach ($permissions as $role => $permissionList) {
            $roleModel = Role::where('name', $role)->first();
            foreach ($permissionList as $permission) {
                $perm = Permission::create(['name' => $permission]);
                $roleModel->permissions()->attach($perm);
            }
        }
    }
}
