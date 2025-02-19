<?php

use App\Http\Controllers\Finance\AccountStatementController;
use App\Http\Controllers\Finance\BudgetUpdateController;
use App\Http\Controllers\Finance\CostLineController;
use App\Http\Controllers\Finance\DepositController;
use App\Http\Controllers\Finance\ExpenseManagementController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\SunatController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:FinanceManager')->group(function () {
    //Budget
    Route::post('/initialBudget/{project}/createUpdate', [BudgetUpdateController::class, 'store'])->name('budgetupdates.store');
    Route::put('/initialBudget/{project}/define', [BudgetUpdateController::class, 'define_initial_budget'])->name('initialbudget.define');

    //Approve purchases
    Route::get('/finance/expencemanagement/generate_payment/{id}', [ExpenseManagementController::class, 'generate_payment'])->name('managementexpense.payment');
    Route::put('/finance/expencemanagement/reviewed/{id}', [ExpenseManagementController::class, 'reviewed'])->name('managementexpense.reviewed');

    //Payment
    Route::post('/finance/payment/pay', [PaymentController::class, 'payment_pay'])->name('payment.pay');



    //Account Status
    Route::get('/finance/account_statement', [AccountStatementController::class, 'index'])->name('finance.account_statement');
    Route::get('/finance/account_statement/search_costs', [AccountStatementController::class, 'searchCosts'])->name('finance.search_costs');
    Route::post('/finance/account_statement/store/{as_id?}', [AccountStatementController::class, 'store'])->name('finance.account_statement.store');
    Route::get('/finance/account_statement_search}', [AccountStatementController::class, 'searchStatements'])->name('finance.account_statement.search');
    Route::post('/finance/account_statement_delete/{as_id}', [AccountStatementController::class, 'destroy'])->name('finance.account_statement.delete');
    Route::post('/finance/account_statement_import', [AccountStatementController::class, 'importExcel'])->name('finance.account_statement.import');
    Route::get('/finance/account_statement_costs/{as_id}', [AccountStatementController::class, 'searchStatementsCosts'])->name('finance.account_statement.costs');
    Route::post('/finance/account_statement_massive_delete', [AccountStatementController::class, 'masiveDestroy'])->name('finance.account_statement.masivedelete');
    Route::get('/finance/account_statement_ds_download', [AccountStatementController::class, 'dowloadDataStructure'])->name('finance.account_statement.dsdownload');

    ///tests routes
    // Route::get('/test_acount/{month}/{all?}', [AccountStatementController::class, 'previousBalance'])->name('finance.account_statement.testgetprincipalvar');


    //Sunata SIRE
    Route::get('/finance/sunnat_allexpenses', [SunatController::class, 'general_expenses'])->name('finance.sunata.gexpenses');


    //Costs Lines
    Route::post('/cost_lines/store/{cl_id?}', [CostLineController::class,'cost_line_store'])->name('finance.cost_line.store');
    Route::delete('/cost_lines/destroy/{cl_id?}', [CostLineController::class,'cost_line_destroy'])->name('finance.cost_line.destroy');
    Route::get('/cost_lines/employees/{cl_id}', [CostLineController::class, 'cost_line_employee'])->name('finance.cost_line.employees');
    Route::post('/cost_lines/employees_store', [CostLineController::class, 'cost_line_employee_store'])->name('finance.cost_line.employee.store');
    Route::delete('/cost_lines/employees/destroy/{emp_id}', [CostLineController::class, 'cost_line_employee_destroy'])->name('finance.cost_line.employee.destroy');
    Route::get('/cost_lines/employees_search/{cl_id?}', [CostLineController::class, 'searchCostLineEmployees'])->name('finance.cost_line.employee.search');

    //CostsCenters
    Route::get('/cost_center/index/{cl_id}', [CostLineController::class,'cost_centers_index'])->name('finance.cost_centers.index');
    Route::post('/cost_center/store/{cc_id?}', [CostLineController::class,'cost_center_store'])->name('finance.cost_center.store');
    Route::delete('/cost_center/destroy/{cc_id?}', [CostLineController::class,'cost_center_destroy'])->name('finance.cost_center.destroy');
    Route::post('/cost_center/employees_store', [CostLineController::class, 'cost_centers_employee_store'])->name('finance.cost_center.employee.store');



});

Route::middleware('permission:FinanceManager|Finance')->group(function () {
    //Budget
    Route::get('/selectProject', [BudgetUpdateController::class, 'selectProject'])->name('selectproject.index');
    Route::get('/initialBudget/{project}', [BudgetUpdateController::class, 'initial'])->name('initialbudget.index');

    //Approve purchases
    Route::any('/finance/expencemanagement/{boolean?}', [ExpenseManagementController::class, 'index'])->name('managementexpense.index');
    Route::get('/finance/expencemanagement/details/{purchase_quote}', [ExpenseManagementController::class, 'details'])->name('managementexpense.details');

    //Alarm
    Route::get('/finance/quotes/alarm', [ExpenseManagementController::class, 'approve_quote_alarm'])->name('approve_quote.alarm');

    //Payment
    Route::get('/finance/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/finance/payment/search/{request}', [PaymentController::class, 'search'])->name('payment.search');

    Route::get('/finance/alarm/payment', [PaymentController::class, 'alarm_payments'])->name('payment.alarm');
});



Route::get('/finance/desposits', [DepositController::class, 'deposits_index'])->name('deposits.index');
Route::post('/finance/desposits/{deposit_id?}', [DepositController::class, 'deposits_store'])->name('deposits.store');
//
Route::post('/finance/desposits/generateSummary/post', [DepositController::class, 'generateSummary'])->name('deposits.generateSummary');






