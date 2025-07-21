<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\PRO\ProController;

class ProRoutes
{
    public static function all(): array
    {
        return [
            // Títulos
            [
                'uri' => '/preprojects/titles',
                'method' => 'get',
                'action' => [ProController::class, 'showTitles'],
                'permission' => true,
                'name' => 'preprojects.titles',
            ],
            [
                'uri' => '/preprojects/titles/post',
                'method' => 'post',
                'action' => [ProController::class, 'postTitle'],
                'permission' => true,
                'name' => 'preprojects.titles.post',
            ],
            [
                'uri' => '/preprojects/titles/{title}/put',
                'method' => 'put',
                'action' => [ProController::class, 'putTitle'],
                'permission' => true,
                'name' => 'preprojects.titles.put',
            ],
            [
                'uri' => '/preprojects/titles_delete/{title}/delete',
                'method' => 'delete',
                'action' => [ProController::class, 'deleteTitle'],
                'permission' => true,
                'name' => 'preprojects.titles.delete',
            ],

            //Codes
            [
                'uri' => '/preprojects/codes',
                'method' => 'get',
                'action' => [ProController::class, 'showCodes'],
                'permission' => true,
                'name' => 'preprojects.codes',
            ],
            [
                'uri' => '/preprojects/codes/post',
                'method' => 'post',
                'action' => [ProController::class, 'storeCode'],
                'permission' => true,
                'name' => 'preprojects.codes.post',
            ],
            [
                'uri' => '/preprojects/codes/{code}/put',
                'method' => 'put',
                'action' => [ProController::class, 'updateCode'],
                'permission' => true,
                'name' => 'preprojects.codes.put',
            ],
            [
                'uri' => '/preprojects/codes/{code}/delete',
                'method' => 'delete',
                'action' => [ProController::class, 'deleteCode'],
                'permission' => true,
                'name' => 'preprojects.codes.delete',
            ],
            [
                'uri' => '/preprojects/codes/{code_id}/images/show',
                'method' => 'get',
                'action' => [ProController::class, 'indexImages'],
                'permission' => true,
                'name' => 'preprojects.code.images.index',
            ],
            [
                'uri' => '/preprojects/codes/images/store',
                'method' => 'post',
                'action' => [ProController::class, 'storeCodeImages'],
                'permission' => true,
                'name' => 'preprojects.code.images.store',
            ],
            [
                'uri' => '/preprojects/codes/images/{image_id}/show',
                'method' => 'get',
                'action' => [ProController::class, 'show_code_image'],
                'permission' => true,
                'name' => 'preprojects.code.images.show',
            ],
            [
                'uri' => '/preprojects/codes/images/{image_id}/delete',
                'method' => 'delete',
                'action' => [ProController::class, 'deleteCodeImages'],
                'permission' => true,
                'name' => 'preprojects.code.images.delete',
            ],
        ];
    }
}
