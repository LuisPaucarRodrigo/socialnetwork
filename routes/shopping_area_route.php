<?php

use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ShoppingArea\ProviderController;
use App\Http\Controllers\ShoppingArea\PurchaseOrdersController;
use App\Http\Controllers\ShoppingArea\PurchaseRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:PurchasingManager')->group(function () {

    //Providers
    Route::get('/shopping_area/providers/create', [ProviderController::class, 'create'])->name('providersmanagement.create');
    Route::post('/shopping_area/providers/store', [ProviderController::class, 'store'])->name('providersmanagement.store');

    Route::post('/shopping_area/providers/category', [ProviderController::class, 'category_provider'])->name('provider.category');
    Route::post('/shopping_area/providers/segment', [ProviderController::class, 'segment_provider'])->name('provider.segment');

    //Purchase request
    Route::get('/shopping_area/purchasesrequest/create', [PurchaseRequestController::class, 'create'])->name('purchasesrequest.create');
    Route::post('/shopping_area/purchasesrequest/store', [PurchaseRequestController::class, 'store'])->name('purchasesrequest.store');

    //Purchase quote
    Route::get('/shopping_area/purchasesrequest/deadline_complete/quotes/{id}', [PurchaseRequestController::class, 'purchase_quote_deadline_complete'])->name('purchasesrequest.quote_deadline.complete');
    Route::get('/shopping_area/purchasesrequest/quotes/{id}', [PurchaseRequestController::class, 'index_quotes'])->name('purchasesrequest.quotes.create');
    Route::post('/shopping_area/purchasesrequest/orders', [PurchaseRequestController::class, 'quote'])->name('purchasesrequest.quotes.store');

    Route::post('/shopping_area/purchasesrequest/reject/{id}', [PurchaseRequestController::class, 'reject_request'])->name('purchasesrequest.reject_request');

    //Purchase udpate date preproject
    Route::get('/shopping_area/purchasesrequest/purchase_quote/details/{id}', [PurchaseRequestController::class, 'purchase_quote_complete_details'])->name('purchase.quote.details.complete');
    Route::post('/shopping_area/project/purchases_request/update/quotedeadline', [PurchaseRequestController::class, 'project_purchases_request_update_quote_deadline'])->name('purchase.update_quotedeadline');
    
    //Orders
    Route::post('/shopping_area/purchaseorders/state', [PurchaseOrdersController::class, 'state'])->name('purchaseorders.state');
});

Route::middleware('permission:PurchasingManager|Purchasing')->group(function () {

    //Providers
    Route::get('/shopping_area/providers', [ProviderController::class, 'index'])->name('providersmanagement.index');
    Route::get('/shopping_area/providers/search/{request}', [ProviderController::class, 'search'])->name('providersmanagement.search');

    //Purchase request
    Route::get('/shopping_area/purchasesrequest', [PurchaseRequestController::class, 'index'])->name('purchasesrequest.index');
    Route::get('/shopping_area/purchasesrequest/search/{request}', [PurchaseRequestController::class, 'search'])->name('purchasesrequest.search');
    Route::get('/shopping_area/purchasesrequest/export/{id}', [PurchaseRequestController::class, 'export'])->name('purchasingrequest.export');
    Route::get('/shopping_area/purchasesrequest/details/{id}', [PurchaseRequestController::class, 'details'])->name('purchasingrequest.details');

    Route::get('/shopping_area/purchasesrequest/alarm', [PurchaseRequestController::class, 'alarm'])->name('purchasesrequest.alarm');

    //Purchase Orders
    Route::get('/shopping_area/purchaseorders', [PurchaseOrdersController::class, 'index'])->name('purchaseorders.index');
    Route::get('/shopping_area/purchaseorders/search/{request}/{history}', [PurchaseOrdersController::class, 'search'])->name('purchaseorders.search');

    Route::get('/shopping_area/purchaseorders_details/{purchase_order_id}', [PurchaseOrdersController::class, 'purchase_order_view'])->name('purchaseorders.details');

    Route::get('/shopping_area/purchaseorders/{id}/export', [PurchaseOrdersController::class, 'purchase_orders_export'])->name('purchaseorders.export.order');

    Route::get('/shopping_area/purchaseorders/alarms', [PurchaseOrdersController::class, 'purchase_orders_alarms'])->name('purchaseorders.alarms');

    //Purchase completed
    Route::get('/shopping_area/purchaseorders/history', [PurchaseOrdersController::class, 'history'])->name('purchaseorders.history');
    Route::get('/shopping_area/purchasesrequest/quotes/{id}/preview', [PurchaseRequestController::class, 'showDocument'])->name('purchasesrequest.show');

    Route::get('/shopping_area/purchasesorder/{id}/preview', [PurchaseOrdersController::class, 'showFacture'])->name('purchasesorder.showFacture');
});
