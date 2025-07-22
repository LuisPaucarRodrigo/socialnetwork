<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\Pext\PextExpensesController;

class ProjectExpensesRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => '/projectPext/additionalOrFixed/expenses_test/{project_id}/index/{fixedOrAdditional}/{type}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'additional_expense_index'],
                'permission' => true,
                'name' => 'pext.additional.expense.index',
            ],
            [
                'uri' => 'projectPext/expenses/expenseValidate/{expense_id}',
                'method' => 'put',
                'action' => [PextExpensesController::class, 'expense_validate'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.validate',
            ],
            [
                'uri' => '/projectPext/expenses/showImage/{expense_id}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'expense_show_image'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.image.show',
            ],
            [
                'uri' => '/project/project_additionals/expenses/{project_id}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'additional_project_expensese'],
                'permission' => true,
                'name' => 'projectmanagement.projectadditional.expenses',
            ],

            [
                'uri' => '/projectPext/additionalOrFixed/expenses/general/{fixedOrAdditional}/index/{type}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'additional_expense_index_general'],
                'permission' => true,
                'name' => 'pext.additional.expense.general.index',
            ],

            [
                'uri' => '/project/getExpenses/{fixedOrAdditional}/general/{type}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'getExpenses'],
                'permission' => true,
                'name' => 'getExpenses.general',
            ],

            [
                'uri' => '/projectPext/additionalOrFixed/getCicsaAssignation/search_zone',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'getCicsaAssignation'],
                'permission' => true,
                'name' => 'pext.additional.expense.general.getCicsaAssignation',
            ],

            [
                'uri' => '/projectPext/additionalOrFixed/expense/search/{project_id}',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'search_advance_monthly_or_additional_expense'],
                'permission' => true,
                'name' => 'pext.monthly.additional.expense.search_advance',
            ],
            [
                'uri' => '/projectPext/additionalOrFixed/expense/general/search',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'search_advance_additional_expense_general'],
                'permission' => true,
                'name' => 'pext.monthly.additional.expense.general.search_advance',
            ],
            [
                'uri' => '/projectPext/massive_update',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'masiveUpdate'],
                'permission' => true,
                'name' => 'projectmanagement.pext.massiveUpdate',
            ],
            [
                'uri' => '/projectPext/massive_update_swap',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'masiveUpdateSwap'],
                'permission' => true,
                'name' => 'projectmanagement.pext.massiveUpdate.swap',
            ],

            [
                'uri' => '/projectPext/expense_dashboard/{project_id}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'expense_dashboard'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expense_dashboard',
            ],
            [
                'uri' => '/projectPext/expense_type_zone',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'expense_type_zone'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenset_type_zone',
            ],
            [
                'uri' => '/projectPext/expense_dashboard_bar/{project_id}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'barChart'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expense_dashboard_bar',
            ],
            [
                'uri' => '/projectPext/import_expenses',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'import_excel_expenses'],
                'permission' => true,
                'name' => 'projectmanagement.pext.import.expenses',
            ],

            [
                'uri' => 'projectPext/expenses/storeOrUpdate/{expense_id?}',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'expenses_storeOrUpdate'],
                'permission' => true,
                'name' => 'pext.expenses.storeOrUpdate',
            ],
            [
                'uri' => 'projectPext/expenses/delete/{expense_id}',
                'method' => 'delete',
                'action' => [PextExpensesController::class, 'expenses_delete'],
                'permission' => true,
                'name' => 'pext.expenses.delete',
            ],

            [
                'uri' => 'projectPext/expenses/downloadImages/{project_id}/fixedOrAdditional/{fixedOrAdditional}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'downloadImages'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.download.zip',
            ],
            [
                'uri' => '/projectPext/expenses/{project_id}/export/{fixedOrAdditional}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'expense_export'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.export',
            ],
            [
                'uri' => '/projectPext/expenses/export/general/{fixedOrAdditional}/cost_line/{cost_line}',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'expense_export_general'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.general.export',
            ],

            [
                'uri' => '/projectPext/expenses/structure/excel_export',
                'method' => 'get',
                'action' => [PextExpensesController::class, 'structure_excel_export'],
                'permission' => true,
                'name' => 'projectmanagement.pext.structure.excel.export',
            ],
            [
                'uri' => '/project/additional_to_addproject_massive_swap/',
                'method' => 'post',
                'action' => [PextExpensesController::class, 'swap_additionalToAdditional'],
                'permission' => true,
                'name' => 'projectmanagement.additionalToAdditional.swapCosts',
            ],
        ];
    }
}
