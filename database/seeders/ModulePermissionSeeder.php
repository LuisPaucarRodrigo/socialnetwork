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
                'permissions' => ['documents.index', 'documents.zipSection', 'documents.updateSection', 'documents.sections', 'documents.storeSection', 'documents.subdivisions', 'documents.zipSubdivision', 'documents.updateSubdivision', 'documents.storeSubdivision' ]
            ],
            [
                'display_name' => 'Eliminar secciones nuevas',
                'group_name' => 'delete_new_sections_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.sections', 'documents.destroySection' ]
            ],
            [
                'display_name' => 'Eliminar subdivisiones nuevas',
                'group_name' => 'delete_new_subdivisions_hr',
                'module' => 'hrresdoc_submodule',
                'permissions' => ['documents.index', 'documents.sections', 'documents.subdivision', 'documents.destroySubdivision' ]
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
                'display_name' => 'Ver tabla Estatus RRHH',
                'group_name' => 'see_estatus_rrhh_table',
                'module' => 'hrhrstate_submodule',
                'permissions' => ['document.rrhh.status']
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


        ];
    }
}
