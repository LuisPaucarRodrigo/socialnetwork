<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Support\RouteDefinitions\UserAdminRoutes;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, UserAdminRoutes::all());
        Permission::insert($routes);


    }
}
