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
            //User
            'UserManager' => 'Permite acceso a la mayoria de funciones de Usuarios y roles',
            //Human Resource
            'HumanResourceManager' => 'Permite acceso a la mayoria de funciones de RRHH',    
            'HumanResource' => 'Solo podra visualizar RRHH',
            'HumanResourceEmployeesManager' => 'Acceso total a la gestión de empleados',
            'HumanResourceEmployees' => 'Acceso restringido a empleados',
            'HumanResourceEEmployeesManager' => 'Acceso total a la gestión de empleados externos',
            'HumanResourceEEmployees' => 'Acceso restringido a empleados externos',
            'HumanResourceSpreedsheetManager' => 'Acceso total a la gestión de nómina',
            'HumanResourceSpreedsheet' => 'Acceso restringido a nómina',
            'HumanResourceDocumentsManager' => 'Acceso total a la gestión de documentos',
            'HumanResourceDocuments' => 'Acceso restringido a documentos',
            'HumanResourceHRStateManager' => 'Acceso total a la gestión de estatus RRHH',
            'HumanResourceHRState' => 'Acceso restringido a estatus RRHH',
            //Inventory
            'InventoryManager' => 'Permite acceso a la mayoria de funciones de Inventario',
            'Inventory' => 'Solo podra visualizar Inventario',
            //Purchasing
            'PurchasingManager' => 'Permite acceso a la mayoria de funciones del Area de Compras',
            'Purchasing' => 'Solo podra visualizarl Area de Compras',
            'PurchasingProvidersManager' =>  'Acceso total a la gestión de proveedores',
            'PurchasingProviders' =>  'Acceso restringido a estatus proveedores',
            'PurchasingPurchasingRequestManager' =>  'Acceso total a la gestión de solicitudes de compra',
            'PurchasingPurchasingRequest' =>  'Acceso restringido a solicitudes de compra',
            'PurchasingPurchasingOrderManager' =>  'Acceso total a la gestión de ordenes de compra',
            'PurchasingPurchasingOrder' =>  'Acceso restringido a ordenes de compra',
            'PurchasingCompletedPurchasesManager' =>  'Acceso total a la gestión de ordenes de compra',
            'PurchasingCompletedPurchases' =>  'Acceso restringido a ordenes de compra',
            //Project Area
            'ProjectManager' => 'Permite acceso a la mayoria de funciones de Area de Proyectos',
            'Project' => 'Solo podra visualizarl Area de Proyectos',
            'ProjectClientsManager' =>  'Acceso total a la gestión de clientes',
            'ProjectClients' =>  'Acceso restringido a clientes',
            'ProjectPROManager' =>  'Acceso total a la gestión de PRO',
            'ProjectPRO' =>  'Acceso restringido a PRO',
            'ProjectPreprojectPintManager' =>  'Acceso total a la gestión de anteproyectos pint',
            'ProjectPreprojectPint' =>  'Acceso restringido a anteproyectos pint',
            'ProjectPreprojectPextManager' =>  'Acceso total a la gestión de anteproyectos pext',
            'ProjectPreprojectPext' =>  'Acceso restringido a anteproyectos pex',
            'ProjectProjectPintManager' =>  'Acceso total a la gestión de proyectos pint',
            'ProjectProjectPint' =>  'Acceso restringido a proyectos pint',
            'ProjectProjectPextManager' =>  'Acceso total a la gestión de proyectos pext',
            'ProjectProjectPext' =>  'Acceso restringido a proyectos pext',
            'ProjectChecklistManager' =>  'Acceso total a la gestión del checklist',
            'ProjectChecklist' =>  'Acceso restringido a checklist',
            'ProjectBacklogManager' =>  'Acceso total a la gestión del backlog',
            'ProjectBacklog' =>  'Acceso restringido a backlog',
            //Finances
            'FinanceManager' => 'Permite acceso a la mayoria de funciones de Finanzas',
            'Finance' => 'Solo podra visualizar Finanzas',
            'FinanceBudgetManager' =>  'Acceso total a la gestión del presupuestos',
            'FinanceBudget' =>  'Acceso restringido a presupuestos',
            'FinancePurchaseApprovalManager' =>  'Acceso total a la gestión de aprobaciones de compras',
            'FinancePurchaseApproval' =>  'Acceso restringido a aprobación de compras',
            'FinanceDepositsManager' =>  'Acceso total a la gestión de depósitos',
            'FinanceDeposits' =>  'Acceso restringido a depósitos',
            'FinancePOPaymentsManager' =>  'Acceso total a la gestión de pagos de ordenes de compra',
            'FinancePOPayments' =>  'Acceso restringido a pagos de ordenes de compra',
            'FinanceAccountStatementManager' =>  'Acceso total a la gestión de estados de cuenta',
            'FinanceAccountStatement' =>  'Acceso restringido al estado de cuenta',
            //Huawei and Document gestion
            'HuaweiManager' => 'Permite acceso al Área de Huawei',
            'DocumentGestion' => 'Permite acceso al área de Gestión Documentaria',
            //Billing
            'BillingManager' => 'Permite acceso a la mayoria de funciones de Facturación',
            'Billing' => 'Solo podra visualizar Facturación',

            'CarManager' => 'Permite acceso al área de Gestión de Autos',
            'Car' => 'Funcionalidades limitadas en Gestión de Autos',

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
            'DocumentGestion',
            'CarManager',
            'Car'
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
            ["name" => 'MBB', 'cost_line_id' => 3, 'percentage' => '50'],
            ["name" => 'FBB', 'cost_line_id' => 3, 'percentage' => '50'],
        ];

        CostLine::insert($costLinesData);
        CostCenter::insert($costCenterData);
        Customer::insert($customersData);
        Customers_contact::insert($customersContactData);
        Warehouse::insert($warehousesData);
        CustomerWarehouse::insert($warehousesCustomerData);
    }
}
