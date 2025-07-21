<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\ChecklistsController;

class ChecklistRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => '/checklist',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'index'],
                'permission' => true,
                'name' => 'checklist.index',
            ],
            [
                'uri' => '/checklist/car',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'car_index'],
                'permission' => true,
                'name' => 'checklist.car.index',
            ],
            [
                'uri' => '/checklist/car/{id}/{photoProp}',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'car_photo'],
                'permission' => true,
                'name' => 'checklist.car.photo',
            ],
            [
                'uri' => '/checklist/toolkit/{id}/{photoProp}',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'toolkit_photo'],
                'permission' => true,
                'name' => 'checklist.toolkit.photo',
            ],
            [
                'uri' => '/checklist/dailytoolkit',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'dailytoolkit_index'],
                'permission' => true,
                'name' => 'checklist.dailytoolkit.index',
            ],
            [
                'uri' => '/checklist/epp',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'epp_index'],
                'permission' => true,
                'name' => 'checklist.epp.index',
            ],
            [
                'uri' => '/checklist/toolkit',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'toolkit_index'],
                'permission' => true,
                'name' => 'checklist.toolkit.index',
            ],
            [
                'uri' => '/checklist/car/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'car_destroy'],
                'permission' => true,
                'name' => 'checklist.car.destroy',
            ],
            [
                'uri' => '/checklist/toolkit/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'toolkit_destroy'],
                'permission' => true,
                'name' => 'checklist.toolkit.destroy',
            ],
            [
                'uri' => '/checklist/dailytoolkit/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'dailytoolkit_destroy'],
                'permission' => true,
                'name' => 'checklist.dailytoolkit.destroy',
            ],
            [
                'uri' => '/checklist/epp/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'epp_destroy'],
                'permission' => true,
                'name' => 'checklist.epp.destroy',
            ],
        ];
    }
}
