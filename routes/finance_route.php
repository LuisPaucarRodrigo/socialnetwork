<?php

use App\Http\Controllers\Finance\AccountStatementController;
use App\Http\Controllers\Finance\BudgetUpdateController;
use App\Http\Controllers\Finance\DepositController;
use App\Http\Controllers\Finance\ExpenseManagementController;
use App\Http\Controllers\Finance\PaymentController;
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






