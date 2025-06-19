<?php

use App\Http\Controllers\HumanResource\DocumentController;
use App\Http\Controllers\HumanResource\DocumentSpreedSheetController;
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

//NUEVAS RUTAS
Route::post('/documents/filter_document/post', [DocumentController::class, 'documentReport'])->name('documents.filter_document');
Route::post('/document_sections/subdivisions/drag_and_drop', [DocumentController::class, 'dragandrop'])->name('documents.drag_and_drop');
Route::post('/documents/filter_document/massive_zip/post', [DocumentController::class, 'massiveZip'])->name('documents.filter_document.massive_zip');

Route::get('/management_employees/spreadsheets_details/{payroll_details_id}/employee/{employee_id}', [SpreadsheetsController::class, 'index_payroll_detail'])->name('spreadsheets.details.index');

