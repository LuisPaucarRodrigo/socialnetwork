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

                ]
            ],
            [
                'name' => 'PURCHASING_MODULE',
                'display_name' => 'Área de Compras',
                'submodules' => [
                    'pprovider_submodule' => 'Proveedores',
                    'pprequest_submodule' => 'Solicitudes',
                    'pporder_submodule' => 'Órdenes',
                    'ppcpurchase_submodule' => 'Compras Completadas',

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
                    'pchecklist_submodule' => 'G. Administrativos',
                    'padmexpen_submodule' => 'Checklist',
                    'pbacklog_submodule' => 'Backlog',
                ]
            ],
            [
                'name' => 'FINANCE_MODULE',
                'display_name' => 'Finanzas',
                'submodules' => [
                    'fbudget_submodule' => 'Presupuestos',
                    'fpapproval_submodule' => 'Aprobación de Compras',
                    'fdeposists_submodule' => 'Depósitos',
                    'fpopayment_submodule' => 'Pagos OC',
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
                'name' => 'DOCUMENT_MODULE',
                'display_name' => 'Usuarios y Roles',
                'submodules' => [

                ]
            ],
            [
                'name' => 'HUAWEI_MODULE',
                'display_name' => 'Huawei',
                'submodules' => [

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
            ModuleRole::create([
                'role_id' => 1,
                'module_id' => $module->id
            ]);
            foreach ($mod['submodules'] as $sub_name => $sub_display_name) {
                $submodule = Module::create([
                    'name' => $sub_name,
                    'display_name' => $sub_display_name,
                    'parent_id' => $module->id,
                    'type' => 'submodule',
                ]);
                ModuleRole::create([
                    'role_id' => 1,
                    'module_id' => $submodule->id
                ]);
            }
        }
    }
}
