<?php

use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\PurchaseProductsController;
use App\Http\Controllers\Inventory\ResourceManagementController;
use App\Http\Controllers\Inventory\SpecialWarehouseController;
use App\Http\Controllers\Inventory\WarehousesController;
use Illuminate\Support\Facades\Route;

//Resources
Route::get('/resources', [ResourceManagementController::class, 'index'])->name('resources.index');
Route::get('/resources/new', [ResourceManagementController::class, 'new'])->name('resources.new');
Route::get('/resources/details/{id}', [ResourceManagementController::class, 'details'])->name('resources.details');
Route::post('/resources/create', [ResourceManagementController::class, 'create'])->name('resource.create');
Route::get('/resources/edit/{resourceId}', [ResourceManagementController::class, 'edit'])->name('resources.edit');
Route::put('/resources/edit/{resourceId}', [ResourceManagementController::class, 'update'])->name('resource.update');
Route::delete('/resources/delete/{resourceId}', [ResourceManagementController::class, 'destroy'])->name('resource.delete');

Route::post('resource_description/store', [ResourceManagementController::class, 'resource_description_store'])->name('resource_description.store');
//warehouses
Route::get('/inventory/warehouses', [WarehousesController::class, 'showWarehouses'])->name('warehouses.warehouses');
Route::get('/inventory/warehouses/{warehouse}', [WarehousesController::class, 'showWarehouse'])->name('warehouses.warehouse');

//products
Route::get('/inventory/products/{warehouse}', [WarehousesController::class, 'showProducts'])->name('warehouses.products');
Route::get('/inventory/products/{warehouse}/{inventory}/get', [WarehousesController::class, 'showEntries'])->name('warehouses.products.entries');
Route::get('/inventory/products/{warehouse}/create', [WarehousesController::class, 'createProducts'])->name('warehouses.createNormalProduct');
Route::post('/inventory/products/{warehouse}/store', [WarehousesController::class, 'storeProducts'])->name('warehouses.storeNormalProduct');

//approvePurchaseOrders
Route::get('/inventory/approve_purchase_orders/{warehouse}', [WarehousesController::class, 'approvePurchaseOrders'])->name('warehouses.purchaseorders.approve');
Route::post('/inventory/approve_purchase_orders/{warehouse}/approve', [WarehousesController::class, 'approve'])->name('warehouses.purchaseorders.approve.post');

//dispatches
Route::get('/inventory/dispatches/{warehouse}', [WarehousesController::class, 'showDispatches'])->name('warehouses.dispatches');
Route::get('/inventory/dispatches_approved/{warehouse}', [WarehousesController::class, 'showApprovedDispatches'])->name('warehouses.dispatches.approved');
Route::get('/inventory/dispatches_rejected/{warehouse}', [WarehousesController::class, 'showRejectedDispatches'])->name('warehouses.dispatches.rejected');
Route::post('/inventory/dispatches/{warehouse}/accept_or_decline', [WarehousesController::class, 'acceptOrDeclineDispatch'])->name('warehouses.dispatches.acceptordecline');

//outputs
Route::get('/inventory/warehouses/{warehouse}/outputs', [ProductController::class, 'outputs_index'])->name('warehouses.outputs');
Route::post('/inventory/warehouses//outputs/store', [ProductController::class, 'outputs_store'])->name('projectmanagement.outputs.store');
Route::get('/inventory/warehouses/{warehouse}/outputs_history', [ProductController::class, 'outputs_history_index'])->name('warehouses.outputs_history');
Route::delete('/inventory/warehouses/output_delete/{output}', [ProductController::class, 'output_delete'])->name('warehouses.output_delete');

//purchase_products
Route::get('/inventory/purchase_products/products', [PurchaseProductsController::class, 'index'])->name('inventory.purchaseproducts');
Route::get('/inventory/purchase_products/products/search/{request}', [PurchaseProductsController::class, 'search'])->name('inventory.purchaseproducts.search');
Route::post('/inventory/purchase_products/products/post', [PurchaseProductsController::class, 'store'])->name('inventory.purchaseproducts.store');
Route::put('/inventory/purchase_products/products/{purchase_product}/update', [PurchaseProductsController::class, 'update'])->name('inventory.purchaseproducts.update');
Route::put('/inventory/purchase_products/products/{purchase_product}/disable', [PurchaseProductsController::class, 'disable'])->name('inventory.purchaseproducts.disable');

Route::post('/inventory/purchase_products/type_product/store', [PurchaseProductsController::class, 'typeProducts'])->name('inventory.purchaseproducts.typeProduct');


//Special Warehouses
Route::get('/inventory/{warehouse_id}/special_products/', [SpecialWarehouseController::class, 'special_products_index'])->name('inventory.special_products.index');
Route::get('/inventory/{warehouse_id}/special_products/create/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_create'])->name('inventory.special_products.create');
Route::post('/inventory/special_products/store/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_store'])->name('inventory.special_products.store');

Route::get('/inventory/{warehouse_id}/special_dispatch_index/', [SpecialWarehouseController::class, 'special_dispatch_index'])->name('inventory.special_dispatch.index');
Route::get('/inventory/{warehouse_id}/special_dispatch_approved/', [SpecialWarehouseController::class, 'special_dispatch_approved'])->name('inventory.special_dispatch.approved');
Route::get('/inventory/{warehouse_id}/special_dispatch_rejected/', [SpecialWarehouseController::class, 'special_dispatch_rejected'])->name('inventory.special_dispatch.rejected');


Route::post('/inventory/special_dispatch_accept_decline/{project_entry_id}', [SpecialWarehouseController::class, 'special_dispatch_accept_decline'])->name('inventory.special_dispatch.accept_decline');
Route::post('/inventory/special_dispatch_output_store', [SpecialWarehouseController::class, 'special_dispatch_output_store'])->name('inventory.special_dispatch_output.store');

Route::get('/inventory/{warehouse_id}/special_refund_index/', [SpecialWarehouseController::class, 'special_refund_index'])->name('inventory.special_refund.index');
Route::post('/inventory/special_refund_index/accept-decline/{refund_id}', [SpecialWarehouseController::class, 'special_refund_accept_decline'])->name('inventory.special_refund.accept_decline');
Route::get('/inventory/{warehouse_id}/special_refund_historial/', [SpecialWarehouseController::class, 'special_refund_historial'])->name('inventory.special_refund.historial');

//Retrieval_entry
Route::get('/inventory/retrieval_entry/warehouses/{boolean?}', [WarehousesController::class, 'retrieval_entry_index'])->name('inventory.retrieval_entry.index');
Route::post('/inventory/warehouses/retrieval_entry', [WarehousesController::class, 'retrievalEntryApprove'])->name('retrievalentry.approbe');

//Retrieval_product
Route::get('/inventory/retrieval_product/warehouses', [WarehousesController::class, 'retrievalProduct'])->name('inventory.retrievalProduct.index');
Route::get('/inventory/show/retrieval_product/{product}/warehouses', [WarehousesController::class, 'retrievalProductShow'])->name('inventory.retrievalProduct.show');

//Retrieval_dispatch
Route::get('/inventory/retrieval_dispatch/warehouses', [WarehousesController::class, 'retrievalDispatch'])->name('inventory.retrievalDispatch.index');
Route::post('/inventory/approve/retrieval_dispatch/warehouses', [WarehousesController::class, 'retrievalDispatchApprove'])->name('inventory.retrievalDispatch.approve');


