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
        $UsersSubModule = [
            [
                'display_name' => 'Ver tabla usuarios y buscar usuarios',
                'group_name' => 'see_users_table',
                'module_id' => 50,
                'permissions_ids' => [5, 6]
                //users.index, users.search
            ],
            [
                'display_name' => 'Agregar nuevos usuarios',
                'group_name' => 'add_new_users',
                'module_id' => 50,
                'permissions_ids' => [5,   ]
            ],
            [
                'display_name' => 'Ver detalle de usuarios',
                'group_name' => 'see_users_detail',
                'module_id' => 50,
                'permissions_ids' => [5, 7]
            ]

        ];
    }
}
