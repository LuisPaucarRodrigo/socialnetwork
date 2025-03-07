<?php

use App\Constants\RolesConstants;
use App\Http\Controllers\CarManagement\CarsController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:'.implode('|', RolesConstants::CAR_MODULE))->group(function () {
    Route::get('/fleet_cars/index', [CarsController::class, 'index'])->name('fleet.cars.index');
    Route::post('/fleet_cars/search', [CarsController::class, 'search'])->name('fleet.cars.search');
    
    Route::post('/fleet_cars/store', [CarsController::class, 'store'])->name('fleet.cars.store');
    Route::post('/fleet_cars/update/{car}', [CarsController::class, 'update'])->name('fleet.cars.update');
    Route::get('/fleet_cars/show_image/{car}', [CarsController::class, 'showImage'])->name('fleet.cars.show.image');
    Route::delete('/fleet_cars/destroy/{car}', [CarsController::class, 'destroy'])->name('fleet.cars.destroy');

    Route::get('/fleet_cars/show_documents/{car_document}/document_name/{fieldName}', [CarsController::class, 'showDocuments'])->name('fleet.cars.show_documents');
    Route::post('/fleet_cars/store_document', [CarsController::class, 'storeDocument'])->name('fleet.cars.store_document');
    Route::post('/fleet_cars/update_document/{car_document}', [CarsController::class, 'updateDocument'])->name('fleet.cars.update.document');
    Route::delete('/fleet_cars/destroy_document/{car_document}', [CarsController::class, 'destroyDocument'])->name('fleet.cars.destroy_document');

    Route::get('/fleet_cars/approvel_car_document/index', [CarsController::class, 'indexApprovelCarDocument'])->name('fleet.cars.index.approvel');
    Route::get('/fleet_cars/approvel_car_document/approve/changes/{id}', [CarsController::class, 'approveChanges'])->name('fleet.cars.approve.change');
    Route::get('/fleet_cars/approvel_car_document/rejected/changes/{id}', [CarsController::class, 'deleteChanges'])->name('fleet.cars.rejected.change');

    Route::post('/fleet_cars/store_changelog/{car}', [CarsController::class, 'storeChangelog'])->name('fleet.cars.store_changelog');
    Route::post('/fleet_cars/update_changelog/{car_changelog}', [CarsController::class, 'updateChangelog'])->name('fleet.cars.update_changelog');
    Route::delete('/fleet_cars/destroy_changelog/{car_changelog}', [CarsController::class, 'destroyChangelog'])->name('fleet.cars.destroy_changelog');
    Route::get('/fleet_cars/show_invoice/{car_changelog}', [CarsController::class, 'showChangelogInvoice'])->name('fleet.cars.show_invoice');

    Route::get('/fleet_cars/show_checklist/{car}', [CarsController::class, 'showChecklist'])->name('fleet.cars.show_checklist');
    Route::get('/fleet_cars/show_checklist/send_images/{checklist}', [CarsController::class, 'sendChecklistImages'])->name('fleet.cars.show_checklist.send_images');

    Route::get('/fleet_cars/expiration/alarms',[CarsController::class,'alarms'])->name('fleet.cars.alarms');
    Route::get('/fleet_cars/specific_expiration/alarms/{car_id}',[CarsController::class,'specificAlarm'])->name('fleet.cars.specific.alarms');
    // Route::get('/fleet_cars/approve/alarms',[CarsController::class,'approveAlarms'])->name('fleet.cars.approveAlarms');
    Route::put('/fleet_cars/show_checklist/accept_or_decline/{changelog}/{is_accepted}', [CarsController::class, 'acceptOrDecline'])->name('fleet.cars.show_checklist.accept_or_decline');
});
