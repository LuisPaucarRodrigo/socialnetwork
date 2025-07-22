<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\Pext\PextController;

class PextRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'projectPext/storeProjectAndAssignation',
                'method' => 'post',
                'action' => [PextController::class, 'storeProjectAndAssignation'],
                'permission' => true,
                'name' => 'projectmanagement.pext.storeProjectAndAssignation',
            ],
            [
                'uri' => '/projectPext/index',
                'method' => 'any',
                'action' => [PextController::class, 'index'],
                'permission' => true,
                'name' => 'projectmanagement.pext.index',
            ],
            [
                'uri' => '/projectPext/projectOrPreproject',
                'method' => 'get',
                'action' => [PextController::class, 'requestProjectOrPreproject'],
                'permission' => true,
                'name' => 'projectmanagement.pext.requestProjectOrPreproject',
            ],
            [
                'uri' => '/projectPext/projectOrPreproject/historial_pext',
                'method' => 'any',
                'action' => [PextController::class, 'historial_pext'],
                'permission' => true,
                'name' => 'projectmanagement.pext.historial',
            ],

            [
                'uri' => '/projectPext/expenses/monthly/{project_id}/index/{fixedOrAdditional}/status/{status?}',
                'method' => 'get',
                'action' => [PextController::class, 'index_expenses'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.index',
            ],
            // Project Pext Additional
            [
                'uri' => '/projectPext/additional_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [PextController::class, 'index_additional'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.index',
            ],
            [
                'uri' => '/projectPext/additional_rejected/index/{type}',
                'method' => 'any',
                'action' => [PextController::class, 'index_additional_rejected'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.index_rejected',
            ],
            [
                'uri' => '/projectPext/additional_reject/{pa_id}',
                'method' => 'post',
                'action' => [PextController::class, 'rejectProjectAdditional'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.reject',
            ],

            [
                'uri' => '/projectPext/additional/store_quote/{project_quote_id?}',
                'method' => 'post',
                'action' => [PextController::class, 'store_quote'],
                'permission' => true,
                'name' => 'projectmanagement.pext.store.quote',
            ],
            [
                'uri' => '/projectPext/additional/export/quote/{project_id}',
                'method' => 'get',
                'action' => [PextController::class, 'export_quote'],
                'permission' => true,
                'name' => 'projectmanagement.pext.export.pdf.quote',
            ],
            [
                'uri' => '/projectPext/additional/store/{cicsa_assignation_id?}',
                'method' => 'post',
                'action' => [PextController::class, 'updateOrStoreAdditional'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.store',
            ],
        ];
    }
}
