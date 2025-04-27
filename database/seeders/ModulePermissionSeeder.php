<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulePermissionSeeder extends Seeder
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
                'group_name' => 'see_users_table',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.search']
            ],
            [
                'display_name' => 'Agregar usuario',
                'group_name' => 'add_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'register', 'register.post']
            ],
            [
                'display_name' => 'Ver usuario',
                'group_name' => 'see_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.details']
            ],
            [
                'display_name' => 'Editar usuario (incluye contraseña)',
                'group_name' => 'edit_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.edit', 'users.update', 'password.update']
            ],
            [
                'display_name' => 'Eliminar usuario',
                'group_name' => 'delete_user',
                'module' => 'user_submodule',
                'permissions' => ['users.index', 'users.destroy']
            ]
        ];

        // Usuarios y Roles -> Roles
        $RolesSubModule = [
            [
                'display_name' => 'Ver tabla roles',
                'group_name' => 'see_roles_table',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index',]
            ],
            [
                'display_name' => 'Agregar rol',
                'group_name' => 'add_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.store']
            ],
            [
                'display_name' => 'Ver rol',
                'group_name' => 'see_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.details']
            ],
            [
                'display_name' => 'Editar rol',
                'group_name' => 'edit_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.update']
            ],
            [
                'display_name' => 'Eliminar rol',
                'group_name' => 'delete_role',
                'module' => 'roles_submodule',
                'permissions' => ['rols.index', 'rols.delete']
            ],
        ];

        // Recursos Humanos -> Empleados
        $HremployeesSubModule = [
            [
                'display_name' => 'Ver tabla empleados activos (incluye buscar)',
                'group_name' => 'see_active_employees_table',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.search']
            ],
            [
                'display_name' => 'Ver tabla de empleados inactivos (incluye buscar)',
                'group_name' => '',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.search']
            ],
            [
                'display_name' => 'Agregar empleado',
                'group_name' => 'add_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.create', 'management.employees.store']
            ],
            [
                'display_name' => 'Ver empleado',
                'group_name' => 'see_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.show', 'management.employees.information.details.download']
            ],
            [
                'display_name' => 'Editar empleado',
                'group_name' => 'edit_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.edit', 'management.employees.update']
            ],
            [
                'display_name' => 'Despido y recontratación de empleado',
                'group_name' => 'fired_reentry_employee',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees', 'management.employees.fired', 'roles_submodule']
            ],
            [
                'display_name' => 'Alarma cumpleaños empleados',
                'group_name' => 'happy_birthday_alarm',
                'module' => 'hremployees_submodule',
                'permissions' => ['management.employees.happy.birthday']
            ]
        ];

        // Recursos Humanos -> Empleados Externos
        $HreemployeesSubModule = [
            [
                'display_name' => 'Ver tabla empleados externos',
                'group_name' => 'see_external_employee_table',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index']
            ],
            [
                'display_name' => 'Agregar y editar empleado externo',
                'group_name' => 'add_external_employee',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'management.external.storeorupdate']
            ],
            [
                'display_name' => 'Eliminar empleado externo',
                'group_name' => 'delete_external_employee',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'employees.external.delete']
            ],
            [
                'display_name' => 'Ver y descargar currículum vitae de empleado externo',
                'group_name' => 'see_external_employee_document',
                'module' => 'hreemployees_submodule',
                'permissions' => ['employees.external.index', 'employees.external.preview.curriculum_vitae']
            ],
        ];

        // Recursos Humanos -> Nómina

        // Recursos Humanos -> Documentos
        $HrresdocSubModule = [
            [
                'display_name' => 'Ver cards de documentos (incluye buscador y filtro)',
                'group_name' => 'see_document_cards_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.filter.section', 'documents.filter.subdivision', 'documents.search']
            ],
            [
                'display_name' => 'Agregar documento',
                'group_name' => 'add_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.create']
            ],
            [
                'display_name' => 'Gestionar secciones y subdivisiones (sin eliminar)',
                'group_name' => 'manage_sections_subdivisions_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.zipSection', 'documents.updateSection', 'documents.sections', 'documents.storeSection', 'documents.subdivisions', 'documents.zipSubdivision', 'documents.updateSubdivision', 'documents.storeSubdivision']
            ],
            [
                'display_name' => 'Eliminar secciones nuevas',
                'group_name' => 'delete_new_sections_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.sections', 'documents.destroySection']
            ],
            [
                'display_name' => 'Eliminar subdivisiones nuevas',
                'group_name' => 'delete_new_subdivisions_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.sections', 'documents.subdivision', 'documents.destroySubdivision']
            ],
            [
                'display_name' => 'Editar documento',
                'group_name' => 'edit_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.update']
            ],
            [
                'display_name' => 'Ver documento',
                'group_name' => 'see_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.show']
            ],
            [
                'display_name' => 'Descargar documento',
                'group_name' => 'download_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.download']
            ],
            [
                'display_name' => 'Eliminar documento',
                'group_name' => 'delete_document_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.destroy']
            ],

        ];

        // Recursos Humanos -> Estatus RRHH
        $HrhrstateSubModule = [
            [
                'display_name' => 'Ver tabla Estatus RRHH y filtro',
                'group_name' => 'see_estatus_rrhh_table',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status']
            ],
            [
                'display_name' => 'Modificar estados de documentos',
                'group_name' => 'modify_document_status',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.rrhh.status.store', 'document.rrhh.status.destroy', 'document.rrhh.status.in_expdate']
            ],
            [
                'display_name' => 'Gestionar documentos grupales (sin eliminar)',
                'group_name' => 'manage_grupal_documents_hr',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.grupal_documents.index', 'document.grupal_documents.store', 'document.grupal_documents.update', 'document.grupal_documents.download']
            ],
            [
                'display_name' => 'Eliminar documentos grupales',
                'group_name' => 'delete_grupal_documents_hr',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status', 'document.grupal_documents.index', 'document.grupal_documents.destroy']
            ]
        ];

        // Inventario -> Productos
        // Inventario -> Almacenes

        //Area de Compras -> Proveedores
        $pproviderSubModule = [
            [
                'display_name' => 'Ver tabla de proveedores (incluye buscar)',
                'group_name' => 'see_providers_table',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index']
            ],
            [
                'display_name' => 'Agregar proveedor',
                'group_name' => 'add_provider',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'providersmanagement.store', 'provider.segments.list']
            ],
            [
                'display_name' => 'Editar proveedor',
                'group_name' => 'edit_provider',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'providersmanagement.update', 'provider.segments.list']
            ],
            [
                'display_name' => 'Gestionar categorias y segmentos',
                'group_name' => 'manage_categorys_segments',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'provider.category.post', 'provider.segment.post']
            ],
            [
                'display_name' => 'Eliminar proveedor',
                'group_name' => 'delete_provider',
                'module' => 'pprovider_submodule',
                'permissions' => ['providersmanagement.index', 'providersmanagement.destroy']
            ],
        ];

        //Area de Compras -> Solicitudes
        //Area de Compras -> Ordenes
        //Area de Compras -> Compras Completadas

        //Area Proyectos -> Clientes
        $pclientsSubModule = [
            [
                'display_name' => 'Ver tabla de clientes (incluye buscar)',
                'group_name' => 'see_customers_table',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.search']
            ],
            [
                'display_name' => 'Agregar cliente',
                'group_name' => 'add_client',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.store']
            ],
            [
                'display_name' => 'Editar cliente',
                'group_name' => 'edit_client',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.update']
            ],
            [
                'display_name' => 'Eliminar cliente',
                'group_name' => 'delete_client',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.destroy']
            ],
            [
                'display_name' => 'Gestionar contactos de cliente (no eliminar)',
                'group_name' => 'manage_client_contacts',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.contacts.index', 'customers.contacts.store', 'customers.contacts.update']
            ],
            [
                'display_name' => 'Eliminar contacto de cliente',
                'group_name' => 'delete_client_contact',
                'module' => 'pclients_submodule',
                'permissions' => ['customers.index', 'customers.contacts.index', 'customers.contacts.destroy.update']
            ],
        ];

        //Area Proyectos -> PRO
        $pproSubModule = [
            [
                'display_name' => 'Ver tabla títulos',
                'group_name' => 'see_pro_titles_table',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles']
            ],
            [
                'display_name' => 'Agregar título ',
                'group_name' => 'add_pro_title',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.titles.post']
            ],
            [
                'display_name' => 'Editar título ',
                'group_name' => 'edit_pro_title',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.titles.put']
            ],
            [
                'display_name' => 'Eliminar títulos ',
                'group_name' => 'delete_pro_title',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.titles.delete']
            ],
            [
                'display_name' => 'Ver tabla códigos',
                'group_name' => 'see_pro_codes_table',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes']
            ],
            [
                'display_name' => 'Agregar código',
                'group_name' => 'add_pro_code',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.codes.post']
            ],
            [
                'display_name' => 'Agregar imágenes de ejemplo del código',
                'group_name' => 'add_pro_code_images',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.code.images.store']
            ],
            [
                'display_name' => 'Ver imágenes de ejemplo del código',
                'group_name' => 'see_pro_code_images',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.code.images.show', 'preprojects.code.images.index']
            ],
            [
                'display_name' => 'Eliminar imágenes de ejemplo del código',
                'group_name' => 'delete_pro_code_images',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.code', 'preprojects.code.images.index', 'preprojects.code.images.delete']
            ],
            [
                'display_name' => 'Editar código',
                'group_name' => 'edit_pro_code',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.codes.put']
            ],
            [
                'display_name' => 'Eliminar código',
                'group_name' => 'delete_pro_code',
                'module' => 'ppro_submodule',
                'permissions' => ['preprojects.titles', 'preprojects.codes', 'preprojects.codes.delete']
            ],
        ];

        //Area Proyectos -> Anteproyectos PINT
        $pprepintSubModule = [
            [
                'display_name' => 'Ver cards de anteproyectos activos, aprobados y anulados (incluye buscador)',
                'group_name' => 'see_prepro_pint_cards',
                'module' => 'pprepint_submodule',
                'permissions' => ['preprojects.index']
            ],
            [
                'display_name' => 'Asignar usuarios',
                'group_name' => 'add_prepro_pint_users',
                'module' => 'pprepint_submodule',
                'permissions' => ['preprojects.index', 'preprojects.assign.users']
            ],
            [
                'display_name' => 'Descargar imágenes',
                'group_name' => 'download_prepro_pint_images',
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
                'group_name' => 'prepro_pint_photoreport',
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
                'group_name' => 'prepro_pint_warehouseproducts',
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
                'group_name' => 'prepro_pint_purchaserequest',
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
                'group_name' => 'prepro_pint_purchasequotes',
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
                'group_name' => 'prepro_pint_projectquote',
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
                'group_name' => 'addedit_prepro_pint',
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
                'display_name' => 'Agregar y editar preproyecto (manera extendida)',
                'group_name' => 'addedit_prepro_pint',
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
                'group_name' => 'add_prepro_pro_ca_pint',
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
                'group_name' => 'delete_prepro_pint',
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
                'group_name' => 'see_prepro_pext_cards',
                'module' => 'pprepext_submodule',
                'permissions' => ['preprojects.index']
            ],
            [
                'display_name' => 'Asignar usuarios',
                'group_name' => 'add_prepro_pext_users',
                'module' => 'pprepext_submodule',
                'permissions' => ['preprojects.index', 'preprojects.assign.users']
            ],
            [
                'display_name' => 'Descargar imágenes',
                'group_name' => 'download_prepro_pext_images',
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
                'group_name' => 'prepro_pext_photoreport',
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
                'group_name' => 'prepro_pext_warehouseproducts',
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
                'group_name' => 'prepro_pext_purchaserequest',
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
                'group_name' => 'prepro_pext_purchasequotes',
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
                'group_name' => 'prepro_pext_projectquote',
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
                'group_name' => 'addedit_prepro_pext',
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
                'display_name' => 'Agregar y editar preproyecto (manera extendida)',
                'group_name' => 'addedit_prepro_pext',
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
                'group_name' => 'add_prepro_pro_ca_pext',
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
                'group_name' => 'delete_prepro_pext',
                'module' => 'pprepext_submodule',
                'permissions' => [
                    'preprojects.index',
                    'preprojects.destroy',
                ]
            ],
        ];

        //Area Proyectos -> Anteproyectos PEXT
        $ppropintSubModule = [
            [
                'display_name' => 'Ver cards de proyectos (incluye buscador)',
                'group_name' => 'see_pro_pint_cards',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                ]
            ],
            [
                'display_name' => 'Agregar proyecto',
                'group_name' => 'add_pro_pint',
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
                'group_name' => 'edit_pro_pint',
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
                'group_name' => 'pro_pint_calendars',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectscalendar.index',
                    'projectscalendar.show'
                ]
            ],
            [
                'display_name' => 'Ver historial de proyectos',
                'group_name' => 'pro_pint_historial',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.historial',
                ]
            ],
            [
                'display_name' => 'Gestión de proyectos adicionales',
                'group_name' => 'pro_pint_additional_management',
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
                'group_name' => 'pro_pint_liquidate',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectmanagement.liquidation',
                ]
            ],
            [
                'display_name' => 'Tareas de un proyecto',
                'group_name' => 'pro_pint_tasks',
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
                'group_name' => 'pro_pint_one_calendar',
                'module' => 'ppropint_submodule',
                'permissions' => [
                    'projectmanagement.index',
                    'projectscalendar.show',
                ]
            ],

            //servicios
            [
                'display_name' => 'Compras y gastos de un proyecto',
                'group_name' => 'pro_pint_one_purchase_expenses',
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
                    'projectmanagement.updateAdditionalCost',
                    'projectmanagement.deleteAdditionalCost',
                    'additionalcost.advance.search',
                    'projectmanagement.importAdditionalCost',
                    'zip.additional.descargar',
                    'projectmanagement.validateAdditionalCost',
                    'projectmanagement.additionalCosts.massiveUpdate',
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
                ]
            ],


            //asignar productos
            // liquidaciones
            [
                'display_name' => 'Archivos de un proyecto',
                'group_name' => 'pro_pint_archives',
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
        ];

        $ppropextSubModule = [
            [
                'display_name' => 'Ver cards de proyectos (incluye buscador)',
                'group_name' => 'see_pro_pext_cards',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index'
                ]
            ],
            [
                'display_name' => 'Agregar y editar proyecto',
                'group_name' => 'see_pro_pext_cards',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.pext.storeProjectAndAssignation',
                    'projectmanagement.pext.requestProjectOrPreproject',
                ]
            ],
            [
                'display_name' => 'Ver historial de proyectos',
                'group_name' => 'see_pro_pext_history',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.pext.historial',
                ]
            ],
            [
                'display_name' => 'Gestión de proyectos adicionales',
                'group_name' => 'pro_pext_additional_management',
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
                'group_name' => 'pro_pext_tasks',
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
                'group_name' => 'pro_pext_one_calendar',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectscalendar.show',
                ]
            ],
            [
                'display_name' => 'Liquidar proyecto',
                'group_name' => 'pro_pext_liquidate',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.liquidation',
                ]
            ],
            [
                'display_name' => 'Ver detalle de proyecto',
                'group_name' => 'pro_pext_details',
                'module' => 'ppropext_submodule',
                'permissions' => [
                    'projectmanagement.pext.index',
                    'projectmanagement.update',
                ]
            ],
            [
                'display_name' => 'Compras y gastos de un proyecto',
                'group_name' => 'pro_pext_purchase_expenses',
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



        [
            'display_name' => '',
            'group_name' => '',
            'module' => '',
            'permissions' => []
        ]

        ;
        $Submodules = [
            $UsersSubModule,
            $RolesSubModule,

            $HremployeesSubModule,
            $HreemployeesSubModule,
            //nomina
            $$HrresdocSubModule,
            $HrhrstateSubModule,

            $pproviderSubModule,
            //Solicitudes
            //Ordenes
            //Compras Completadas

            $pclientsSubModule,
            $pproSubModule,
            $pprepintSubModule,
            $prepextSubModule,
            $ppropintSubModule,
            $ppropextSubModule,


        ];
    }
}
