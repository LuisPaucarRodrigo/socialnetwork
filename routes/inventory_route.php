<?php

use App\Constants\RolesConstants;
use App\Http\Controllers\Inventory\PurchaseProductsController;
use App\Http\Controllers\Inventory\SpecialWarehouseController;
use App\Http\Controllers\Inventory\WarehousesController;
use Illuminate\Support\Facades\Route;
use App\Enums\Permissions\InventoryPermissions;


// Inventory
Route::put('/inventory/purchase_products/products/{purchase_product}/disable', [PurchaseProductsController::class, 'disable'])
    ->middleware('permission:' . InventoryPermissions::INVENTORY_PURCHASE_PRODUCTS_DISABLE->value)
    ->name('inventory.purchaseproducts.disable');

// Inventory Product Type and Active Type
Route::post('/inventory/purchase_products/type_product/store', [PurchaseProductsController::class, 'typeProducts'])
    ->middleware('permission:' . InventoryPermissions::INVENTORY_PURCHASE_PRODUCTS_TYPE_STORE->value)
    ->name('inventory.purchaseproducts.typeProduct');

Route::post('/inventory/purchase_products/resource_type/store', [PurchaseProductsController::class, 'resourceType'])
    ->middleware('permission:' . InventoryPermissions::INVENTORY_PURCHASE_PRODUCTS_RESOURCE_TYPE_STORE->value)
    ->name('inventory.purchaseproducts.resourceType');

// Resource
Route::get('/inventory/resource/purchase_orders/create', [WarehousesController::class, 'resource_create'])
    ->middleware('permission:' . InventoryPermissions::WAREHOUSES_RESOURCE_CREATE->value)
    ->name('warehouses.resource.create');

Route::post('/inventory/resource/purchase_orders/store', [WarehousesController::class, 'resource_store'])
    ->middleware('permission:' . InventoryPermissions::WAREHOUSES_RESOURCE_STORE->value)
    ->name('warehouses.resource.store');

// Special Warehouses
Route::delete('/inventory/special_products/destroy/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_destroy'])
    ->middleware('permission:' . InventoryPermissions::SPECIAL_PRODUCTS_DELETE->value)
    ->name('inventory.special_products.destroy');

Route::delete('/inventory/special_dispatch_output_delete/{project_entry_output_id}', [SpecialWarehouseController::class, 'special_dispatch_output_destroy'])
    ->middleware('permission:' . InventoryPermissions::SPECIAL_DISPATCH_OUTPUT_DELETE->value)
    ->name('inventory.special_dispatch_output.destroy');





Route::middleware('permission:' . implode('|', RolesConstants::INVENTORY_MODULE))->group(function () {
    //Purchase Products
    Route::post('/inventory/purchase_products/products/post', [PurchaseProductsController::class, 'store'])->name('inventory.purchaseproducts.store');
    Route::post('/inventory/purchase_products/products/{purchase_product}/update', [PurchaseProductsController::class, 'update'])->name('inventory.purchaseproducts.update');

    //Special Warehouses
    Route::get('/inventory/{warehouse_id}/special_products/create/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_create'])->name('inventory.special_products.create');
    Route::post('/inventory/special_products/store/{special_inventory_id?}', [SpecialWarehouseController::class, 'special_products_store'])->name('inventory.special_products.store');

    Route::post('/inventory/special_refund_index/accept-decline/{refund_id}', [SpecialWarehouseController::class, 'special_refund_accept_decline'])->name('inventory.special_refund.accept_decline');

    Route::post('/inventory/special_dispatch_accept_decline/{project_entry_id}', [SpecialWarehouseController::class, 'special_dispatch_accept_decline'])->name('inventory.special_dispatch.accept_decline');

    //Conproco
    Route::post('/inventory/approve_purchase_orders/{warehouse}/approve', [WarehousesController::class, 'approve'])->name('warehouses.purchaseorders.approve.post');
    Route::get('/inventory/conproco/{warehouse}/create', [WarehousesController::class, 'createProducts'])->name('warehouses.createNormalProduct');
    Route::post('/inventory/conproco/{warehouse}/store', [WarehousesController::class, 'storeProducts'])->name('warehouses.storeNormalProduct');

    //Retrieval
    Route::post('/inventory/warehouses/retrieval/approve', [WarehousesController::class, 'retrievalEntryApprove'])->name('retrieval.entry.approbe');

    //Special dispatch, conproco and retrieval
    Route::post('/inventory/special_dispatch_output_store', [SpecialWarehouseController::class, 'special_dispatch_output_store'])->name('inventory.special_dispatch_output.store');

    //Dispatch conproco and retrieval
    Route::post('/inventory/dispatches/accept_or_decline', [WarehousesController::class, 'acceptOrDeclineDispatch'])->name('warehouses.dispatches.acceptordecline');

    //Resources
    Route::post('/inventory/resource/approve/purchase_orders', [WarehousesController::class, 'approveResourcePurchaseOrders'])->name('warehouses.resource.approve');

    //Services
    Route::post('/inventory/warehouses/services/store', [WarehousesController::class, 'service_store'])->name('warehouses.service.store');
    Route::delete('/inventory/warehouses/services/delete/{id}', [WarehousesController::class, 'service_delete'])->name('warehouses.service.delete');
    Route::post('/inventory/resource/serial_number/purchase_orders', [WarehousesController::class, 'serialNumberResourcePurchaseOrders'])->name('warehouses.resource.add.serial_number');
});

Route::middleware('permission:' . implode('|', RolesConstants::INVENTORY_MODULE))->group(function () {
    //Purchase Products
    Route::get('/inventory/purchase_products/products', [PurchaseProductsController::class, 'index'])->name('inventory.purchaseproducts');
    Route::post('/inventory/purchase_products/products/search/{request}', [PurchaseProductsController::class, 'search'])->name('inventory.purchaseproducts.search');

    //warehouses
    Route::get('/inventory/warehouses', [WarehousesController::class, 'showWarehouses'])->name('warehouses.warehouses');

    //Special Warehouses
    Route::get('/inventory/{warehouse_id}/special_products', [SpecialWarehouseController::class, 'special_products_index'])->name('inventory.special_products.index');
    Route::get('/inventory/{warehouse_id}/special_dispatch_index/', [SpecialWarehouseController::class, 'special_dispatch_index'])->name('inventory.special_dispatch.index');
    Route::get('/inventory/{warehouse_id}/special_dispatch_approved/', [SpecialWarehouseController::class, 'special_dispatch_approved'])->name('inventory.special_dispatch.approved');
    Route::get('/inventory/{warehouse_id}/special_dispatch_rejected/', [SpecialWarehouseController::class, 'special_dispatch_rejected'])->name('inventory.special_dispatch.rejected');

    Route::get('/inventory/{warehouse_id}/special_refund_index/', [SpecialWarehouseController::class, 'special_refund_index'])->name('inventory.special_refund.index');
    Route::get('/inventory/{warehouse_id}/special_refund_historial/', [SpecialWarehouseController::class, 'special_refund_historial'])->name('inventory.special_refund.historial');

    //Conproco
    Route::get('/inventory/conproco/approve_purchase_orders/{warehouse}', [WarehousesController::class, 'approvePurchaseOrders'])->name('warehouses.purchaseorders.approve');
    Route::get('/inventory/conproco/{warehouse}', [WarehousesController::class, 'showProducts'])->name('warehouses.conproco.products');
    Route::get('/inventory/conproco/{warehouse}/{inventory}/get', [WarehousesController::class, 'showEntries'])->name('warehouses.products.entries');

    Route::get('/inventory/conproco/dispatches/{warehouse}', [WarehousesController::class, 'showDispatches'])->name('warehouses.dispatches');
    Route::get('/inventory/conproco/dispatches_approved/{warehouse}', [WarehousesController::class, 'showApprovedDispatches'])->name('warehouses.dispatches.approved');
    Route::get('/inventory/conproco/dispatches_rejected/{warehouse}', [WarehousesController::class, 'showRejectedDispatches'])->name('warehouses.dispatches.rejected');

    //Retrieval
    Route::get('/inventory/retrieval/entry/{boolean?}', [WarehousesController::class, 'retrieval_entry_index'])->name('inventory.retrieval.entry.index');
    Route::get('/inventory/retrieval_product/warehouses', [WarehousesController::class, 'retrievalProduct'])->name('inventory.retrieval.product.index');

    Route::get('/inventory/show/retrieval_product/{product}/warehouses', [WarehousesController::class, 'retrievalProductShow'])->name('inventory.retrieval.product.show');

    Route::get('/inventory/warehouses/retrieval/dispatch', [WarehousesController::class, 'retrievalDispatch'])->name('inventory.retrievalDispatch.index');
    Route::get('/inventory/warehouses/retrieval/dispatch/approved', [WarehousesController::class, 'retrievalDispatchApproved'])->name('inventory.retrievalDispatch.approved');
    Route::get('/inventory/warehouses/retrieval/dispatch/rejected', [WarehousesController::class, 'retrievalDispatchRejected'])->name('inventory.retrievalDispatch.rejected');

    //Resources
    Route::get('/inventory/resource/purchase_orders', [WarehousesController::class, 'resourcePurchaseOrders'])->name('warehouses.resource');
    Route::get('/inventory/resource/products/purchase_orders/{boolean?}', [WarehousesController::class, 'productResourcePurchaseOrders'])->name('warehouses.resource.active.index');

    //Services
    Route::get('/inventory/warehouses/services', [WarehousesController::class, 'service_index'])->name('warehouses.service.approve.index');
});
