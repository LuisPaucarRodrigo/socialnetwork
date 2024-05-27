<?php

namespace Database\Seeders;

use App\Models\Pension;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Area;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Customers_contact;
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
            'DocumentGestion' => 'Permite acceso al área de Gestión Documentaria'
        ];



        foreach ($permissions as $name => $description) {
            $permission = Permission::create([
                'name' => $name,
                'description' => $description
            ]);
            $adminRole->permissions()->attach($permission);
        }

        //Social Network Roles and Permissions

        $snP = Permission::create(['name' => 'SocialNetwork', 'description' => 'Permite acceso a usuarios de social network']);
        $snPPr = Permission::create(['name' => 'SocialNetworkProgramation', 'description' => 'Permite accesos al área de programación']);
        $snPOp = Permission::create(['name' => 'SocialNetworkOperation', 'description' => 'Permite accesos al área de operaciones']);
        $snPLi = Permission::create(['name' => 'SocialNetworkLiquidation', 'description' => 'Permite accesos al área de liquidaciones']);
        $snPCh = Permission::create(['name' => 'SocialNetworkCharge', 'description' => 'Permite accesos al área de cobranzas']);
        $snPCo = Permission::create(['name' => 'SocialNetworkControl', 'description' => 'Permite accesos al área de control']);

        $adminRole->permissions()->attach($snP);
        $adminRole->permissions()->attach($snPPr);
        $adminRole->permissions()->attach($snPOp);
        $adminRole->permissions()->attach($snPLi);
        $adminRole->permissions()->attach($snPCh);
        $adminRole->permissions()->attach($snPCo);


        $snRolesPermissions = [
            [
                'name' => 'SocialNetwork Programacion',
                'description' => 'Social Network área programación',
                'permissions' => [$snP->id, $snPPr->id]
            ],
            [
                'name' => 'SocialNetwork Operaciones',
                'description' => 'Social Network área operaciones',
                'permissions' => [$snP->id, $snPOp->id]
            ],
            [
                'name' => 'SocialNetwork Liquidaciones',
                'description' => 'Social Network área liquidación',
                'permissions' => [$snP->id, $snPLi->id]
            ],
            [
                'name' => 'SocialNetwork Cobranzas',
                'description' => 'Social Network área cobranzas',
                'permissions' => [$snP->id, $snPCh->id]
            ],
            [
                'name' => 'SocialNetwork Control',
                'description' => 'Social Network área control',
                'permissions' => [$snP->id, $snPCo->id]
            ],
        ];

        foreach ($snRolesPermissions as $item) {
            $current = Role::create([
                'name' => $item['name'],
                'description' => $item['description'],
            ]);
            $current->permissions()->sync($item['permissions']);
        }

        $areasData = [
            ['name' => 'Gerencia'],
            ['name' => 'Contabilidad'],
            ['name' => 'Administración'],
            ['name' => 'Proyectos'],
            ['name' => 'Logística'],
            ['name' => 'I + D'],
            ['name' => 'Calidad'],

        ];
        Area::insert($areasData);


        User::factory()->create([
            'name' => 'luis',
            'dni' => '70969005',
            'email' => 'luis@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '923098157',
            'platform' => 'Web/Movil',
            'role_id' => '1',
            'area_id' => 1
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
        $customersContactData = [
            ['name' => 'Contacto claro cicsa', 'phone' => '999999999', 'additional_information' => '-', 'customer_id' => 1],
            ['name' => 'Contacto gtd cicsa', 'phone' => '999999998', 'additional_information' => '-', 'customer_id' => 2],
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
        Customers_contact::insert($customersContactData);
        Warehouse::insert($warehousesData);
        CustomerWarehouse::insert($warehousesCustomerData);
    }
}
