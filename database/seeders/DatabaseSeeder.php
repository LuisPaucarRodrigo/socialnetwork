<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Employee;
use App\Models\Pension;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Purchasing_request;
use App\Models\Provider;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el rol "admin"
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Rol de administrador con todos los permisos'
        ]);

        // Crear todos los permisos
        $permissions = [
            'UserManager' => 'Permite acceso para todas las funciones de Usuarios y roles',
            'HumanResourceManager' => 'Permite acceso para todas las funciones de RRHH',
            'HumanResource' => 'Acceso limitado a funciones de RRHH',
            'FinanceManager' => 'Permite acceso para todas las funciones de Finanzas',
            'Finance' => 'Acceso limitado a funciones de Finanzas',
            'InventoryManager' => 'Permite acceso para todas las funciones de Inventario',
            'Inventory' => 'Acceso limitado a funciones de Inventario',
            'ProjectManager' => 'Permite acceso para todas las funciones de Area de Projectos',
            'Project' => 'Acceso limitado a funciones del Area de Projectos',
            'PurchasingManager' => 'Permite acceso para todas las funciones del Area de Compras',
            'Purchasing' => 'Acceso limitado a funciones del Area de Compras',
        ];

        foreach ($permissions as $name => $description) {
            $permission = Permission::create([
                'name' => $name,
                'description' => $description
            ]);
            $adminRole->permissions()->attach($permission);
        }


        \App\Models\User::factory()->create([
            'name' => 'luis',
            'dni' => '70969005',
            'email' => 'luis@gmail.com',
            'password' => Hash::make('12345678'),
            'platform' => 'web',
            'role_id' => '1'
        ]);
        // User::factory()->count(20)->create();
        Project::factory()->count(10)->create();
        Purchasing_request::factory()->count(1)->create();
        Provider::factory()->count(10)->create();
        
        $data = [
            [
                'type' => 'HABITAT',
                'values' => 0.0147,
            ],
            [
                'type' => 'INTEGRA',
                'values' => 0.0155,
            ],
            [
                'type' => 'PRIMA',
                'values' => 0.0160,
            ],
            [
                'type' => 'PROFUTURO',
                'values' => 0.0169,
            ],
            [
                'type' => 'HABITATMX',
                'values' => 0,
            ],
            [
                'type' => 'INTEGRAMX',
                'values' => 0,
            ],
            [
                'type' => 'PRIMAMX',
                'values' => 0,
            ],
            [
                'type' => 'PROFUTUROMX',
                'values' => 0,
            ],
        ];
        Pension::insert($data);
        // Employee::factory()->count(1000)->create();
    }
}
