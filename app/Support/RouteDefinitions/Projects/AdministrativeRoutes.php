<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\AdministrativeCostsController;


class AdministrativeRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => '/project/purchases_request/{month_project_id}/administrative_costs',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'store'],
                'permission' => true,
                'name' => 'projectmanagement.storeAdministrativeCost',
            ],
            [
                'uri' => '/descargar_zip_administrative/{month_project_id}',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'downloadImages'],
                'permission' => true,
                'name' => 'zip.administrative.descargar',
            ],
            [
                'uri' => '/project/administrative_costs_massive_update/',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'masiveUpdate'],
                'permission' => true,
                'name' => 'projectmanagement.administrativeCosts.massiveUpdate',
            ],
            [
                'uri' => '/project/purchases_request/{month_project_id}/administrative_costs',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'index'],
                'permission' => true,
                'name' => 'projectmanagement.administrativeCosts',
            ],
            [
                'uri' => '/administrativecosts_photo/{static_cost_id}',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'download_ac_photo'],
                'permission' => true,
                'name' => 'administrativeCosts.archive',
            ],
            [
                'uri' => '/administrativecosts_advancesearch/{month_project_id}',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'search_costs'],
                'permission' => true,
                'name' => 'administrativeCosts.advance.search',
            ],
            [
                'uri' => '/administrativecosts/excel_export/{month_project_id}',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'export'],
                'permission' => true,
                'name' => 'administrativeCosts.excel.export',
            ],
            [
                'uri' => '/projectmanagement/purchases_request/administrative_costs/{additional_cost}/update',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'update'],
                'permission' => true,
                'name' => 'projectmanagement.updateAdministrativeCost',
            ],
            [
                'uri' => '/projectmanagement/purchases_request/{month_project_id}/administrative_costs/{additional_cost}/destroy',
                'method' => 'delete',
                'action' => [AdministrativeCostsController::class, 'destroy'],
                'permission' => true,
                'name' => 'projectmanagement.deleteAdministrativeCost',
            ],
        ];
    }
}
