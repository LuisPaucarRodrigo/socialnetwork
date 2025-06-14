<?php

namespace Database\Seeders;

use App\Models\ModuleRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $MODULES = [
            [
                'name' => 'USERS_MODULE',
                'display_name' => 'Usuarios y Roles',
                'submodules' => [
                    'user_submodule' => 'Usuarios',
                    'roles_submodule' => 'Roles',
                ]
            ],
            [
                'name' => 'HR_MODULE',
                'display_name' => 'Recursos Humanos',
                'submodules' => [
                    'hremployees_submodule' => 'Empleados',
                    'hreemployees_submodule' => 'Empleados Externos',
                    'hrspreedsheet_submodule' => 'Nómina',
                    'hrresdoc_submodule' => 'Documentos',
                    'hrhrstate_submodule' => 'Estatus RRHH',
                ]
            ],
            [
                'name' => 'INVENTORY_MODULE',
                'display_name' => 'Inventario',
                'submodules' => [
                    'iproduct_submodule' => 'Productos',
                    // 'iwarehouse_submodule' => 'Almacenes',
                ]
            ],
            [
                'name' => 'PURCHASING_MODULE',
                'display_name' => 'Área de Compras',
                'submodules' => [
                    'pprovider_submodule' => 'Proveedores',
                    // 'pprequest_submodule' => 'Solicitudes',
                    // 'pporder_submodule' => 'Órdenes',
                    // 'ppcpurchase_submodule' => 'Compras Completadas',
                ]
            ],
            [
                'name' => 'PROJECT_MODULE',
                'display_name' => 'Área de Proyectos',
                'submodules' => [
                    'pclients_submodule' => 'Clientes',
                    'ppro_submodule' => 'PRO',
                    'pprepint_submodule' => 'Anteproyectos Pint',
                    'pprepext_submodule' => 'Anteproyectos Pext',
                    'ppropint_submodule' => 'Proyectos Pint',
                    'ppropext_submodule' => 'Proyectos Pext',
                    'padmexpen_submodule' => 'G. Administrativos',
                    'pchecklist_submodule' => 'Checklist',
                    // 'pbacklog_submodule' => 'Backlog',
                ]
            ],
            [
                'name' => 'FINANCE_MODULE',
                'display_name' => 'Finanzas',
                'submodules' => [
                    'fbudget_submodule' => 'Presupuestos',
                    // 'fpapproval_submodule' => 'Aprobación de Compras',
                    // 'fdeposists_submodule' => 'Depósitos',
                    // 'fpopayment_submodule' => 'Pagos OC',
                    'faccstatement_submodule' => 'Estado de Cuenta',
                ]
            ],
            [
                'name' => 'BILLING_MODULE',
                'display_name' => 'Facturación',
                'submodules' => [
                    'billingpint_submodule' => 'Pint',
                    'billingpext_submodule' => 'Pext',
                ]
            ],
            [
                'name' => 'HUAWEI_MODULE',
                'display_name' => 'Huawei',
                'submodules' => [
                    'huawei_sites_submodule' => 'Sites Huawei',
                    'huawei_projects_submodule' => 'Proyectos Huawei',
                    'huawei_inventory_submodule' => 'Inventario Huawei',
                    'huawei_internal_inventory_submodule' => 'Inventario Interno',
                    'huawei_interal_guides_submodule' => 'Guias Internas',
                    'huawei_special_returns_submodule' => 'Devolucion Especiales',
                ]
            ],
            [
                'name' => 'CAR_MODULE',
                'display_name' => 'Flota de Vehículos',
                'submodules' => [
                    'cchanappro_submodule' => 'Aprobación de Cambios',
                    'cmobileunit_submodule' => 'UM',
                ]
            ],
            [
                'name' => 'SHAREPOINT_MODULE',
                'display_name' => 'Sharepoint',
                'submodules' => [
                    'sharedoc_submodule' => 'Documentación'
                ]
            ],

        ];

        foreach ($MODULES as $mod) {
            $module = Module::create([
                'name' => $mod['name'],
                'display_name' => $mod['display_name'],
                'type' => 'module',
            ]);
            foreach ($mod['submodules'] as $sub_name => $sub_display_name) {
                Module::create([
                    'name' => $sub_name,
                    'display_name' => $sub_display_name,
                    'parent_id' => $module->id,
                    'type' => 'submodule',
                ]);
            }
        }
    }
}
