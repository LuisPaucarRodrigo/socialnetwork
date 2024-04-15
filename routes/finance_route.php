<?php

use App\Http\Controllers\Finance\BudgetUpdateController;
use App\Http\Controllers\Finance\DepositController;
use App\Http\Controllers\Finance\ExpenseManagementController;
use App\Http\Controllers\Finance\GangExpenseController;
use Illuminate\Support\Facades\Route;

    //Expense
    Route::any('/finance/expensegang', [GangExpenseController::class, 'index'])->name('gangexpense.index');
    Route::get('/finance/expensegang/create', [GangExpenseController::class, 'create'])->name('gangexpense.create');
    Route::post('/finance/expensegang/store', [GangExpenseController::class, 'store'])->name('gangexpense.store');
    Route::get('/finance/expensegang/{id}/edit', [GangExpenseController::class, 'edit'])->name('gangexpense.edit');

    Route::any('/finance/expencemanagement/{boolean?}', [ExpenseManagementController::class, 'index'])->name('managementexpense.index');
    Route::get('/finance/expencemanagement/details/{purchase_quote}', [ExpenseManagementController::class, 'details'])->name('managementexpense.details');

    Route::put('/finance/expencemanagement/reviewed/{id}', [ExpenseManagementController::class, 'reviewed'])->name('managementexpense.reviewed');
    Route::get('/finance/expencemanagement/generate_payment/{id}', [ExpenseManagementController::class, 'generate_payment'])->name('managementexpense.payment');
    Route::get('/finance/purchase_quotes/doTask', [ExpenseManagementController::class, 'doTask'])->name('finance.task');
    //Budget
    Route::get('/budgetUpdates/{project}', [BudgetUpdateController::class, 'index'])->name('budgetupdates.index');
    Route::get('/selectProject', [BudgetUpdateController::class, 'selectProject'])->name('selectproject.index');
    Route::get('/budgetUpdates/{project}/{budgetupdate}', [BudgetUpdateController::class, 'show'])->name('budgetupdates.show');
    Route::get('/initialBudget/{project}', [BudgetUpdateController::class, 'initial'])->name('initialbudget.index');
    Route::post('/initialBudget/{project}/createUpdate', [BudgetUpdateController::class, 'create'])->name('budgetupdates.create');
    Route::put('/initialBudget/{project}/define', [BudgetUpdateController::class, 'define_initial_budget'])->name('initialbudget.define');

    Route::get('/finance/desposits', [DepositController::class, 'deposits_index'])->name('deposits.index');
    Route::post('/finance/desposits/{deposit_id?}', [DepositController::class, 'deposits_store'])->name('deposits.store');
    //
    Route::post('/finance/desposits/generateSummary/post', [DepositController::class, 'generateSummary'])->name('deposits.generateSummary');