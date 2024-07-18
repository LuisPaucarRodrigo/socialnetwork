<?php

use App\Http\Controllers\Cicsa\CicsaController;
use Illuminate\Support\Facades\Route;


Route::get('/cicsa_process', [CicsaController::class, 'index'])->name('cicsa.index');

Route::get('/cicsa_assignation', [CicsaController::class, 'indexAssignation'])->name('assignation.index');
Route::delete('/cicsa_assignation/destroy/{ca_id}', [CicsaController::class, 'destroy'])->name('cicsa.assignation.destroy');
Route::put('/cicsa_assignation/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreAssignation'])->name('assignation.storeOrUpdate');

Route::get('/cicsa_feasibilities', [CicsaController::class, 'indexFeasibilities'])->name('feasibilities.index');
Route::put('/cicsa_feasibilities/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreFeasibilities'])->name('feasibilities.storeOrUpdate');


Route::get('/cicsa_material', [CicsaController::class, 'indexMaterial'])->name('material.index');
Route::post('/cicsa_material/store', [CicsaController::class, 'storeMaterial'])->name('material.store');
Route::put('/cicsa_material/update/{cicsa_assignation_id}', [CicsaController::class, 'updateMaterial'])->name('material.update');

Route::post('/cicsa_material/importmaterial', [CicsaController::class, 'importMaterial'])->name('material.import');

Route::get('/cicsa_purchase_order', [CicsaController::class, 'indexPurchaseOrder'])->name('purchase.order.index');
Route::put('/cicsa_purchase_order/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStorePurchaseOrder'])->name('purchaseOrder.storeOrUpdate');




Route::get('/cicsa_installation', [CicsaController::class, 'indexInstallation'])->name('cicsa.installation.index');
Route::post('/cicsa_installation/store/{ci_id?}', [CicsaController::class, 'updateOrStoreInstallation'])->name('cicsa.installation.store');
// CicsaPurchaseOrderValidations

Route::get('/cicsa_purchase_order_validation', [CicsaController::class, 'indexOCValidation'])->name('cicsa.purchase_orders.validation');
Route::post('/cicsa_purchase_order_validation/store/{cicsa_assignation_id?}', [CicsaController::class, 'storeOCValidation'])->name('cicsa.purchase_orders.validation.store');
Route::post('/cicsa_purchase_order_validation/store/{cicsa_validation_id}', [CicsaController::class, 'updateOCValidation'])->name('cicsa.purchase_orders.validation.update');

// CicsaServiceOrders

Route::get('/cicsa_service_orders', [CicsaController::class, 'indexServiceOrder'])->name('cicsa.service_orders');
Route::post('/cicsa_service_orders/store/{cicsa_assignation_id?}', [CicsaController::class, 'storeServiceOrder'])->name('cicsa.service_orders.store');
Route::post('/cicsa_service_orders/update/{cicsa_service_order_id}', [CicsaController::class, 'updateServiceOrder'])->name('cicsa.service_orders.update');

// CicsaChargeArea

Route::get('/cicsa_charge_areas', [CicsaController::class, 'indexChargeArea'])->name('cicsa.charge_areas');
Route::post('/cicsa_charge_areas/store/{cicsa_assignation_id?}', [CicsaController::class, 'storeChargeArea'])->name('cicsa.charge_areas.store');
Route::post('/cicsa_charge_areas/update/{cicsa_charge_area}', [CicsaController::class, 'updateChargeArea'])->name('cicsa.charge_areas.update');


