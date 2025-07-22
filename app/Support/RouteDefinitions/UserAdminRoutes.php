<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ManagementRolsController;
use App\Http\Controllers\Auth\PasswordController;


class UserAdminRoutes
{
    public static function all(): array
    {
        return [
            // Users
            [
                'uri' => 'users/linkEmployee/{user}',
                'method' => 'get',
                'action' => [UserController::class, 'linkEmployee'],
                'permission' => true,
                'name' => 'users.linkEmployee',
            ],
            [
                'uri' => 'users/edit/{id}',
                'method' => 'get',
                'action' => [UserController::class, 'edit'],
                'permission' => true,
                'name' => 'users.edit',
            ],
            [
                'uri' => 'users/update/{id}',
                'method' => 'put',
                'action' => [UserController::class, 'update'],
                'permission' => true,
                'name' => 'users.update',
            ],
            [
                'uri' => 'password/{user_id?}',
                'method' => 'put',
                'action' => [PasswordController::class, 'update'],
                'permission' => true,
                'name' => 'password.update',
            ],
            [
                'uri' => 'users/delete/{id}',
                'method' => 'post',
                'action' => [UserController::class, 'delete'],
                'permission' => true,
                'name' => 'users.destroy',
            ],
            [
                'uri' => 'users',
                'method' => 'get',
                'action' => [UserController::class, 'index_user'],
                'permission' => true,
                'name' => 'users.index',
            ],
            [
                'uri' => 'users/getUsers',
                'method' => 'get',
                'action' => [UserController::class, 'getUsers'],
                'permission' => true,
                'name' => 'getUsers',
            ],
            [
                'uri' => 'users/search',
                'method' => 'post',
                'action' => [UserController::class, 'search'],
                'permission' => true,
                'name' => 'users.search',
            ],
            [
                'uri' => 'users/details/{id}',
                'method' => 'get',
                'action' => [UserController::class, 'details'],
                'permission' => true,
                'name' => 'users.details',
            ],
            [
                'uri' => 'register',
                'method' => 'get',
                'action' => [RegisteredUserController::class, 'create'],
                'permission' => true,
                'name' => 'register',
            ],
            [
                'uri' => 'register',
                'method' => 'post',
                'action' => [RegisteredUserController::class, 'store'],
                'permission' => true,
                'name' => 'register.post',
            ],
        
            // Rols
            [
                'uri' => 'rols',
                'method' => 'get',
                'action' => [ManagementRolsController::class, 'rols_index'],
                'permission' => true,
                'name' => 'rols.index',
            ],
            [
                'uri' => 'rols/getRols',
                'method' => 'get',
                'action' => [ManagementRolsController::class, 'getRols'],
                'permission' => true,
                'name' => 'getRols',
            ],
            [
                'uri' => 'rols/store',
                'method' => 'post',
                'action' => [ManagementRolsController::class, 'store'],
                'permission' => true,
                'name' => 'rols.store',
            ],
            [
                'uri' => 'rols/update/{id}',
                'method' => 'post',
                'action' => [ManagementRolsController::class, 'update'],
                'permission' => true,
                'name' => 'rols.update',
            ],
            [
                'uri' => 'rols/delete/{id}',
                'method' => 'delete',
                'action' => [ManagementRolsController::class, 'delete'],
                'permission' => true,
                'name' => 'rols.delete',
            ],
            [
                'uri' => 'rols/details/{id}',
                'method' => 'get',
                'action' => [ManagementRolsController::class, 'details'],
                'permission' => true,
                'name' => 'rols.details',
            ],





            [
                'uri' => 'dev_permissions',
                'method' => 'get',
                'action' => [PermissionController::class, 'index'],
                'permission' => false,
                'name' => 'dev.permissions.index',
            ],





        ];
    }
}