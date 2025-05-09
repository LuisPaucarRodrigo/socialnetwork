<?php

use App\Http\Controllers\Cicsa\CicsaController;
use Illuminate\Support\Facades\Route;


Route::get('/cicsa_process_index/{type}', [CicsaController::class, 'index'])->name('cicsa.index');
Route::get('/cicsa_process_export/{type}/{stages?}', [CicsaController::class, 'exportCiscaProcess'])->name('cicsa.export');

Route::delete('/cicsa_assignation_destroy/destroy/{ca_id}', [CicsaController::class, 'destroy'])->name('cicsa.assignation.destroy');


Route::any('/cicsa_assignation/{type}/index/{searchCondition?}', [CicsaController::class, 'indexAssignation'])->name('assignation.index');
Route::get('/cicsa_assignation_export/export/{type}', [CicsaController::class, 'exportAssignation'])->name('assignation.export');


Route::any('/cicsa_feasibilities_index/{type}/{searchCondition?}', [CicsaController::class, 'indexFeasibilities'])->name('feasibilities.index');
Route::put('/cicsa_feasibilities_store/store/update/{cicsa_assignation_id?}', [CicsaController::class, 'updateOrStoreFeasibilities'])->name('feasibilities.storeOrUpdate');
Route::get('/cicsa_feasibilities_export/export/{type}', [CicsaController::class, 'exportFeasibilities'])->name('feasibilities.export');



Route::any('/cicsa_material_index/{type}/{searchCondition?}', [CicsaController::class, 'indexMaterial'])->name('material.index');
Route::post('/cicsa_material_store', [CicsaController::class, 'storeMaterial'])->name('material.store');
Route::put('/cicsa_material_update/{cicsa_material_id}', [CicsaController::class, 'updateMaterial'])->name('material.update');
Route::post('/cicsa_material_search/material', [CicsaController::class, 'searchMaterial'])->name('material.search.material');
Route::get('/cicsa_material_export/{type}', [CicsaController::class, 'exportMaterial'])->name('material.export');


Route::post('/cicsa_material_importmaterial', [CicsaController::class, 'importMaterial'])->name('material.import');

Route::any('/cicsa_purchase_order_index/{type}/{searchCondition?}', [CicsaController::class, 'indexPurchaseOrder'])->name('purchase.order.index');
Route::post('/cicsa_purchase_order_store/update/{cicsa_purchase_order_id?}', [CicsaController::class, 'updateOrStorePurchaseOrder'])->name('purchaseOrder.storeOrUpdate');
Route::get('/cicsa_purchase_order_showDocument/{purchaseOrder}', [CicsaController::class, 'showDocument'])->name('purchase.order.showDocument');
Route::get('/cicsa_purchase_order_export/{type}', [CicsaController::class, 'exportPurchaseOrder'])->name('purchase.order.export');




Route::any('/cicsa_installation_index/{type}/{searchCondition?}', [CicsaController::class, 'indexInstallation'])->name('cicsa.installation.index');
Route::post('/cicsa_installation_storeOrUpdate/{ci_id?}', [CicsaController::class, 'updateOrStoreInstallation'])->name('cicsa.installation.store');
Route::get('/cicsa_installation_export/{type}', [CicsaController::class, 'exportInstallation'])->name('cicsa.installation.export');

// CicsaPurchaseOrderValidations

Route::any('/cicsa_purchase_order_validation_index/{type}/{searchCondition?}', [CicsaController::class, 'indexOCValidation'])->name('cicsa.purchase_orders.validation');
Route::put('/cicsa_purchase_order_validation_store/{cicsa_validation_order_id}', [CicsaController::class, 'storeOrUpdateOCValidation'])->name('cicsa.purchase_orders.validation.update');
Route::get('/cicsa_purchase_order_validation_export/{type}', [CicsaController::class, 'exportOCValidation'])->name('cicsa.purchase_orders.validation.export');

// Route::post('/cicsa_purchase_order_validation/store/{cicsa_validation_id}', [CicsaController::class, 'updateOCValidation'])->name('cicsa.purchase_orders.validation.update');

// CicsaServiceOrders

Route::any('/cicsa_service_orders_index/{type}/{searchCondition?}', [CicsaController::class, 'indexServiceOrder'])->name('cicsa.service_orders');
Route::post('/cicsa_service_orders_update/{cicsa_service_order_id}', [CicsaController::class, 'updateServiceOrder'])->name('cicsa.service_orders.update');
Route::get('/cicsa_service_orders_showDocument/OS/{serviceOrder}/Fac/{doc}', [CicsaController::class, 'showServiceDocument'])->name('cicsa.service_orders.showDocument');
Route::get('/cicsa_service_orders_export/{type}', [CicsaController::class, 'exportServiceOrder'])->name('cicsa.service_orders.export');

// CicsaChargeArea

Route::any('/cicsa_charge_areas_index/{type}/{searchCondition?}', [CicsaController::class, 'indexChargeArea'])->name('cicsa.charge_areas');
Route::post('/cicsa_charge_areas_update/{cicsa_charge_area_id}', [CicsaController::class, 'updateChargeArea'])->name('cicsa.charge_areas.update');
Route::get('/cicsa_charge_areas_showDocument/{chargeAreaOrder}', [CicsaController::class, 'showChargeAreaDocument'])->name('cicsa.charge_areas.showDocument');
Route::get('/cicsa_charge_areas_export/{cost_line_id}', [CicsaController::class, 'exportChargeArea'])->name('cicsa.charge_areas.export');


//search

Route::post('/cicsa_advance_search/{type}', [CicsaController::class, 'search'])->name('cicsa.advance.search');

Route::get('/cicsa_export_materials_summary/{ca_id}', [CicsaController::class, 'exportMaterialsSummary'])->name('cicsa.export.materials.summary');


//material delete
Route::delete('/cicsa_material_delete/{c_m_id}', [CicsaController::class, 'deleteMaterial'])->name('material.delete');