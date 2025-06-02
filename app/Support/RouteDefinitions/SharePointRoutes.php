<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Services\SharePointController;

class SharePointRoutes
{
    public static function all(): array
    {
        return [
            // Rutas Cicsa Process
            [
                'uri' => 'sharepoint/index_cost_line',
                'method' => 'get',
                'action' => [SharePointController::class, 'index'],
                'permission' => true,
                'name' => 'sharepoint.index',
            ],
        ];
    }
}
