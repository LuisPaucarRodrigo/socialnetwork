<?php

namespace Database\Seeders;

use App\Models\CostLine;
use App\Models\CostCenter;
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
    public function run(): void
    {
        //Crear el rol "admin"
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Rol de administrador con todos los permisos de gerente'
        ]);

        // Crear todos los permisos
        $permissions = [
            'UserManager' => 'Permite acceso a la mayoria de funciones de Usuarios y roles',
            'HumanResourceManager' => 'Permite acceso a la mayoria de funciones de RRHH',
            'HumanResource' => 'Solo podra visualizar RRHH',
            'FinanceManager' => 'Permite acceso a la mayoria de funciones de Finanzas',
            'Finance' => 'Solo podra visualizar Finanzas',
            'InventoryManager' => 'Permite acceso a la mayoria de funciones de Inventario',
            'Inventory' => 'Solo podra visualizar Inventario',
            'ProjectManager' => 'Permite acceso a la mayoria de funciones de Area de Projectos',
            'Project' => 'Solo podra visualizarl Area de Projectos',
            'PurchasingManager' => 'Permite acceso a la mayoria de funciones del Area de Compras',
            'Purchasing' => 'Solo podra visualizarl Area de Compras',
            'HuaweiManager' => 'Permite acceso al Área de Huawei',
            'DocumentGestion' => 'Permite acceso al área de Gestión Documentaria',
            'CarManager' => 'Permite acceso al área de Gestión de Autos'
        ];

        // Permissions Admin
        $managerPermissions = [
            'UserManager',
            'HumanResourceManager',
            'FinanceManager',
            'InventoryManager',
            'ProjectManager',
            'PurchasingManager',
            'HuaweiManager',
            'DocumentGestion'
        ];

        foreach ($permissions as $name => $description) {
            $permission = Permission::create([
                'name' => $name,
                'description' => $description
            ]);
            if (in_array($name, $managerPermissions)) {
                $adminRole->permissions()->attach($permission);
            }
        }

        $areasData = [
            ['name' => 'Gerencia'],
            ['name' => 'Contabilidad'],
            ['name' => 'Administración'],
            ['name' => 'Proyectos'],
            ['name' => 'Logística'],
            ['name' => 'I + D'],
            ['name' => 'Calidad']

        ];
        Area::insert($areasData);

        User::factory()->create([
            'name' => 'Gustavo Flores Llerena',
            'dni' => '43196970',
            'email' => 'gflores@ccip.com.pe',
            'password' => Hash::make('12345678'),
            'phone' => '992275316',
            'platform' => 'Web/Movil',
            'role_id' => '1',
            'area_id' => 1
        ]);

        $customersData = [
            ['id' => 1, 'ruc' => 20512780114, 'business_name' => 'CICSA PERU SAC', 'category' => 'Especial', 'address' => 'CALLE AMADOR MERINO REINA 267 OFC 501 LIMA LIMA SAN ISIDRO'],
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

        $costLinesData = [
            ["name" => "PINT"],
            ["name" => "PEXT"],
            ["name" => "HUAWEI"],
        ];
        $costCenterData = [
            ["name" => "Mantto pint CLARO", "cost_line_id" => 1, "percentage" => "20"],
            ["name" => "Combustible pint CLARO", "cost_line_id" => 1, "percentage" => "40"],
            ["name" => "Adicionales pint CLARO", "cost_line_id" => 1, "percentage" => "40"],
            ["name" => "Mantto pext CLARO", "cost_line_id" => 2, "percentage" => "10"],
            ["name" => "Mantto pext GTD", "cost_line_id" => 2, "percentage" => "10"],
            ["name" => "Instalaciones CLARO", "cost_line_id" => 2, "percentage" => "30"],
            ["name" => "Instalaciones GTD", "cost_line_id" => 2, "percentage" => "20"],
            ["name" => "Densificaion", "cost_line_id" => 2, "percentage" => "20"],
            ["name" => "Adicionales", "cost_line_id" => 2, "percentage" => "10"],
        ];

        CostLine::insert($costLinesData);
        CostCenter::insert($costCenterData);
        Customer::insert($customersData);
        Customers_contact::insert($customersContactData);
        Warehouse::insert($warehousesData);
        CustomerWarehouse::insert($warehousesCustomerData);
    }
}
