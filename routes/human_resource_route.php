<?php

use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\ManagementEmployees;
use App\Http\Controllers\HumanResource\SpreadsheetsController;
use Illuminate\Support\Facades\Route;
use App\Support\RouteDefinitions\HumanResourceRoutes;


foreach (HumanResourceRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}

//new payroll routes

Route::get('/management_employees/worker_data/{employee_id}', [SpreadsheetsController::class, 'index_worder_data'])->name('index.worker.data');
Route::get('/management_employees/payroll_show_pdws/{payroll_detail_id}', [SpreadsheetsController::class, 'show_payroll_detail_work_schedule'])->name('payroll.show.payroll.detail.work.schedule');
Route::post('/management_employees/payroll_store_pdws', [SpreadsheetsController::class, 'store_payroll_detail_work_schedule'])->name('payroll.store.payroll.detail.work.schedule');

Route::get('/management_employees/payroll_show_pdmi/{payroll_detail_id}', [SpreadsheetsController::class, 'show_payroll_detail_monetary_income'])->name('payroll.show.payroll.detail.monetary.income');
Route::post('/management_employees/payroll_store_pdmi', [SpreadsheetsController::class, 'store_payroll_monetary_income'])->name('payroll.store.payroll.detail.monetary.income');

Route::get('/management_employees/payroll_show_pdmd/{payroll_detail_id}', [SpreadsheetsController::class, 'show_payroll_detail_monetary_discount'])->name('payroll.show.payroll.detail.monetary.discount');
Route::post('/management_employees/payroll_store_pdmd', [SpreadsheetsController::class, 'store_payroll_monetary_discount'])->name('payroll.store.payroll.detail.monetary.discount');

Route::get('/management_employees/payroll_show_pdtac/{payroll_detail_id}', [SpreadsheetsController::class, 'show_payroll_detail_tax_and_contribution'])->name('payroll.show.payroll.detail.tax.contribution');
Route::post('/management_employees/payroll_store_pdtac', [SpreadsheetsController::class, 'store_payroll_tax_and_contribution'])->name('payroll.store.payroll.detail.tax.contribution');

Route::get('/management_employees/payroll_external_detail_index/{payroll_id}', [SpreadsheetsController::class, 'index_payroll_external_detail'])->name('payroll.index.payroll.external.detail');
Route::post('/management_employees/payroll_external_detail_store', [SpreadsheetsController::class, 'store_payroll_external_detail'])->name('payroll.store.payroll.external.detail');
Route::delete('/management_employees/payroll_external_detail_delete/{payroll_detail_id}', [SpreadsheetsController::class, 'destroy_payroll_external_detail'])->name('payroll.store.payroll.external.destroy');



Route::post('/management_employees/payroll_detail_expenses_multiple_store', [SpreadsheetsController::class, 'store_pay_spreedsheets'])->name('payroll.detail.expenses.multiple.store');
Route::get('/management_employees/payroll_detail_expense_constants_index', [SpreadsheetsController::class, 'show_payroll_detail_expense_constants'])->name('payroll.detail.expense.constants.show');


Route::get('/management_employees/payroll_detail_expense_index/{payroll_id}', [SpreadsheetsController::class, 'index_payroll_detail_expense'])->name('payroll.detail.expense.index');
Route::post('/management_employees/payroll_detail_expense_search/{payroll_id}', [SpreadsheetsController::class, 'search_payroll_detail_expenses'])->name('payroll.detail.expense.search');
Route::post('/management_employees/payroll_detail_expense_store', [SpreadsheetsController::class, 'store_payroll_detail_expense'])->name('payroll.detail.expenses.store');
Route::delete('/management_employees/payroll_detail_expense_destroy/{payroll_detail_expense_id}', [SpreadsheetsController::class, 'destroy_payroll_detail_expense'])->name('payroll.detail.expense.destroy');
Route::post('/management_employees/payroll_detail_expense_masive_opnuda_update', [SpreadsheetsController::class, 'masive_update_payroll_detail_expense'])->name('payroll.detail.expenses.massive.update.opnuda');
Route::get('/management_employees/payroll_detail_export/{payroll_id}', [SpreadsheetsController::class, 'export_excel_payroll_detail'])->name('payroll.detail.export');
Route::get('/management_employees/payroll_detail_export_details/{payroll_id}', [SpreadsheetsController::class, 'export_detail_excel_payroll_detail'])->name('payroll.detail.export.details');

//exteernal empployees
Route::delete('/management_employees/external/delete/{external_id}', [ManagementEmployees::class, 'external_delete'])->name('employees.external.delete');

//NUEVAS RUTAS
Route::post('/documents/filter_document/post', [DocumentController::class, 'documentReport'])->name('documents.filter_document');
Route::post('/document_sections/subdivisions/drag_and_drop', [DocumentController::class, 'dragandrop'])->name('documents.drag_and_drop');
Route::post('/documents/filter_document/massive_zip/post', [DocumentController::class, 'massiveZip'])->name('documents.filter_document.massive_zip');

Route::get('/management_employees/spreadsheets_details/{payroll_details_id}/employee/{employee_id}', [SpreadsheetsController::class, 'index_payroll_detail'])->name('spreadsheets.details.index');