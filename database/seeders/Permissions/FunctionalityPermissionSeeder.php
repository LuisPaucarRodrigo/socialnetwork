<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Functionality;
use App\Models\Permission;
use App\Models\FunctionalityPermission;
use Illuminate\Support\Facades\Log;

class FunctionalityPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuarios y Roles -> Usuarios
        $UsersSubModule = [
            [
                'display_name' => 'Ver tabla usuarios (incluye buscar)',
                'key_name' => 'see_users_table',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.search']
            ],
            [
                'display_name' => 'Agregar usuario',
                'key_name' => 'add_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'register', 'register.post']
            ],
            [
                'display_name' => 'Ver usuario',
                'key_name' => 'see_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.details']
            ],
            [
                'display_name' => 'Editar usuario (incluye contraseña)',
                'key_name' => 'edit_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.edit', 'users.update', 'password.update']
            ],
            [
                'display_name' => 'Eliminar usuario',
                'key_name' => 'delete_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.destroy']
            ]
        ];

        // Usuarios y Roles -> Roles
        $RolesSubModule = [
            [
                'display_name' => 'Ver tabla roles',
                'key_name' => 'see_roles_table',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index',]
            ],
            [
                'display_name' => 'Agregar rol',
                'key_name' => 'add_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.store']
            ],
            [
                'display_name' => 'Ver rol',
                'key_name' => 'see_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.details']
            ],
            [
                'display_name' => 'Editar rol',
                'key_name' => 'edit_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.update']
            ],
            [
                'display_name' => 'Eliminar rol',
                'key_name' => 'delete_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.delete']
            ],
        ];

        // Recursos Humanos -> Empleados
        $HremployeesSubModule = [
            [
                'display_name' => 'Ver tabla empleados activos (incluye buscar)',
                'key_name' => 'see_active_employees_table',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.search',]
            ],
            [
                'display_name' => 'Ver tabla de empleados inactivos (incluye buscar)',
                'key_name' => 'see_inactive_employees_table',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.search', 'management.employees.show.preview.doc_alta']
            ],
            [
                'display_name' => 'Agregar empleado',
                'key_name' => 'add_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.create', 'management.employees.store', 'management.employees.search']
            ],
            [
                'display_name' => 'Ver empleado',
                'key_name' => 'see_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.show', 'management.employees.information.details.download', 'management.employees.search']
            ],
            [
                'display_name' => 'Editar empleado',
                'key_name' => 'edit_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.edit', 'management.employees.update', 'management.employees.search']
            ],
            [
                'display_name' => 'Despido y recontratación de empleado',
                'key_name' => 'fired_reentry_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.fired', 'management.employees.reentry', 'management.employees.search', 'management.employees.show.preview.doc_alta']
            ],
            [
                'display_name' => 'Alarma cumpleaños empleados',
                'key_name' => 'happy_birthday_alarm',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees.happy.birthday']
            ]
        ];

        // Recursos Humanos -> Empleados Externos
        $HreemployeesSubModule = [
            [
                'display_name' => 'Ver tabla empleados externos',
                'key_name' => 'see_external_employee_table',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'employees.external.search']
            ],
            [
                'display_name' => 'Agregar y editar empleado externo',
                'key_name' => 'add_external_employee',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'management.external.storeorupdate', 'employees.external.search']
            ],
            [
                'display_name' => 'Eliminar empleado externo',
                'key_name' => 'delete_external_employee',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'employees.external.delete', 'employees.external.search']
            ],
            [
                'display_name' => 'Ver y descargar currículum vitae de empleado externo',
                'key_name' => 'see_external_employee_document',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'employees.external.preview.curriculum_vitae', 'employees.external.search']
            ],
        ];

        // Recursos Humanos -> Nómina
        $hrspreedsheet_submodule = [
            [
                'display_name' => 'Gestion de Nomina',
                'key_name' => 'manage_payroll_spreedsheet',
                'module' => 'hrspreedsheet_submodule',
                'permissions' => [
                    'payroll.index',
                    'payroll.store',
                    'payroll.state.update',
                    'spreadsheets.index',
                    'spreadsheets.generate.bill',
                    'index.worker.data',
                    'payroll.show.payroll.detail.work.schedule',
                    'payroll.store.payroll.detail.work.schedule',
                    'payroll.show.payroll.detail.monetary.income',
                    'payroll.store.payroll.detail.monetary.income',
                    'payroll.show.payroll.detail.monetary.discount',
                    'payroll.store.payroll.detail.monetary.discount',
                    'payroll.show.payroll.detail.tax.contribution',
                    'payroll.store.payroll.detail.tax.contribution',
                    'payroll.index.payroll.external.detail',
                    'payroll.store.payroll.external.detail',
                    'payroll.store.payroll.external.destroy',
                    'payroll.detail.expenses.multiple.store',
                    'payroll.detail.expense.constants.show',
                    'payroll.detail.expense.index',
                    'payroll.detail.expenses.show',
                    'payroll.detail.expense.search',
                    'payroll.detail.expenses.store',
                    'payroll.detail.expense.destroy',
                    'payroll.detail.expenses.massive.update.opnuda',
                    'payroll.detail.export',
                    'payroll.detail.export.details',
                ]
            ],
        ];
        // Recursos Humanos -> Documentos
        $HrresdocSubModule = [
            [
                'display_name' => 'Ver cards de documentos (incluye buscador y filtro)',
                'key_name' => 'see_document_cards_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.filter.section', 'documents.filter.subdivision', 'documents.search']
            ],
            [
                'display_name' => 'Agregar documento',
                'key_name' => 'add_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.create']
            ],
            [
                'display_name' => 'Editar documento',
                'key_name' => 'edit_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.update']
            ],
            [
                'display_name' => 'Ver documento',
                'key_name' => 'see_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.show']
            ],
            [
                'display_name' => 'Descargar documento',
                'key_name' => 'download_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.download']
            ],
            [
                'display_name' => 'Eliminar documento',
                'key_name' => 'delete_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.destroy']
            ],
            [
                'display_name' => 'Gestionar secciones y subdivisiones (sin eliminar)',
                'key_name' => 'manage_sections_subdivisions_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.zipSection', 'documents.updateSection', 'documents.sections', 'documents.storeSection', 'documents.subdivisions', 'documents.zipSubdivision', 'documents.updateSubdivision', 'documents.storeSubdivision']
            ],
            [
                'display_name' => 'Eliminar secciones nuevas',
                'key_name' => 'delete_new_sections_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.sections', 'documents.destroySection']
            ],
            [
                'display_name' => 'Eliminar subdivisiones nuevas',
                'key_name' => 'delete_new_subdivisions_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.sections', 'documents.subdivisions', 'documents.destroySubdivision']
            ]
        ];

        // Recursos Humanos -> Estatus RRHH
        $HrhrstateSubModule = [
            [
                'display_name' => 'Ver tabla Estatus RRHH y filtro',
                'key_name' => 'see_estatus_rrhh_table',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status']
            ],
            [
                'display_name' => 'Modificar estados de documentos',
                'key_name' => 'modify_document_status',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.rrhh.status.create', 'document.rrhh.status.update', 'document.rrhh.status.destroy', 'document.rrhh.status.in_expdate']
            ],
            [
                'display_name' => 'Gestionar documentos grupales (sin eliminar)',
                'key_name' => 'manage_grupal_documents_hr',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.grupal_documents.index', 'document.grupal_documents.store', 'document.grupal_documents.update', 'document.grupal_documents.download']
            ],
            [
                'display_name' => 'Eliminar documentos grupales',
                'key_name' => 'delete_grupal_documents_hr',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.grupal_documents.index', 'document.grupal_documents.destroy']
            ],
            [
                'display_name' => 'Ver Detalles del estatus RRHH',
                'key_name' => 'show_status_rrhh',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'employee.document.rrhh.status']
            ],
            [
                'display_name' => 'Alarma de rrhh',
                'key_name' => 'doc_alarm_hr',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.rrhh.status.alarms', 'document.rrhh.nodoc.alarms']
            ],
        ];

        // Inventario -> Productos
        $iproduct_submodule = [
            [
                'display_name' => 'Ver tabla de Productos (incluye buscar)',
                'key_name' => 'see_product_table',
                'module' => 'iproduct_submodule',
                'permissions' => [
                    'inventory.purchaseproducts.disable',
                    'inventory.purchaseproducts.typeProduct',
                    'inventory.purchaseproducts.resourceType',

                    'inventory.purchaseproducts',
                    'inventory.purchaseproducts.search',

                    'inventory.purchaseproducts.store',
                    'inventory.purchaseproducts.update',
                ]
            ],
        ];
        // Inventario -> Almacenes
        // $iwarehouse_submodule = [
        //     [
        //         'display_name' => 'Ver tabla de Almacenes',
        //         'key_name' => 'see_warehouse_table',
        //         'module' => 'iwarehouse_submodule',
        //         'permissions' => [
        //             'warehouses.resource.create',
        //             'warehouses.resource.store',

        //             'inventory.special_products.destroy',
        //             'inventory.special_dispatch_output.destroy',

        //             'inventory.special_products.create',
        //             'inventory.special_products.store',
        //             'inventory.special_refund.accept_decline',
        //             'inventory.special_dispatch.accept_decline',

        //             'warehouses.purchaseorders.approve.post',
        //             'warehouses.createNormalProduct',
        //             'warehouses.storeNormalProduct',

        //             'retrieval.entry.approbe',
        //             'inventory.special_dispatch_output.store',
        //             'warehouses.dispatches.acceptordecline',
        //             'warehouses.resource.approve',

        //             'warehouses.service.approve.index',
        //             'warehouses.service.store',
        //             'warehouses.service.delete',
        //             'warehouses.resource.add.serial_number',

        //             'warehouses.warehouses',

        //             'inventory.special_products.index',
        //             'inventory.special_dispatch.index',
        //             'inventory.special_dispatch.approved',
        //             'inventory.special_dispatch.rejected',
        //             'inventory.special_refund.index',
        //             'inventory.special_refund.historial',

        //             'warehouses.purchaseorders.approve',
        //             'warehouses.conproco.products',
        //             'warehouses.products.entries',
        //             'warehouses.dispatches',
        //             'warehouses.dispatches.approved',
        //             'warehouses.dispatches.rejected',

        //             'inventory.retrieval.entry.index',
        //             'inventory.retrieval.product.index',
        //             'inventory.retrieval.product.show',
        //             'inventory.retrievalDispatch.index',
        //             'inventory.retrievalDispatch.approved',
        //             'inventory.retrievalDispatch.rejected',

        //             'warehouses.resource',
        //             'warehouses.resource.active.index',

        //             'warehouses.service.approve.index',
        //         ]
        //     ],
        // ];

        //Area de Compras -> Proveedores
        $pproviderSubModule = [
            [
                'display_name' => 'Ver tabla de proveedores (incluye buscar)',
                'key_name' => 'see_providers_table',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index']
            ],
            [
                'display_name' => 'Agregar proveedor',
                'key_name' => 'add_provider',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'providersmanagement.store', 'provider.segments.list']
            ],
            [
                'display_name' => 'Editar proveedor',
                'key_name' => 'edit_provider',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'providersmanagement.update', 'provider.segments.list']
            ],
            [
                'display_name' => 'Gestionar categorias y segmentos',
                'key_name' => 'manage_categorys_segments',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'provider.category.post', 'provider.segment.post']
            ],
            [
                'display_name' => 'Eliminar proveedor',
                'key_name' => 'delete_provider',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'providersmanagement.destroy']
            ],
        ];

        //Area de Compras -> Solicitudes
        // $pprequest_submodule = [
        //     [
        //         'display_name' => 'Ver tabla de solicitudes (incluye buscar)',
        //         'key_name' => 'see_purchases_request_table',
        //         'module' => 'pprequest_submodule',
        //         'permissions' => [
        //             'purchasesrequest.index',
        //             'purchasesrequest.search',
        //             'purchasingrequest.export',
        //             'purchasingrequest.details',
        //             'purchasesrequest.alarm',

        //             'purchasesrequest.edit',
        //             'purchasesrequest.update',
        //             'purchasesrequest.destroy'
        //         ]
        //     ],
        // ];

        // $pporder_submodule = [
        //     [
        //         'display_name' => 'Ver tabla de ordenes (incluye buscar)',
        //         'key_name' => 'see_purchases_order_table',
        //         'module' => 'pporder_submodule',
        //         'permissions' => [
        //             'purchaseorders.index',
        //             'purchaseorders.search',
        //             'purchaseorders.details',
        //             'purchaseorders.export.order',
        //             'purchaseorders.alarms'
        //         ]
        //     ],
        // ];

        // $ppcpurchase_submodule = [
        //     [
        //         'display_name' => 'Ver tabla de compras completadas (incluye buscar)',
        //         'key_name' => 'see_completed_purchases_table',
        //         'module' => 'ppcpurchase_submodule',
        //         'permissions' => [
        //             'purchaseorders.history',
        //             'purchasesrequest.show',
        //             'purchasesorder.showFacture'
        //         ]
        //     ],
        // ];

        //Area de Compras -> Ordenes
        //Area de Compras -> Compras Completadas

        //Area Proyectos -> Clientes
        $pclientsSubModule = [
            [
                'display_name' => 'Ver tabla de clientes (incluye buscar)',
                'key_name' => 'see_customers_table',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.search']
            ],
            [
                'display_name' => 'Agregar cliente',
                'key_name' => 'add_client',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.store']
            ],
            [
                'display_name' => 'Editar cliente',
                'key_name' => 'edit_client',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.update']
            ],
            [
                'display_name' => 'Eliminar cliente',
                'key_name' => 'delete_client',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.destroy']
            ],
            [
                'display_name' => 'Gestionar contactos de cliente (no eliminar)',
                'key_name' => 'manage_client_contacts',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.contacts.index', 'customers.contacts.store', 'customers.contacts.update']
            ],
            [
                'display_name' => 'Eliminar contacto de cliente',
                'key_name' => 'delete_client_contact',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.contacts.index', 'customers.contacts.destroy']
            ],
        ];

        //Area Proyectos -> PRO
        $pproSubModule = [
            [
                'display_name' => 'Ver tabla títulos',
                'key_name' => 'see_pro_titles_table',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles']
            ],
            [
                'display_name' => 'Agregar título ',
                'key_name' => 'add_pro_title',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.titles.post']
            ],
            [
                'display_name' => 'Editar título ',
                'key_name' => 'edit_pro_title',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.titles.put']
            ],
            [
                'display_name' => 'Eliminar títulos ',
                'key_name' => 'delete_pro_title',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.titles.delete']
            ],
            [
                'display_name' => 'Ver tabla códigos',
                'key_name' => 'see_pro_codes_table',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes']
            ],
            [
                'display_name' => 'Agregar código',
                'key_name' => 'add_pro_code',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.codes.post']
            ],
            [
                'display_name' => 'Agregar imágenes de ejemplo del código',
                'key_name' => 'add_pro_code_images',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.code.images.store']
            ],
            [
                'display_name' => 'Ver imágenes de ejemplo del código',
                'key_name' => 'see_pro_code_images',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.code.images.show', 'preprojects.code.images.index']
            ],
            [
                'display_name' => 'Eliminar imágenes de ejemplo del código',
                'key_name' => 'delete_pro_code_images',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.code.images.index', 'preprojects.code.images.delete']
            ],
            [
                'display_name' => 'Editar código',
                'key_name' => 'edit_pro_code',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.codes.put']
            ],
            [
                'display_name' => 'Eliminar código',
                'key_name' => 'delete_pro_code',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.codes.delete']
            ],
        ];

        //Area Proyectos -> Anteproyectos PINT
        $pprepintSubModule = [
            [
                'display_name' => 'Ver cards de anteproyectos activos, aprobados y anulados (incluye buscador)',
                'key_name' => 'see_prepro_pint_cards',
                'module' => 'pprepint_submodule',
                'permissions' => ['preprojects.index']
            ],
            [
                'display_name' => 'Asignar usuarios',
                'key_name' => 'add_prepro_pint_users',
                'module' => 'pprepint_submodule',
                'permissions' => ['preprojects.index', 'preprojects.assign.users']
            ],
            [
                'display_name' => 'Descargar imágenes',
                'key_name' => 'download_prepro_pint_images',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.imagereport.index',
                    'preprojects.report.download',
                    'preprojects.imagereport.delete',
                    'preprojects.imagereport.download',
                    'preprojects.imagereport.show',
                    'preprojects.imagereport.approveReject',
                    'preprojects.stages.delete',
                    'preprojects.stages.store',
                    'preprojects.codereport.approveCode',
                    'preprojects.codereport.approveTitle',
                    'preprojects.codereport.approveImages'
                ]
            ],
            [
                'display_name' => 'Informe fotográfico',
                'key_name' => 'prepro_pint_photoreport',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.photoreport.index',
                    'preprojects.photoreport.download',
                    'preprojects.photoreport.update',
                    'preprojects.photoreport.store',
                    'preprojects.photoreport.delete',
                    'preprojects.photoreport_pdf.download',
                    'preprojects.photoreport.show'
                ]
            ],
            [
                'display_name' => 'Productos de almacén',
                'key_name' => 'prepro_pint_warehouseproducts',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.products',
                    'preprojects.warehouse_products',
                    'preprojects.inventory_products',
                    'preprojects.products.store',
                    'projectmanagement.products.delete',
                    'projectmanagement.products.update',
                ]
            ],
            [
                'display_name' => 'Solicitud de compras',
                'key_name' => 'prepro_pint_purchaserequest',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.request.index',
                    'preprojects.request.details',
                    'preprojects.request.edit',
                    'purchasesrequest.destroy',
                    'preprojects.request.create',
                    'preprojects.request.update',
                    'preprojects.request.store',
                    'purchasing_request_product.store',
                    'purchasing_request_product.delete'
                ]
            ],
            [
                'display_name' => 'Cotizaciones de compras',
                'key_name' => 'prepro_pint_purchasequotes',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.purchase_quote',
                    'preprojects.purchase.quote.details',
                    'purchasesrequest.show',
                    'preprojects.purchase_quote.accept_decline',
                    'purchase.update_quotedeadline',
                    'purchasesrequest.quote_deadline.complete'
                ]
            ],
            [
                'display_name' => 'Cotización para proyecto',
                'key_name' => 'prepro_pint_projectquote',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.quote',
                    'preprojects.pdf',
                    'preprojects.quote.store',
                    'preprojects.quote.item.store',
                    'preprojects.quote.item.delete',
                    'preprojects.accept',
                    'preprojects.quote.product.store',
                    'preprojects.quote.product.delete',
                    'load.resource_entries',
                    'preproject_quote.rejected',
                    'preproject_quote.canceled'

                ]
            ],
            [
                'display_name' => 'Agregar y editar preproyecto (manera extendida)',
                'key_name' => 'addedit_prepro_pint',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.create',
                    'preprojects.update',
                    'preprojects.store',
                    'preprojects.update'
                ]
            ],
            [
                'display_name' => 'Agregar preproyecto, proyecto y asignación cicsa (manera corta)',
                'key_name' => 'add_prepro_pro_ca_pint',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'project.auto.pint',
                    'pint_project.products.cpe',
                    'project.auto.pint.getEmployees',
                ]
            ],
            [
                'display_name' => 'Eliminar preproyecto, proyecto y asignación cicsa',
                'key_name' => 'delete_prepro_pint',
                'module' => 'pprepint_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.destroy',
                ]
            ],
        ];

        //Area Proyectos -> Anteproyectos PEXT
        $prepextSubModule = [
            [
                'display_name' => 'Ver cards de anteproyectos activos, aprobados y anulados (incluye buscador)',
                'key_name' => 'see_prepro_pext_cards',
                'module' => 'pprepext_submodule',
                'permissions' => ['preprojects.index']
            ],
            [
                'display_name' => 'Asignar usuarios',
                'key_name' => 'add_prepro_pext_users',
                'module' => 'pprepext_submodule',
                'permissions' => ['preprojects.index', 'preprojects.assign.users']
            ],
            [
                'display_name' => 'Descargar imágenes',
                'key_name' => 'download_prepro_pext_images',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.imagereport.index',
                    'preprojects.report.download',
                    'preprojects.imagereport.delete',
                    'preprojects.imagereport.download',
                    'preprojects.imagereport.show',
                    'preprojects.imagereport.approveReject',
                    'preprojects.stages.delete',
                    'preprojects.stages.store',
                    'preprojects.codereport.approveCode',
                    'preprojects.codereport.approveTitle',
                    'preprojects.codereport.approveImages'
                ]
            ],
            [
                'display_name' => 'Informe fotográfico',
                'key_name' => 'prepro_pext_photoreport',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.photoreport.index',
                    'preprojects.photoreport.download',
                    'preprojects.photoreport.update',
                    'preprojects.photoreport.store',
                    'preprojects.photoreport.delete',
                    'preprojects.photoreport_pdf.download',
                    'preprojects.photoreport.show'
                ]
            ],
            [
                'display_name' => 'Productos de almacén',
                'key_name' => 'prepro_pext_warehouseproducts',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.products',
                    'preprojects.warehouse_products',
                    'preprojects.inventory_products',
                    'preprojects.products.store',
                    'projectmanagement.products.delete',
                    'projectmanagement.products.update',
                ]
            ],
            [
                'display_name' => 'Solicitud de compras',
                'key_name' => 'prepro_pext_purchaserequest',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.request.index',
                    'preprojects.request.details',
                    'preprojects.request.edit',
                    'purchasesrequest.destroy',
                    'preprojects.request.create',
                    'preprojects.request.update',
                    'preprojects.request.store',
                    'purchasing_request_product.store',
                    'purchasing_request_product.delete'
                ]
            ],
            [
                'display_name' => 'Cotizaciones de compras',
                'key_name' => 'prepro_pext_purchasequotes',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.purchase_quote',
                    'preprojects.purchase.quote.details',
                    'purchasesrequest.show',
                    'preprojects.purchase_quote.accept_decline',
                    'purchase.update_quotedeadline',
                    'purchasesrequest.quote_deadline.complete'
                ]
            ],
            [
                'display_name' => 'Cotización para proyecto',
                'key_name' => 'prepro_pext_projectquote',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.quote',
                    'preprojects.pdf',
                    'preprojects.quote.store',
                    'preprojects.quote.item.store',
                    'preprojects.quote.item.delete',
                    'preprojects.accept',
                    'preprojects.quote.product.store',
                    'preprojects.quote.product.delete',
                    'load.resource_entries',
                    'preproject_quote.rejected',
                    'preproject_quote.canceled'

                ]
            ],
            [
                'display_name' => 'Agregar y editar preproyecto (manera extendida)',
                'key_name' => 'addedit_prepro_pext',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.create',
                    'preprojects.update',
                    'preprojects.store',
                    'preprojects.update'
                ]
            ],
            [
                'display_name' => 'Agregar preproyecto, proyecto y asignación cicsa (manera corta)',
                'key_name' => 'add_prepro_pro_ca_pext',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'project.auto.pext',
                    'pext_project.products.cpe',
                    'project.auto.pint.getEmployees',
                    'project.auto_store.pext',
                ]
            ],
            [
                'display_name' => 'Eliminar preproyecto, proyecto y asignación cicsa',
                'key_name' => 'delete_prepro_pext',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.destroy',
                ]
            ],
        ];

        //Area Proyectos -> Proyectos PINT
        $ppropintSubModule = [
            [
                'display_name' => 'Ver cards de proyectos (incluye buscador)',
                'key_name' => 'see_pro_pint_cards',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                ]
            ],
            [
                'display_name' => 'Agregar proyecto',
                'key_name' => 'add_pro_pint',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.create',
                    'projectmanagement.store',
                    'projectmanagement.add.employee',
                    'projectmanagement.delete.employee',
                ]
            ],
            [
                'display_name' => 'Editar proyecto',
                'key_name' => 'edit_pro_pint',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.update',
                    'projectmanagement.store',
                    'projectmanagement.add.employee',
                    'projectmanagement.delete.employee',
                ]
            ],
            [
                'display_name' => 'Calendario de proyectos',
                'key_name' => 'pro_pint_calendars',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectscalendar.index',
                    'projectscalendar.show'
                ]
            ],
            [
                'display_name' => 'Ver historial de proyectos',
                'key_name' => 'pro_pint_historial',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.historial',
                ]
            ],
            [
                'display_name' => 'Gestión de proyectos adicionales',
                'key_name' => 'pro_pint_additional_management',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.pext.additional.index',
                    'projectmanagement.pext.store.quote',
                    'projectmanagement.pext.additional.store',
                    'pext.additional.expense.general.index',
                    'pext.expenses.storeOrUpdate',
                    'pext.expenses.delete',
                    'projectmanagement.pext.expenses.image.show',
                    'pext.additional.expense.general.getCicsaAssignation',
                    'pext.monthly.additional.expense.general.search_advance',
                    'projectmanagement.pext.expenses.general.export',
                    'projectmanagement.pext.expenses.validate',
                    'projectmanagement.pext.massiveUpdate',
                    'projectmanagement.pext.massiveUpdate.swap',
                    'projectmanagement.pext.export.pdf.quote',
                    'projectmanagement.pext.additional.reject',
                    'projectmanagement.pext.additional.index_rejected',
                    'pext.additional.expense.index',
                ]
            ],
            [
                'display_name' => 'Liquidar proyecto',
                'key_name' => 'pro_pint_liquidate',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.liquidation',
                ]
            ],
            [
                'display_name' => 'Tareas de un proyecto',
                'key_name' => 'pro_pint_tasks',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'tasks.index',
                    'tasks.create',
                    'tasks.edit.status',
                    'tasks.duplicated',
                    'tasks.edit.date',
                    'tasks.delete',
                ]
            ],
            [
                'display_name' => 'Calendario de un proyecto',
                'key_name' => 'pro_pint_one_calendar',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectscalendar.show',
                ]
            ],

            //servicios
            [
                'display_name' => 'Servicios',
                'key_name' => 'pro_pint_services',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.resources',
                ]
            ],

            //Purchase and expenses
            [
                'display_name' => 'Compras y gastos de un proyecto',
                'key_name' => 'pro_pint_one_purchase_expenses',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.purchases_request.index',
                    'projectmanagement.additionalCosts',
                    'projectmanagement.staticCosts',
                    'projectmanagement.purchases_request.details',
                    'projectmanagement.purchases_request.edit',
                    'purchasesrequest.destroy',
                    'projectmanagement.purchases_request.create',
                    'projectmanagement.update_due_date',
                    //expenses
                    'projectmanagement.expenses',
                    'projectmanagement.last12.utilities',
                    'project.expenses.zones.details',
                    'projectmanagement.zoneexpenses.chart',
                    //additional
                    'projectmanagement.additionalCosts.rejected',
                    'additionalcost.excel.export',
                    'additionalcost.archive',
                    'projectmanagement.storeAdditionalCost',
                    'additionalcost.advance.search',
                    'projectmanagement.importAdditionalCost',
                    'zip.additional.descargar',
                    
                    'projectmanagement.additionalCosts.swapCosts',
                    'projectmanagement.addctoaddproject.swapCosts',
                    'projectmanagement.regularprojects.all',
                    'projectmanagement.regularproject.swapCosts',
                    //static
                    'staticcost.archive',
                    'projectmanagement.storeStaticCost',
                    'projectmanagement.updateStaticCost',
                    'projectmanagement.deleteStaticCost',
                    'zip.static.descargar',
                    'staticcost.advance.search',
                    'staticcost.excel.export',
                    'projectmanagement.staticCosts.massiveUpdate',

                    'projectmanagement.statitoaddproject.swapCosts',
                    'projectmanagement.additionalToAdditional.swapCosts',
                ]
            ],
            [
                'display_name' => 'Modificación de gastos variables',
                'key_name' => 'pro_pint_mod_additional_costs',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.updateAdditionalCost',
                ]
            ],
            [
                'display_name' => 'Eliminar gastos variables',
                'key_name' => 'pro_pint_delete_additional_costs',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.deleteAdditionalCost',
                ]
            ],

            [
                'display_name' => 'Validación de gastos por supervisor',
                'key_name' => 'pro_pint_validate_expenses',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.validateAdditionalCost',
                    'projectmanagement.additionalCosts.massiveUpdate',
                    'projectmanagement.rejectAdditionalCost',
                ]
            ],
            [
                'display_name' => 'Validación de gastos por administrativo',
                'key_name' => 'pro_pint_admin_validate_expenses',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.administrative.validation'
                ]
            ],




            //asignar productos
            [
                'display_name' => 'Asignar Productos',
                'key_name' => 'pro_pint_assign_products',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'project.document.index',
                    'project.folder.download',
                    'project.document.store',
                    'project.folder.delete',
                    'documment.management.folders'
                ]
            ],

            //Liquidar productos
            [
                'display_name' => 'Liquidar productos',
                'key_name' => 'pro_pint_liquidate_products',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.liquidate'
                ]
            ],

            // archivos
            [
                'display_name' => 'Archivos de un proyecto',
                'key_name' => 'pro_pint_archives',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.products'
                ]
            ],
        ];

        //Area Proyectos -> Proyectos PEXT
        $ppropextSubModule = [
            [
                'display_name' => 'Ver cards de proyectos (incluye buscador)',
                'key_name' => 'see_pro_pext_cards',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index'
                ]
            ],
            [
                'display_name' => 'Agregar y editar proyecto',
                'key_name' => 'add_pro_pext',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.pext.storeProjectAndAssignation',
                    'projectmanagement.pext.requestProjectOrPreproject',
                ]
            ],
            [
                'display_name' => 'Ver historial de proyectos',
                'key_name' => 'see_pro_pext_history',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.pext.historial',
                ]
            ],
            [
                'display_name' => 'Gestión de proyectos adicionales',
                'key_name' => 'pro_pext_additional_management',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.pext.additional.index',
                    'projectmanagement.pext.store.quote',
                    'projectmanagement.pext.additional.store',
                    'pext.additional.expense.general.index',
                    'pext.expenses.storeOrUpdate',
                    'pext.expenses.delete',
                    'projectmanagement.pext.expenses.image.show',
                    'pext.additional.expense.general.getCicsaAssignation',
                    'pext.monthly.additional.expense.general.search_advance',
                    'projectmanagement.pext.expenses.general.export',
                    'projectmanagement.pext.expenses.validate',
                    'projectmanagement.pext.massiveUpdate',
                    'projectmanagement.pext.massiveUpdate.swap',
                    'projectmanagement.pext.export.pdf.quote',
                    'projectmanagement.pext.additional.reject',
                    'projectmanagement.pext.additional.index_rejected',
                    'pext.additional.expense.index',
                ]
            ],
            [
                'display_name' => 'Tareas de un proyecto',
                'key_name' => 'pro_pext_tasks',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'tasks.index',
                    'tasks.create',
                    'tasks.edit.status',
                    'tasks.duplicated',
                    'tasks.edit.date',
                    'tasks.delete',
                ]
            ],
            [
                'display_name' => 'Calendario de un proyecto',
                'key_name' => 'pro_pext_one_calendar',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectscalendar.show',
                ]
            ],
            // [
            //     'display_name' => 'Liquidar proyecto',
            //     'key_name' => 'pro_pext_liquidate',
            //     'module' => 'ppropext_submodule',
            //     'permissions' => [
            //         'projectmanagement.pext.index',
            //         'projectmanagement.liquidation',
            //     ]
            // ],
            // [
            //     'display_name' => 'Ver detalle de proyecto',
            //     'key_name' => 'pro_pext_details',
            //     'module' => 'ppropext_submodule',
            //     'permissions' => [
            //         'projectmanagement.pext.index',
            //         'projectmanagement.update',
            //     ]
            // ],
            [
                'display_name' => 'Compras y gastos de un proyecto',
                'key_name' => 'pro_pext_purchase_expenses',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.pext.expenses.index',
                    'projectmanagement.pext.historial',
                    'pext.monthly.additional.expense.search_advance',
                    'projectmanagement.pext.massiveUpdate.swap',
                    'pext.expenses.storeOrUpdate',
                    'projectmanagement.pext.massiveUpdate',
                    'pext.expenses.delete',
                    'projectmanagement.pext.expenses.validate',
                    'projectmanagement.pext.expenses.image.show',
                    'projectmanagement.pext.expense_dashboard',
                    'projectmanagement.pext.expenses.export',
                    'projectmanagement.pext.expenset_type_zone',
                    'projectmanagement.pext.expense_dashboard_bar',
                ]
            ],

        ];

        //Area Proyectos -> G. Administrativos
        $padmexpenSubModule = [
            [
                'display_name' => 'Gestión de gastos administrativos',
                'key_name' => 'administrative_expense_manage',
                'module' => 'padmexpen_submodule',
                'permissions' => [
                    'monthproject.index',
                    'projectmanagement.administrativeCosts',
                    'monthproject.store',
                    'monthproject.destroy',
                    'administrativeCosts.archive',
                    'projectmanagement.storeAdministrativeCost',
                    'projectmanagement.updateAdministrativeCost',
                    'projectmanagement.deleteAdministrativeCost',
                    'administrativeCosts.archive',
                    'zip.administrative.descargar',
                    'administrativeCosts.advance.search',
                    'administrativeCosts.excel.export',
                    'projectmanagement.administrativeCosts.massiveUpdate'
                ]
            ]

        ];

        //Area Proyectos -> Checklist
        $pchecklistSubModule = [
            [
                'display_name' => 'Gestión de checklist',
                'key_name' => 'pro_checklist_manager',
                'module' => 'pchecklist_submodule',
                'permissions' => [
                    'checklist.index',
                    'checklist.car.index',
                    'checklist.toolkit.index',
                    'checklist.dailytoolkit.index',
                    'checklist.epp.index',
                    'checklist.car.photo',
                    'checklist.car.destroy',
                    'checklist.toolkit.photo',
                    'checklist.toolkit.destroy',
                    'checklist.dailytoolkit.destroy',
                    'checklist.epp.destroy',
                ]
            ]
        ];

        //Area Proyectos -> Backlog

        //Finanzas -> Presupuestos
        $fbudget_submodule = [
            [
                'display_name' => 'Presupuestos',
                'key_name' => 'finance_budgets',
                'module' => 'fbudget_submodule',
                'permissions' => [
                    'selectproject.index',
                    'initialbudget.index',

                    'finance.cost_line.store',
                    'finance.cost_line.destroy',
                    'finance.cost_line.employees',
                    'finance.cost_line.employee.store',
                    'finance.cost_line.employee.destroy',
                    'finance.cost_line.employee.search',
                    'finance.cost_centers.index',
                    'finance.cost_center.store',
                    'finance.cost_center.destroy',
                    'finance.cost_center.employee.store',
                ]
            ]
        ];

        //Finanzas -> Aprobación de compras
        // $fpapproval_submodule = [
        //     [
        //         'display_name' => 'Aprobación de compras',
        //         'key_name' => 'finance_purchase_approval',
        //         'module' => 'fpapproval_submodule',
        //         'permissions' => [
        //             'managementexpense.index',
        //             'managementexpense.details',
        //             'approve_quote.alarm',
        //         ]
        //     ]
        // ];

        //Finanzas -> Depósitos
        // $fdeposists_submodule = [
        //     [
        //         'display_name' => 'Depósitos',
        //         'key_name' => 'finance_deposits',
        //         'module' => 'fdeposists_submodule',
        //         'permissions' => [
        //             'deposits.index',
        //             'deposits.store',
        //             'deposits.generateSummary'
        //         ]
        //     ]
        // ];

        //Finanzas -> Pagos OC
        // $fpopayment_submodule = [
        //     [
        //         'display_name' => 'Pagos OC',
        //         'key_name' => 'finance_payments_oc',
        //         'module' => 'fpopayment_submodule',
        //         'permissions' => [
        //             'payment.index',
        //             'payment.search',
        //             'payment.alarm'
        //         ]
        //     ]
        // ];
        //Finanzas -> Estado de cuenta
        $faccstatementSubModule = [
            [
                'display_name' => 'Gestión de estado de cuenta',
                'key_name' => 'finance_account_statement',
                'module' => 'faccstatement_submodule',
                'permissions' => [
                    'finance.account_statement',
                    'finance.search_costs',
                    'finance.account_statement.store',
                    'finance.account_statement.search',
                    'finance.account_statement.delete',
                    'finance.account_statement.import',
                    'finance.account_statement.costs',
                    'finance.account_statement.masivedelete',
                    'finance.account_statement.dsdownload',
                ]
            ]
        ];

        //Facturación -> PINT

        $billingpintSubModule = [
            [
                'display_name' => 'Gestión de facturación pint',
                'key_name' => 'billing_pint_manage',
                'module' => 'billingpint_submodule',
                'permissions' => [
                    'cicsa.index',
                    'cicsa.export',
                    'cicsa.assignation.destroy',
                    'assignation.index',
                    'assignation.export',
                    'feasibilities.index',
                    'feasibilities.storeOrUpdate',
                    'feasibilities.export',
                    'material.index',
                    'material.store',
                    'material.update',
                    'material.search.material',
                    'material.export',
                    'material.import',
                    'material.delete',
                    'purchase.order.index',
                    'purchaseOrder.storeOrUpdate',
                    'purchase.order.showDocument',
                    'purchase.order.export',
                    'cicsa.installation.index',
                    'cicsa.installation.store',
                    'cicsa.installation.export',
                    'cicsa.purchase_orders.validation',
                    'cicsa.purchase_orders.validation.update',
                    'cicsa.purchase_orders.validation.export',
                    'cicsa.service_orders',
                    'cicsa.service_orders.update',
                    'cicsa.service_orders.showDocument',
                    'cicsa.service_orders.export',
                    'cicsa.charge_areas',
                    'cicsa.charge_areas.update',
                    'cicsa.charge_areas.showDocument',
                    'cicsa.charge_areas.export',
                    'cicsa.advance.search',
                    'cicsa.export.materials.summary',
                ]
            ]

        ];

        //Facturación -> PEXT
        $billingpextSubModule = [
            [
                'display_name' => 'Gestión de facturación pext',
                'key_name' => 'billing_pext_manage',
                'module' => 'billingpext_submodule',
                'permissions' => [
                    'cicsa.index',
                    'cicsa.export',
                    'cicsa.assignation.destroy',
                    'assignation.index',
                    'assignation.export',
                    'assignation.update',
                    'feasibilities.index',
                    'feasibilities.storeOrUpdate',
                    'feasibilities.export',
                    'material.index',
                    'material.store',
                    'material.update',
                    'material.search.material',
                    'material.export',
                    'material.import',
                    'material.delete',
                    'purchase.order.index',
                    'purchaseOrder.storeOrUpdate',
                    'purchase.order.showDocument',
                    'purchase.order.export',
                    'cicsa.installation.index',
                    'cicsa.installation.store',
                    'cicsa.installation.export',
                    'cicsa.purchase_orders.validation',
                    'cicsa.purchase_orders.validation.update',
                    'cicsa.purchase_orders.validation.export',
                    'cicsa.service_orders',
                    'cicsa.service_orders.update',
                    'cicsa.service_orders.showDocument',
                    'cicsa.service_orders.export',
                    'cicsa.charge_areas',
                    'cicsa.charge_areas.update',
                    'cicsa.charge_areas.showDocument',
                    'cicsa.charge_areas.export',
                    'cicsa.advance.search',
                    'cicsa.export.materials.summary',
                ]
            ]
        ];

        // $pextBillingActionsManagerSubModule = [
        //     [
        //         'display_name' => 'Gestión de Asignación',
        //         'key_name' => 'pext_billing_actions_manager',
        //         'module' => 'billingpext_submodule',
        //         'permissions' => [
        //             'cicsa.index',
        //             'cicsa.advance.search',
        //             'assignation.index',
        //             'assignation.export',
        //             'cicsa.assignation.destroy',
        //             'cicsa.export.materials.summary',
        //         ]
        //     ]
        // ];

        // $pextBillingAssignationsSubModule = [
        //     [
        //         'display_name' => 'Gestión de Asignación pext',
        //         'key_name' => 'pext_billing_assignation',
        //         'module' => 'billingpext_submodule',
        //         'permissions' => [
        //             'cicsa.index',
        //             'cicsa.advance.search',
        //             'assignation.index',
        //             'assignation.export',
        //         ]
        //     ]
        // ];

        // $pextBillingFeasibilitySubModule = [
        //     [
        //         'display_name' => 'Gestión de Factibilidad pext',
        //         'key_name' => 'pext_billing_feasibility',
        //         'module' => 'billingpext_submodule',
        //         'permissions' => [
        //             'cicsa.index',
        //             'cicsa.advance.search',
        //             'feasibilities.index',
        //             'feasibilities.storeOrUpdate',
        //             'feasibilities.export',
        //         ]
        //     ]
        // ];

        // $pextBillingMaterialsSubModule = [
        //     [
        //         'display_name' => 'Gestión de Materiales pext',
        //         'key_name' => 'pext_billing_materials',
        //         'module' => 'billingpext_submodule',
        //         'permissions' => [
        //             'cicsa.index',
        //             'cicsa.advance.search',
        //             'material.index',
        //             'material.store',
        //             'material.update',
        //             'material.search.material',
        //             'material.export',
        //             'material.import',
        //             'material.delete',
        //         ]
        //     ]
        // ];

        // $pextBillingInstallationSubModule = [
        //     [
        //         'display_name' => 'Gestión de Instalación pext',
        //         'key_name' => 'pext_billing_installation',
        //         'module' => 'billingpext_submodule',
        //         'permissions' => [
        //             'cicsa.index',
        //             'cicsa.advance.search',
        //             'cicsa.installation.index',
        //             'cicsa.installation.store',
        //             'cicsa.installation.export',
        //         ]
        //     ]
        // ];

        // $pextBillingPurchaseOrderSubModule = [
        //     [
        //         'display_name' => 'Gestión de Ordenes pext',
        //         'key_name' => 'pext_billing_purchaseOrder',
        //         'module' => 'billingpext_submodule',
        //         'permissions' => [
        //             'cicsa.index',
        //             'cicsa.advance.search',
        //             'purchase.order.index',
        //             'purchaseOrder.storeOrUpdate',
        //             'purchase.order.showDocument',
        //             'purchase.order.export',
        //             'cicsa.purchase_orders.validation',
        //             'cicsa.purchase_orders.validation.update',
        //             'cicsa.purchase_orders.validation.export',
        //             'cicsa.service_orders',
        //             'cicsa.service_orders.update',
        //             'cicsa.service_orders.showDocument',
        //             'cicsa.service_orders.export',
        //             'cicsa.charge_areas',
        //             'cicsa.charge_areas.update',
        //             'cicsa.charge_areas.showDocument',
        //             'cicsa.charge_areas.export',
        //         ]
        //     ]
        // ];

        // $pextBillingPurchaseOrderSubModule = [
        //     [

        //     ]
        // ];

        // $pextBillingPurchaseOrderValidationSubModule = [
        //     [

        //     ]
        // ];

        // $pextBillingServiceOrderSubModule = [
        //     [

        //     ]
        // ];

        $cchanapproSubModule = [
            [
                'display_name' => 'Aprobacion de Cambios',
                'key_name' => 'CarManager',
                'module' => 'cchanappro_submodule',
                'permissions' => [
                    'fleet.cars.show_approvals_document',
                    'fleet.cars.approve.change',
                    'fleet.cars.rejected.change',
                    'fleet.cars.index.approvel',
                ],
            ],
        ];

        $cSeeAllMobile = [
            [
                'display_name' => 'Ver Todos los Vehiculos',
                'key_name' => 'see_all_mobile',
                'module' => 'cmobileunit_submodule',
                'permissions' => [
                    'fleet.cars.index',
                    'fleet.cars.show_checklist.send_images',
                    'fleet.cars.show.image',
                    'fleet.cars.show_checklist',
                    'fleet.cars.show_invoice',
                    'fleet.cars.show_checklist.accept_or_decline',
                    'fleet.cars.search',

                    'fleet.cars.alarms',
                    'fleet.cars.checklist.alarms',
                    'fleet.cars.specific.alarms',
                    'fleet.cars.alarms.changelogs',
                ]
            ]
        ];

        $cSeeMobile = [
            [
                'display_name' => 'Ver Vehiculos propios',
                'key_name' => 'see_mobile',
                'module' => 'cmobileunit_submodule',
                'permissions' => [
                    'fleet.cars.index',
                    'fleet.cars.show_checklist.send_images',
                    'fleet.cars.show.image',
                    'fleet.cars.show_checklist',
                    'fleet.cars.show_invoice',
                    'fleet.cars.show_checklist.accept_or_decline',
                    'fleet.cars.search',

                    'fleet.cars.alarms',
                    'fleet.cars.checklist.alarms',
                    'fleet.cars.specific.alarms',
                    'fleet.cars.alarms.changelogs',
                ]
            ]
        ];

        $cmobileActionsManager = [
            [
                'display_name' => 'Acciones de Manager',
                'key_name' => 'mobile_actions_manager',
                'module' => 'cmobileunit_submodule',
                'permissions' => [
                    'fleet.cars.index',
                    'fleet.cars.show_checklist.send_images',
                    'fleet.cars.show.image',
                    'fleet.cars.show_checklist',
                    'fleet.cars.show_checklist.accept_or_decline',
                    'fleet.cars.destroy_changelog',
                    'fleet.cars.show_invoice',
                    'fleet.cars.update_changelog',
                    'fleet.cars.store_changelog',
                    'fleet.cars.show_documents',
                    'fleet.cars.update.document',
                    'fleet.cars.store_document',
                    'fleet.cars.search',
                    'fleet.cars.store',
                    'fleet.cars.update',
                    'fleet.cars.destroy',

                    'fleet.cars.alarms',
                    'fleet.cars.checklist.alarms',
                    'fleet.cars.specific.alarms',
                    'fleet.cars.alarms.changelogs',
                ]
            ]
        ];

        $cmobileActions = [
            [
                'display_name' => 'Acciones de Propietario',
                'key_name' => 'mobile_actions',
                'module' => 'cmobileunit_submodule',
                'permissions' => [
                    'fleet.cars.index',
                    'fleet.cars.show_checklist.send_images',
                    'fleet.cars.show.image',
                    'fleet.cars.show_checklist',
                    'fleet.cars.show_checklist.accept_or_decline',
                    'fleet.cars.destroy_changelog',
                    'fleet.cars.show_invoice',
                    'fleet.cars.update_changelog',
                    'fleet.cars.store_changelog',
                    'fleet.cars.show_documents',
                    'fleet.cars.update.document',
                    'fleet.cars.store_document',
                    'fleet.cars.search',
                ]
            ]
        ];

        $sharedoc_submodule = [
            [
                'display_name' => 'Manager sharepoint',
                'key_name' => 'SharePoint',
                'module' => 'sharedoc_submodule',
                'permissions' => [
                    'sharepoint.index',
                ],
            ],
        ];

        //Huawei
        $huawei_sites_submodule = [
            [
                'display_name' => 'Encargado de Sites Huawei',
                'key_name' => 'huawei_sites',
                'module' => 'huawei_sites_submodule',
                'permissions' => [
                    'huawei.sites',
                    'huawei.sites.post',
                    'huawei.sites.put',
                    'huawei.sites.delete',
                    'huawei.sites.verify',
                    'huawei.sites.search',
                ]
            ],
        ];

        $huawei_projects_submodule = [
            [
                'display_name' => 'Encargado de Proyectos',
                'key_name' => 'huawei_projects',
                'module' => 'huawei_projects_submodule',
                'permissions' => [
                    'huawei.projects',
                    'huawei.projects.search',
                    'huawei.projects.create',
                    'huawei.projects.store',
                    'huawei.projects.import.baselines',
                    'huawei.projects.baselines.template',
                    'huawei.projects.toupdate',
                    'huawei.projects.balance',
                    'huawei.projects.liquidateproject',
                    'huawei.projects.cancelproject',
                    'huawei.projects.stopped.resume',

                    'huawei.projects.generalbalance',
                    'huawei.projects.generalbalance.expensesbyzone',
                    'huawei.projects.general.expenses',
                    'huawei.projects.general.expenses.search',
                    'huawei.projects.general.expenses.searchadvance',
                    'huawei.projects.general.expenses.store',
                    'huawei.projects.general.expenses.update',
                    'huawei.projects.general.expenses.delete',
                    'huawei.projects.general.expenses.validate',
                    'huawei.projects.general.expenses.showimage',
                    'huawei.projects.general.expenses.export',
                    'huawei.projects.general.expenses.massiveupdate',
                    'huawei.projects.general.expenses.fetchsites',
                    'huawei.projects.general.expenses.fetchprojects',
                    'huawei.projects.general.expenses.import',
                    'huawei.projects.general.expenses.donwloadtemplate',
                    'huawei.projects.general.expenses.downloadzip',
                    'huawei.projects.monthlyexpenses.downloadimages',

                    //Project Costs
                    'huawei.projects.additionalcosts',
                    'huawei.projects.additionalcosts.showimage',
                    'huawei.projects.additionalcosts.search',
                    'huawei.projects.additionalcosts.summary',
                    'huawei.projects.additionalcosts.advancedsearch',
                    'huawei.projects.additionalcosts.export',

                    //Liquidations
                    'huawei.projects.liquidations',
                    'huawei.projects.liquidations.history',
                    'huawei.projects.liquidations.post',
                    'huawei.projects.liquidations.searchadvance',
                    'huawei.projects.liquidations.massiveliquidation',

                    //Resources
                    'huawei.projects.resources',
                    'huawei.projects.resources.search',
                    'huawei.projects.resources.store',
                    'huawei.projects.refund',
                    'huawei.projects.resources.searchdetails',
                    'huawei.projects.create.fetchsites'
                ]
            ],
            // [
            //     'display_name' => 'Agregar proyecto',
            //     'key_name' => 'huawei_projects_add',
            //     'module' => 'huawei_projects_submodule',
            //     'permissions' => ['huawei.projects', 'huawei.projects.search', 'huawei.projects.create', 'huawei.projects.store', 'huawei.projects.import.baselines', 'huawei.projects.baselines.template']
            // ],
            // [
            //     'display_name' => 'Ver proyecto',
            //     'key_name' => 'huawei_projects_see_project',
            //     'module' => 'huawei_projects_submodule',
            //     'permissions' => ['huawei.projects', 'huawei.projects.search', 'huawei.projects.toupdate', 'huawei.projects.balance']
            // ],
            // [
            //     'display_name' => 'Editar proyecto',
            //     'key_name' => 'huawei_projects_edit',
            //     'module' => 'huawei_projects_submodule',
            //     'permissions' => ['huawei.projects', 'huawei.projects.search', 'huawei.projects.toupdate', 'huawei.projects.update', 'huawei.projects.import.baselines', 'huawei.projects.baselines.template']
            // ],
            // [
            //     'display_name' => 'Administrar proyectos',
            //     'key_name' => 'huawei_projects_admin',
            //     'module' => 'huawei_projects_submodule',
            //     'permissions' => ['huawei.projects.liquidateproject', 'huawei.projects.cancelproject', 'huawei.projects.stopped.resume']
            // ],

            //provisional
            // [
            //     'display_name' => 'Eliminar proyecto',
            //     'key_name' => 'huawei_projects_delete',
            //     'module' => 'project_submodule',
            //     'permissions' => []
            // ],
        ];
        $huawei_inventory_submodule = [
            [
                'display_name' => 'Encargado de Inventario huawei',
                'key_name' => 'huawei_inventory',
                'module' => 'huawei_inventory_submodule',
                'permissions' => [
                    'huawei.inventory.show',
                    'huawei.inventory.pendingorders',
                    'huawei.inventory.pendingorders.searchadvance',
                    'huawei.inventory.pendingorders.assignguide',
                    'huawei.inventory.pendingorders.fetch',
                    'huawei.inventory.show.search',
                    'huawei.inventory.create',
                    'huawei.inventory.store',
                    'huawei.inventory.store.order',
                    'huawei.inventory.create.brand',
                    'huawei.inventory.create.brandmodel',
                    'huawei.inventory.show.details',
                    'huawei.inventory.show.details.withoutdiu',
                    'huawei.inventory.show.details.search',
                    'huawei.inventory.details.refund',
                    'huawei.inventory.refunds',
                    'huawei.inventory.refunds.search',
                    'huawei.inventory.showguide',
                    'huawei.inventory.details.assigndiu',
                    'huawei.inventory.export',
                    'huawei.inventory.general.equipments',
                    'huawei.inventory.general.equipments.searchadvance',
                    'huawei.inventory.general.equipments.massiveupdate',
                    'huawei.inventory.general.equipments.search',
                    'huawei.inventory.update.entrydetail',
                    'huawei.inventory.update.entrydetail.site',
                    'huawei.inventory.create.verifyserie',
                    'huawei.inventory.create.getinventory',
                ]
            ]
        ];

        $huawei_inventory_internal_submodule = [
            [
                'display_name' => 'Encargado de Inventario Interno',
                'key_name' => 'Inventario Interno',
                'module' => 'huawei_internal_inventory_submodule',
                'permissions' => [
                    'huawei.quickmaterials',
                    'huawei.quickmaterials.search',
                    'huawei.quickmaterials.store',
                    'huawei.quickmaterials.update',
                    'huawei.quickmaterials.delete',
                    'huawei.quickmaterials.verifyname',

                    'huawei.quickmaterials.details',
                    'huawei.quickmaterials.details.store',
                    'huawei.quickmaterials.details.delete',

                    'huawei.quickmaterials.details.output.store',
                    'huawei.quickmaterials.details.output.delete',
                    'huawei.quickmaterials.details.output.fetchprojects',
                    'huawei.quickmaterials.details.output.selectproject',
                ]
            ],
        ];

        $huawei_interal_guides_submodule = [
            [
                'display_name' => 'Encargado de Guias Internas',
                'key_name' => 'Guias Internas',
                'module' => 'huawei_interal_guides_submodule',
                'permissions' => [
                    'huawei.internalguides',
                    'huawei.internalguides.store',
                    'huawei.internalguides.delete',
                    'huawei.internalguides.show',
                ]
            ],
        ];

        $huawei_special_returns_submodule = [
            [
                'display_name' => 'Encargado de Devoluciones Especiales',
                'key_name' => 'Devoluciones Especiales',
                'module' => 'huawei_special_returns_submodule',
                'permissions' => [
                    'huawei.specialrefunds',
                    'huawei.specialrefunds.search',
                    'huawei.specialrefunds.store',
                    'huawei.specialrefunds.update',
                    'huawei.specialrefunds.delete',
                ]
            ],
        ];

        $Submodules = [
            $UsersSubModule,
            $RolesSubModule,

            $HremployeesSubModule,
            $HreemployeesSubModule,
            //nomina
            $hrspreedsheet_submodule,
            $HrresdocSubModule,
            $HrhrstateSubModule,

            $pproviderSubModule,
            // $pprequest_submodule,
            // $pporder_submodule,
            // $ppcpurchase_submodule,
            //Solicitudes
            //Ordenes
            //Compras Completadas

            $pclientsSubModule,
            $pproSubModule,
            $pprepintSubModule,
            $prepextSubModule,
            $ppropintSubModule,
            $ppropextSubModule,
            $padmexpenSubModule,
            $pchecklistSubModule,

            //presupuestos
            $fbudget_submodule,
            //aprobacion de compras
            // $fpapproval_submodule,
            //depósitos
            // $fdeposists_submodule,
            //pagos OC
            // $fpopayment_submodule,
            //Estado de cuenta
            $faccstatementSubModule,

            $billingpintSubModule,
            $billingpextSubModule,
            // $pextBillingActionsManagerSubModule,
            // $pextBillingAssignationsSubModule,
            // $pextBillingFeasibilitySubModule,
            // $pextBillingMaterialsSubModule,
            // $pextBillingInstallationSubModule,
            // $pextBillingPurchaseOrderSubModule,

            $cchanapproSubModule,
            // $cmobileunitSubModule,

            $cSeeAllMobile,
            $cSeeMobile,
            $cmobileActionsManager,
            $cmobileActions,

            //Sharepoint
            $sharedoc_submodule,

            //Huawei
            $huawei_sites_submodule,
            $huawei_projects_submodule,
            $huawei_inventory_submodule,
            $huawei_inventory_internal_submodule,
            $huawei_interal_guides_submodule,
            $huawei_special_returns_submodule,

            //Productos
            $iproduct_submodule
        ];

        foreach ($Submodules as $sm) {
            foreach ($sm as $func) {
                $module = Module::where('name', $func['module'])->first();
                $functionality = Functionality::create([
                    'key_name' => $func['key_name'],
                    'display_name' => $func['display_name'],
                    'module_id' => $module->id
                ]);

                foreach ($func['permissions'] as $funcperm) {
                    $permission = Permission::where('name', $funcperm)->first();
                    FunctionalityPermission::create([
                        'functionality_id' => $functionality->id,
                        'permission_id' => $permission->id
                    ]);
                }
            }
        }
    }
}
