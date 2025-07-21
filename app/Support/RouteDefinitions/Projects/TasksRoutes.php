<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\TaskManagementController;

class TasksRoutes
{
    public static function all(): array
    {
        return [
            // Tareas del proyecto
            [
                'uri' => '/project/task/create/{project_id?}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'create'],
                'permission' => true,
                'name' => 'tasks.create',
            ],
            [
                'uri' => '/projecmanagement/task/store',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'store'],
                'permission' => true,
                'name' => 'tasks.store',
            ],
            [
                'uri' => '/projecmanagement/task/add/comment',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'comment'],
                'permission' => true,
                'name' => 'tasks.add.comment',
            ],
            [
                'uri' => '/projecmanagement/task/add/employee',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'add_employee'],
                'permission' => true,
                'name' => 'tasks.add.employee',
            ],
            [
                'uri' => '/statustask/{taskId}/{status}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'status_task'],
                'permission' => true,
                'name' => 'tasks.edit.status',
            ],
            [
                'uri' => '/tasks/duplicated',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'task_duplicated'],
                'permission' => true,
                'name' => 'tasks.duplicated',
            ],
            [
                'uri' => '/tasks/edit/date',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'task_edit_date'],
                'permission' => true,
                'name' => 'tasks.edit.date',
            ],
            [
                'uri' => '/tasks/{id?}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'index'],
                'permission' => true,
                'name' => 'tasks.index',
            ],
            [
                'uri' => '/projecmanagement/task/edit/{taskId}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'edit'],
                'permission' => true,
                'name' => 'tasks.show',
            ],
            [
                'uri' => 'edittask/delete',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'delete_employee'],
                'permission' => true,
                'name' => 'tasks.delete.employee',
            ],
            [
                'uri' => 'deletetask/{taskId}',
                'method' => 'delete',
                'action' => [TaskManagementController::class, 'delete_task'],
                'permission' => true,
                'name' => 'tasks.delete',
            ],
        ];
    }
}
