<?php

namespace App\Support\RouteDefinitions\ShoppingArea;

use App\Http\Controllers\ShoppingArea\MCSController;

class MCSRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'shopping_area/MCSManagement_index',
                'method' => 'get',
                'action' => [MCSController::class, 'index'],
                'permission' => true,
                'name' => 'providersmanagement.MCSManagement.index',
            ],
            [
                'uri' => 'shopping_area/MCSManagement_getData',
                'method' => 'get',
                'action' => [MCSController::class, 'getData'],
                'permission' => true,
                'name' => 'providersmanagement.MCSManagement.getData',
            ],
            [
                'uri' => 'shopping_area/MCSManagement_search',
                'method' => 'post',
                'action' => [MCSController::class, 'search'],
                'permission' => true,
                'name' => 'providersmanagement.MCSManagement.search',
            ],
            [
                'uri' => 'shopping_area/MCSManagement_store',
                'method' => 'post',
                'action' => [MCSController::class, 'store'],
                'permission' => true,
                'name' => 'providersmanagement.MCSManagement.category.store',
            ],
            [
                'uri' => 'shopping_area/MCSManagement_edit/{category}',
                'method' => 'post',
                'action' => [MCSController::class, 'edit'],
                'permission' => true,
                'name' => 'providersmanagement.MCSManagement.category.edit',
            ],
            [
                'uri' => 'shopping_area/MCSManagement_delete/{item}',
                'method' => 'delete',
                'action' => [MCSController::class, 'delete'],
                'permission' => true,
                'name' => 'providersmanagement.MCSManagement.category.delete',
            ]
        ];
    }
}
