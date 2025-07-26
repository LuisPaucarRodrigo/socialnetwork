<?php

namespace App\Support\RouteDefinitions\ShoppingArea;

use App\Http\Controllers\ShoppingArea\ProviderController;

class ProvidersRoutes
{
    public static function all(): array
    {
        return [
            // Providers
            [
                'uri' => 'shopping_area/providers',
                'method' => 'any',
                'action' => [ProviderController::class, 'index'],
                'permission' => true,
                'name' => 'providersmanagement.index',
            ],
            [
                'uri' => 'shopping_area/providers/create',
                'method' => 'get',
                'action' => [ProviderController::class, 'create'],
                'permission' => true,
                'name' => 'providersmanagement.create',
            ],
            [
                'uri' => 'shopping_area/providers/store',
                'method' => 'post',
                'action' => [ProviderController::class, 'store'],
                'permission' => true,
                'name' => 'providersmanagement.store',
            ],
            [
                'uri' => 'shopping_area/providers/edit/{id}',
                'method' => 'get',
                'action' => [ProviderController::class, 'edit'],
                'permission' => true,
                'name' => 'providersmanagement.edit',
            ],
            [
                'uri' => 'shopping_area/providers/update/{id}',
                'method' => 'post',
                'action' => [ProviderController::class, 'update'],
                'permission' => true,
                'name' => 'providersmanagement.update',
            ],
            [
                'uri' => 'shopping_area/providers/destroy/{id}',
                'method' => 'delete',
                'action' => [ProviderController::class, 'destroy'],
                'permission' => true,
                'name' => 'providersmanagement.destroy',
            ],
            [
                'uri' => 'shopping_area/providers/segment/list/{category_id?}',
                'method' => 'get',
                'action' => [ProviderController::class, 'segment_list'],
                'permission' => true,
                'name' => 'provider.segments.list',
            ],
        ];
    }
}
