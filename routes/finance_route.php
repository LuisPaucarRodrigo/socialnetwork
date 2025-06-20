<?php

use App\Http\Controllers\Finance\AccountStatementController;
use Illuminate\Support\Facades\Route;
use App\Support\RouteDefinitions\FinanceRoutes;

foreach (FinanceRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}


Route::get('/finance/account_statement_excel_export', [AccountStatementController::class, 'excel_export'])->name('finance.account_statement.excel.export');
Route::get('/management_employees/spreadsheets_export/bank_Table/month_year/{date}', [AccountStatementController::class, 'export_bank_table'])->name('spreadsheets.export.bank.table');
