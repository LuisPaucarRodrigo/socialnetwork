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
use App\Models\Header;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\CustomerWarehouse;
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
            'phone' => '923098157',
            'platform' => 'web',
            'role_id' => '1'
        ]);
        // User::factory()->count(20)->create();
        // Project::factory()->count(10)->create();
        // Purchasing_request::factory()->count(1)->create();
        // Provider::factory()->count(10)->create();
        
        $data = [
            [
                'type' => 'HABITAT',
                'values' => 0.0147,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'INTEGRA',
                'values' => 0.0155,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'PRIMA',
                'values' => 0.0160,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'PROFUTURO',
                'values' => 0.0169,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'HABITATMX',
                'values' => 0,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'INTEGRAMX',
                'values' => 0,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'PRIMAMX',
                'values' => 0,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'PROFUTUROMX',
                'values' => 0,
                'values_seg' => 0.0184,
            ],
            [
                'type' => 'ONP',
                'values' => 0.13,
                'values_seg' => 0.0,
            ],
        ];
        Pension::insert($data);
        // Employee::factory()->count(1000)->create();

        $customersData = [
            ['id' => 1, 'ruc' => 1, 'business_name' => 'CLARO CICSA', 'category' => 'Especial', 'address' => '-'],
            ['id' => 2, 'ruc' => 2, 'business_name' => 'GTD CICSA', 'category' => 'Especial', 'address' => '-'],
        ];
        $warehousesData = [
            ['id' => 1, 'name' => 'CLARO CICSA', 'description' => '-', 'person_in_charge' => '-', 'category' => 'Especial'],
            ['id' => 2, 'name' => 'GTD CICSA', 'description' => '-', 'person_in_charge' => '-', 'category' => 'Especial'],
            ['id' => 3, 'name' => 'CONPROCO', 'description' => '-', 'person_in_charge' => '-', 'category' => 'Normal'],
            ['id' => 4, 'name' => 'RECUPEROS', 'description' => '-', 'person_in_charge' => '-', 'category' => 'Normal'],
            ['id' => 5, 'name' => 'ACTIVOS', 'description' => '-', 'person_in_charge' => '-', 'category' => 'Normal'],
            ['id' => 6, 'name' => 'SERVICIOS', 'description' => '-', 'person_in_charge' => '-', 'category' => 'Normal'],
        ];
        $warehousesCustomerData = [
            ['id' => 1, 'customer_id' => 1, 'warehouse_id' => 1],
            ['id' => 2, 'customer_id' => 1, 'warehouse_id' => 3],
            ['id' => 3, 'customer_id' => 1, 'warehouse_id' => 4],
            ['id' => 4, 'customer_id' => 2, 'warehouse_id' => 2],
            ['id' => 5, 'customer_id' => 2, 'warehouse_id' => 3],
            ['id' => 6, 'customer_id' => 2, 'warehouse_id' => 4],
        ];

        Customer::insert($customersData);
        Warehouse::insert($warehousesData);
        CustomerWarehouse::insert($warehousesCustomerData);
    }
}
