<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Finance\AccountStatementController;
use App\Http\Controllers\Finance\BudgetUpdateController;
use App\Http\Controllers\Finance\CostLineController;
use App\Http\Controllers\Finance\DepositController;
use App\Http\Controllers\Finance\ExpenseManagementController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\SunatController;

class FinanceRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'initialBudget/{project}/createUpdate',
                'method' => 'post',
                'action' => [BudgetUpdateController::class, 'store'],
                'permission' => false,
                'name' => 'budgetupdates.store',
            ],
            [
                'uri' => 'initialBudget/{project}/define',
                'method' => 'put',
                'action' => [BudgetUpdateController::class, 'define_initial_budget'],
                'permission' => false,
                'name' => 'initialbudget.define',
            ],
            [
                'uri' => 'finance/expencemanagement/generate_payment/{id}',
                'method' => 'get',
                'action' => [ExpenseManagementController::class, 'generate_payment'],
                'permission' => false,
                'name' => 'managementexpense.payment',
            ],
            [
                'uri' => 'finance/expencemanagement/reviewed/{id}',
                'method' => 'put',
                'action' => [ExpenseManagementController::class, 'reviewed'],
                'permission' => false,
                'name' => 'managementexpense.reviewed',
            ],
            [
                'uri' => 'finance/payment/pay',
                'method' => 'post',
                'action' => [PaymentController::class, 'payment_pay'],
                'permission' => false,
                'name' => 'payment.pay',
            ],
            [
                'uri' => 'finance/account_statement',
                'method' => 'get',
                'action' => [AccountStatementController::class, 'index'],
                'permission' => false,
                'name' => 'finance.account_statement',
            ],
            [
                'uri' => 'finance/account_statement/search_costs',
                'method' => 'get',
                'action' => [AccountStatementController::class, 'searchCosts'],
                'permission' => false,
                'name' => 'finance.search_costs',
            ],
            [
                'uri' => 'finance/account_statement/store/{as_id?}',
                'method' => 'post',
                'action' => [AccountStatementController::class, 'store'],
                'permission' => false,
                'name' => 'finance.account_statement.store',
            ],
            [
                'uri' => 'finance/account_statement_search',
                'method' => 'get',
                'action' => [AccountStatementController::class, 'searchStatements'],
                'permission' => false,
                'name' => 'finance.account_statement.search',
            ],
            [
                'uri' => 'finance/account_statement_delete/{as_id}',
                'method' => 'post',
                'action' => [AccountStatementController::class, 'destroy'],
                'permission' => false,
                'name' => 'finance.account_statement.delete',
            ],
            [
                'uri' => 'finance/account_statement_import',
                'method' => 'post',
                'action' => [AccountStatementController::class, 'importExcel'],
                'permission' => false,
                'name' => 'finance.account_statement.import',
            ],
            [
                'uri' => 'finance/account_statement_costs/{as_id}',
                'method' => 'get',
                'action' => [AccountStatementController::class, 'searchStatementsCosts'],
                'permission' => false,
                'name' => 'finance.account_statement.costs',
            ],
            [
                'uri' => 'finance/account_statement_massive_delete',
                'method' => 'post',
                'action' => [AccountStatementController::class, 'masiveDestroy'],
                'permission' => false,
                'name' => 'finance.account_statement.masivedelete',
            ],
            [
                'uri' => 'finance/account_statement_ds_download',
                'method' => 'get',
                'action' => [AccountStatementController::class, 'dowloadDataStructure'],
                'permission' => false,
                'name' => 'finance.account_statement.dsdownload',
            ],
            [
                'uri' => 'previousbalance/{month?}/{all?}',
                'method' => 'get',
                'action' => [AccountStatementController::class, 'allfine'],
                'permission' => false,
                'name' => 'finance.previous_balance',
            ],
            //Sunat
            [
                'uri' => 'finance/sunnat_allexpenses',
                'method' => 'get',
                'action' => [SunatController::class, 'general_expenses'],
                'permission' => false,
                'name' => 'finance.sunata.gexpenses',
            ],
            //CostLine and CostCenter
            [
                'uri' => 'cost_lines/store/{cl_id?}',
                'method' => 'post',
                'action' => [CostLineController::class, 'cost_line_store'],
                'permission' => false,
                'name' => 'finance.cost_line.store',
            ],
            [
                'uri' => 'cost_lines/destroy/{cl_id?}',
                'method' => 'delete',
                'action' => [CostLineController::class, 'cost_line_destroy'],
                'permission' => false,
                'name' => 'finance.cost_line.destroy',
            ],
            [
                'uri' => 'cost_lines/employees/{cl_id}',
                'method' => 'get',
                'action' => [CostLineController::class, 'cost_line_employee'],
                'permission' => false,
                'name' => 'finance.cost_line.employees',
            ],
            [
                'uri' => 'cost_lines/employees_store',
                'method' => 'post',
                'action' => [CostLineController::class, 'cost_line_employee_store'],
                'permission' => false,
                'name' => 'finance.cost_line.employee.store',
            ],
            [
                'uri' => 'cost_lines/employees/destroy/{emp_id}',
                'method' => 'delete',
                'action' => [CostLineController::class, 'cost_line_employee_destroy'],
                'permission' => false,
                'name' => 'finance.cost_line.employee.destroy',
            ],
            [
                'uri' => 'cost_lines/employees_search/{cl_id?}',
                'method' => 'get',
                'action' => [CostLineController::class, 'searchCostLineEmployees'],
                'permission' => false,
                'name' => 'finance.cost_line.employee.search',
            ],
            [
                'uri' => 'cost_center/index/{cl_id}',
                'method' => 'get',
                'action' => [CostLineController::class, 'cost_centers_index'],
                'permission' => false,
                'name' => 'finance.cost_centers.index',
            ],
            [
                'uri' => 'cost_center/store/{cc_id?}',
                'method' => 'post',
                'action' => [CostLineController::class, 'cost_center_store'],
                'permission' => false,
                'name' => 'finance.cost_center.store',
            ],
            [
                'uri' => 'cost_center/destroy/{cc_id?}',
                'method' => 'delete',
                'action' => [CostLineController::class, 'cost_center_destroy'],
                'permission' => false,
                'name' => 'finance.cost_center.destroy',
            ],
            [
                'uri' => 'cost_center/employees_store',
                'method' => 'post',
                'action' => [CostLineController::class, 'cost_centers_employee_store'],
                'permission' => false,
                'name' => 'finance.cost_center.employee.store',
            ],
            //Presupuestos
            [
                'uri' => 'selectProject',
                'method' => 'get',
                'action' => [BudgetUpdateController::class, 'selectProject'],
                'permission' => false,
                'name' => 'selectproject.index',
            ],
            [
                'uri' => 'initialBudget/{project}',
                'method' => 'get',
                'action' => [BudgetUpdateController::class, 'initial'],
                'permission' => false,
                'name' => 'initialbudget.index',
            ],
            //Aprobación de compras
            [
                'uri' => 'finance/expencemanagement/{boolean?}',
                'method' => 'any',
                'action' => [ExpenseManagementController::class, 'index'],
                'permission' => false,
                'name' => 'managementexpense.index',
            ],
            [
                'uri' => 'finance/expencemanagement/details/{purchase_quote}',
                'method' => 'get',
                'action' => [ExpenseManagementController::class, 'details'],
                'permission' => false,
                'name' => 'managementexpense.details',
            ],
            [
                'uri' => 'finance/quotes/alarm',
                'method' => 'get',
                'action' => [ExpenseManagementController::class, 'approve_quote_alarm'],
                'permission' => false,
                'name' => 'approve_quote.alarm',
            ],
            //Pagos OC
            [
                'uri' => 'finance/payment',
                'method' => 'get',
                'action' => [PaymentController::class, 'index'],
                'permission' => false,
                'name' => 'payment.index',
            ],
            [
                'uri' => 'finance/payment/search/{request}',
                'method' => 'get',
                'action' => [PaymentController::class, 'search'],
                'permission' => false,
                'name' => 'payment.search',
            ],
            [
                'uri' => 'finance/alarm/payment',
                'method' => 'get',
                'action' => [PaymentController::class, 'alarm_payments'],
                'permission' => false,
                'name' => 'payment.alarm',
            ],
            //Depósitos
            [
                'uri' => 'finance/desposits',
                'method' => 'get',
                'action' => [DepositController::class, 'deposits_index'],
                'permission' => false,
                'name' => 'deposits.index',
            ],
            [
                'uri' => 'finance/desposits/{deposit_id?}',
                'method' => 'post',
                'action' => [DepositController::class, 'deposits_store'],
                'permission' => false,
                'name' => 'deposits.store',
            ],
            [
                'uri' => 'finance/desposits/generateSummary/post',
                'method' => 'post',
                'action' => [DepositController::class, 'generateSummary'],
                'permission' => false,
                'name' => 'deposits.generateSummary',
            ],
        ];
    }
}
