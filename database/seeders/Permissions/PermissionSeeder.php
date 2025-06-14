<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Support\RouteDefinitions\CicsaRoutes;
use App\Support\RouteDefinitions\DocumentGestionRoutes;
use App\Support\RouteDefinitions\FinanceRoutes;
use App\Support\RouteDefinitions\FleetCarRoutes;
use App\Support\RouteDefinitions\HumanResourceRoutes;
use App\Support\RouteDefinitions\InventoryRoutes;
use App\Support\RouteDefinitions\ProjectRoutes;
use App\Support\RouteDefinitions\ShoppingAreaRoutes;
use App\Support\RouteDefinitions\UserAdminRoutes;

use App\Models\Permission;
use App\Support\RouteDefinitions\HuaweiRoutes;
use App\Support\RouteDefinitions\SharePointRoutes;

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

        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, CicsaRoutes::all());
        Permission::insert($routes);

        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, DocumentGestionRoutes::all());
        Permission::insert($routes);

        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, FinanceRoutes::all());

        Permission::insert($routes);
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, FleetCarRoutes::all());

        Permission::insert($routes);
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, HumanResourceRoutes::all());

        Permission::insert($routes);
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, InventoryRoutes::all());

        Permission::insert($routes);
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, ShoppingAreaRoutes::all());

        Permission::insert($routes);
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, ProjectRoutes::all());

        Permission::insert($routes);
        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, SharePointRoutes::all());

        Permission::insert($routes);

        $routes = array_map(function ($route) {
            return ['name' => $route['name']];
        }, HuaweiRoutes::all());
        Permission::insert($routes);
    }
}
