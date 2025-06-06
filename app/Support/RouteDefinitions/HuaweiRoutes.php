<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Huawei\HuaweiProjectController;

class HuaweiRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'huawei/projects/show/{status}/{prefix}/get',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'show'],
                'permission' => true,
                'name' => 'huawei.projects',
            ],
            [
                'uri' => 'huawei/projects/search/{status}/{prefix}/get/{request}',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'searchProject'],
                'permission' => true,
                'name' => 'huawei.projects.search',
            ],
            [
                'uri' => 'huawei/projects/create',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'create'],
                'permission' => true,
                'name' => 'huawei.projects.create',
            ],
            [
                'uri' => 'huawei/projects/store',
                'method' => 'post',
                'action' => [HuaweiProjectController::class, 'store'],
                'permission' => true,
                'name' => 'huawei.projects.store',
            ],
            [
                'uri' => 'huawei/projects/toUpdate/{huawei_project}',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'toUpdate'],
                'permission' => true,
                'name' => 'huawei.projects.toupdate',
            ],
            [
                'uri' => 'huawei/projects/update/{huawei_project}',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'update'],
                'permission' => true,
                'name' => 'huawei.projects.update',
            ],
            [
                'uri' => 'huawei/projects/import_base_lines/{zone}',
                'method' => 'post',
                'action' => [HuaweiProjectController::class, 'importBaseLines'],
                'permission' => true,
                'name' => 'huawei.projects.import.baselines',
            ],
            [
                'uri' => 'huawei/projects/base_lines/donwload_template/get',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'downloadTemplate'],
                'permission' => true,
                'name' => 'huawei.projects.baselines.template',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/liquidate/put',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'liquidateProject'],
                'permission' => true,
                'name' => 'huawei.projects.liquidateproject',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/cancel/put',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'cancelProject'],
                'permission' => true,
                'name' => 'huawei.projects.cancelproject',
            ],
            [
                'uri' => 'huawei/projects/stopped/{huawei_project}/put',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'resumeProject'],
                'permission' => true,
                'name' => 'huawei.projects.stopped.resume',
            ],
            [
                'uri' => 'huawei/projects/balance/{huawei_project}/get',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'projectBalance'],
                'permission' => true,
                'name' => 'huawei.projects.balance',
            ],
        ];


    }
}