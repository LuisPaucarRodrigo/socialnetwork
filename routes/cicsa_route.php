<?php

use App\Http\Controllers\Cicsa\CicsaController;
use Illuminate\Support\Facades\Route;


Route::get('/cicsa_process', [CicsaController::class, 'index'])->name('cicsa.index');
Route::any('/cicsa_approve', [CicsaController::class, 'chargeCicsa'])->name('cicsa.charge');

Route::any('/cicsa_assignation/{searchCondition?}', [CicsaController::class, 'indexAssignation'])->name('assignation.index');
Route::delete('/cicsa_assignation/destroy/{ca_id}', [CicsaController::class, 'destroy'])->name('cicsa.assignation.destroy');
Route::put('/cicsa_assignation/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreAssignation'])->name('assignation.storeOrUpdate');
Route::get('/cicsa_assignation/export', [CicsaController::class, 'exportAssignation'])->name('assignation.export');


Route::any('/cicsa_feasibilities/{searchCondition?}', [CicsaController::class, 'indexFeasibilities'])->name('feasibilities.index');
Route::put('/cicsa_feasibilities/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreFeasibilities'])->name('feasibilities.storeOrUpdate');
Route::get('/cicsa_feasibilities/export', [CicsaController::class, 'exportFeasibilities'])->name('feasibilities.export');



Route::any('/cicsa_material/{searchCondition?}', [CicsaController::class, 'indexMaterial'])->name('material.index');
Route::post('/cicsa_material/store', [CicsaController::class, 'storeMaterial'])->name('material.store');
Route::put('/cicsa_material/update/{cicsa_material_id}', [CicsaController::class, 'updateMaterial'])->name('material.update');
Route::post('/cicsa_material/search/material', [CicsaController::class, 'searchMaterial'])->name('material.search.material');
Route::get('/cicsa_material/export', [CicsaController::class, 'exportMaterial'])->name('material.export');


Route::post('/cicsa_material/importmaterial', [CicsaController::class, 'importMaterial'])->name('material.import');

Route::any('/cicsa_purchase_order', [CicsaController::class, 'indexPurchaseOrder'])->name('purchase.order.index');
Route::post('/cicsa_purchase_order/store/update/{cicsa_purchase_order_id?}', [CicsaController::class, 'updateOrStorePurchaseOrder'])->name('purchaseOrder.storeOrUpdate');
Route::get('/cicsa_purchase_order/showDocument/{purchaseOrder}', [CicsaController::class, 'showDocument'])->name('purchase.order.showDocument');
Route::get('/cicsa_purchase_order/export', [CicsaController::class, 'exportPurchaseOrder'])->name('purchase.order.export');




Route::any('/cicsa_installation/{searchCondition?}', [CicsaController::class, 'indexInstallation'])->name('cicsa.installation.index');
Route::post('/cicsa_installation/store/{ci_id?}', [CicsaController::class, 'updateOrStoreInstallation'])->name('cicsa.installation.store');
Route::get('/cicsa_installation/export', [CicsaController::class, 'exportInstallation'])->name('cicsa.installation.export');

// CicsaPurchaseOrderValidations

Route::any('/cicsa_purchase_order_validation', [CicsaController::class, 'indexOCValidation'])->name('cicsa.purchase_orders.validation');
Route::put('/cicsa_purchase_order_validation/store/{cicsa_validation_order_id}', [CicsaController::class, 'storeOrUpdateOCValidation'])->name('cicsa.purchase_orders.validation.update');
Route::get('/cicsa_purchase_order_validation/export', [CicsaController::class, 'exportOCValidation'])->name('cicsa.purchase_orders.validation.export');

// Route::post('/cicsa_purchase_order_validation/store/{cicsa_validation_id}', [CicsaController::class, 'updateOCValidation'])->name('cicsa.purchase_orders.validation.update');

// CicsaServiceOrders

Route::any('/cicsa_service_orders', [CicsaController::class, 'indexServiceOrder'])->name('cicsa.service_orders');
Route::post('/cicsa_service_orders/update/{cicsa_service_order_id}', [CicsaController::class, 'updateServiceOrder'])->name('cicsa.service_orders.update');
Route::get('/cicsa_service_orders/showDocument/OS/{serviceOrder}/Fac/{doc}', [CicsaController::class, 'showServiceDocument'])->name('cicsa.service_orders.showDocument');
Route::get('/cicsa_service_orders/export', [CicsaController::class, 'exportServiceOrder'])->name('cicsa.service_orders.export');

// CicsaChargeArea

Route::any('/cicsa_charge_areas', [CicsaController::class, 'indexChargeArea'])->name('cicsa.charge_areas');
Route::post('/cicsa_charge_areas/update/{cicsa_charge_area_id}', [CicsaController::class, 'updateChargeArea'])->name('cicsa.charge_areas.update');
Route::get('/cicsa_charge_areas/showDocument/{chargeAreaOrder}', [CicsaController::class, 'showChargeAreaDocument'])->name('cicsa.charge_areas.showDocument');
Route::get('/cicsa_charge_areas/export', [CicsaController::class, 'exportChargeArea'])->name('cicsa.charge_areas.export');

// CicsaChargeAreaAccepted
Route::any('/cicsa_charge_areas/accepted', [CicsaController::class, 'getChargeAreaAccepted'])->name('cicsa.charge_areas.accepted');


//search

Route::post('/cicsa_advance_search', [CicsaController::class, 'search'])->name('cicsa.advance.search');

Route::get('/cicsa_export_materials_summary/{ca_id}', [CicsaController::class, 'exportMaterialsSummary'])->name('cicsa.export.materials.summary');
