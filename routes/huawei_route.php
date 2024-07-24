<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\HuaweiController;
use App\Http\Controllers\Huawei\HuaweiManagementController;
use App\Http\Controllers\Huawei\HuaweiProjectController;

Route::middleware('permission:HuaweiManager')->group(function () {
    Route::get('/huaweiLoads', [HuaweiController::class, 'show'])->name('huawei.loads');
    Route::post('/huaweiLoads/import', [HuaweiController::class, 'import'])->name('huawei.loads.import');
    Route::get('/huaweiLoads/{loadId}/products/{noPg?}', [HuaweiController::class, 'renderByLoad'])->name('huawei.loads.products');
    Route::get('/huaweiLoads/products/{huawei_product}/similarity', [HuaweiController::class, 'searchSimilarities'])->name('huawei.loads.products.similarities');
    Route::put('/huaweiLoads/{loadId}/products/associate/{huawei_product}', [HuaweiController::class, 'associate'])->name('huawei.loads.products.associate');
    Route::get('/huaweiLoads/{loadId}/exportpdf', [HuaweiController::class, 'exportHuaweiProducts'])->name('huawei.loads.exportpdf');

    //Inventory
    Route::get('/huawei/inventory/{equipment?}', [HuaweiManagementController::class, 'show'])->name('huawei.inventory.show');
    Route::get('/huawei/inventory/search/{request}/{equipment?}', [HuaweiManagementController::class, 'searchIndex'])->name('huawei.inventory.show.search');
    Route::get('/huawei/inventory/create/get', [HuaweiManagementController::class, 'create'])->name('huawei.inventory.create');
    Route::post('/huawei/inventory/create/post', [HuaweiManagementController::class, 'store'])->name('huawei.inventory.store');
    Route::post('/huawei/inventory/create/get/brand_post', [HuaweiManagementController::class, 'storeBrand'])->name('huawei.inventory.create.brand');
    Route::post('/huawei/inventory/create/get/brand_model_post', [HuaweiManagementController::class, 'storeBrandModel'])->name('huawei.inventory.create.brandmodel');
    Route::get('/huawei/inventory/details/{id}/{equipment?}', [HuaweiManagementController::class, 'showDetails'])->name('huawei.inventory.show.details');
    Route::get('/huawei/inventory/details/search/{id}/{request}/{equipment?}', [HuaweiManagementController::class, 'search'])->name('huawei.inventory.show.details.search');
    Route::post('/huawei/inventory/details/refunds/post/{equipment?}', [HuaweiManagementController::class, 'refund'])->name('huawei.inventory.details.refund');
    Route::get('/huawei/inventory/refunds/get/{equipment?}', [HuaweiManagementController::class, 'getRefunds'])->name('huawei.inventory.refunds');
    Route::get('/huawei/inventory/refunds/search/{request}/{equipment?}', [HuaweiManagementController::class, 'searchRefunds'])->name('huawei.inventory.refunds.search');
    //Route::get('huawei/inventory/antiquation/get', [HuaweiManagementController::class, 'antiquation'])->name('huawei.inventory.antiquation');

    //projects
    Route::get('huawei/projects/show', [HuaweiProjectController::class, 'show'])->name('huawei.projects');
    Route::get('huawei/projects/search/{request}', [HuaweiProjectController::class, 'searchProject'])->name('huawei.projects.search');
    Route::get('huawei/projects/prereport/{huawei_project}', [HuaweiProjectController::class, 'showPreReport'])->name('huawei.projects.prereport');
    Route::get('huawei/projects/history', [HuaweiProjectController::class, 'projectHistory'])->name('huawei.projects.history');
    Route::get('huawei/projects/history/search/{request}', [HuaweiProjectController::class, 'searchProjectHistory'])->name('huawei.projects.search.history');
    Route::get('huawei/projects/create', [HuaweiProjectController::class, 'create'])->name('huawei.projects.create');
    Route::post('huawei/projects/store', [HuaweiProjectController::class, 'store'])->name('huawei.projects.store');
    Route::get('huawei/projects/toUpdate/{huawei_project}', [HuaweiProjectController::class, 'toUpdate'])->name('huawei.projects.toupdate');
    Route::post('huawei/projects/update/{huawei_project}', [HuaweiProjectController::class, 'update'])->name('huawei.projects.update');
    Route::delete('huawei/projects/deleteemployee/{id}/delete', [HuaweiProjectController::class, 'deleteEmployee'])->name('huawei.projects.deleteemployee');
    Route::post('huawei/projects/addemployee/{huawei_project}/add', [HuaweiProjectController::class, 'add_employee'])->name('huawei.projects.addemployee');
    Route::put('huawei/projects/{huawei_project}/liquidate/put', [HuaweiProjectController::class, 'liquidateProject'])->name('huawei.projects.liquidateproject');
    Route::put('huawei/projects/{huawei_project}/cancel/put', [HuaweiProjectController::class, 'cancelProject'])->name('huawei.projects.cancelproject');
    Route::get('huawei/projects/balance/{huawei_project}/get', [HuaweiProjectController::class, 'projectBalance'])->name('huawei.projects.balance');
    Route::get('huawei/projects/stopped/get', [HuaweiProjectController::class, 'showStoppedProjects'])->name('huawei.projects.stopped');
    Route::put('huawei/projects/stopped/{huawei_project}/put', [HuaweiProjectController::class, 'resumeProject'])->name('huawei.projects.stopped.resume');
    Route::get('huawei/projects/stopped/{request}/search', [HuaweiProjectController::class, 'searchStoppedProjects'])->name('huawei.projects.stopped.search');

    //sites
    Route::get('huawei/sites/get', [HuaweiProjectController::class, 'getSites'])->name('huawei.sites');
    Route::post('huawei/sites/store', [HuaweiProjectController::class, 'storeSite'])->name('huawei.sites.post');
    Route::put('huawei/sites/update/{site}', [HuaweiProjectController::class, 'updateSite'])->name('huawei.sites.put');
    Route::post('huawei/sites/verify/{update?}', [HuaweiProjectController::class, 'verifySiteName'])->name('huawei.sites.verify');
    Route::get('huawei/sites/search/{request}', [HuaweiProjectController::class, 'searchSites'])->name('huawei.sites.search');

    //additional_costs
    Route::get('huawei/projects/{huawei_project}/additional_costs/get', [HuaweiProjectController::class, 'getAdditionalCosts'])->name('huawei.projects.additionalcosts');
    Route::post('huawei/projects/{huawei_project}/additional_costs/store', [HuaweiProjectController::class, 'storeAdditionalCosts'])->name('huawei.projects.additionalcosts.store');
    Route::put('huawei/projects/{huawei_project}/additional_costs/update/{huawei_additional_cost}', [HuaweiProjectController::class, 'updateAdditionalCosts'])->name('huawei.projects.additionalcosts.update');
    Route::delete('huawei/projects/{huawei_project}/additional_costs/delete/{huawei_additional_cost}', [HuaweiProjectController::class, 'deleteAdditionalCost'])->name('huawei.projects.additionalcosts.delete');
    Route::get('huawei/projects/{huawei_project}/additional_costs/search/{request}', [HuaweiProjectController::class, 'searchAdditionalCosts'])->name('huawei.projects.additionalcosts.search');

    //resources
    Route::get('huawei/projects/{huawei_project}/resources/get/{equipment?}', [HuaweiProjectController::class, 'getResources'])->name('huawei.projects.resources');
    Route::get('huawei/projects/{huawei_project}/resources/search/{request}/{equipment?}', [HuaweiProjectController::class, 'searchResources'])->name('huawei.projects.resources.search');
    Route::post('huawei/projects/{huawei_project}/resources/post/{equipment?}', [HuaweiProjectController::class, 'storeProjectResource'])->name('huawei.projects.resources.store');
    Route::put('huawei/projects/refund_resource/{huawei_resource}/{equipment?}', [HuaweiProjectController::class, 'refundResource'])->name('huawei.projects.refund');

    //earnings
    Route::get('huawei/projects/{huawei_project}/earnings/get', [HuaweiProjectController::class, 'getEarnings'])->name('huawei.projects.earnings');
    Route::get('huawei/projects/{huawei_project}/earnings/search/{request}', [HuaweiProjectController::class, 'searchEarnings'])->name('huawei.projects.earnings.search');
    Route::post('huawei/projects/earnings/post', [HuaweiProjectController::class, 'storeEarning'])->name('huawei.projects.earnings.store');
    Route::put('huawei/projects/earnings/{huawei_project_earning}/put', [HuaweiProjectController::class, 'updateEarning'])->name('huawei.projects.earnings.update');
    Route::delete('huawei/projects/earnings/{huawei_project_earning}/delete', [HuaweiProjectController::class, 'deleteEarning'])->name('huawei.projects.earnings.delete');

    //liquidations
    Route::get('huawei/projects/{huawei_project}/liquidations/get_resources', [HuaweiProjectController::class, 'geResourcesToLiquidate'])->name('huawei.projects.liquidations');
    Route::get('huawei/projects/{huawei_project}/liquidations/history/{equipment?}', [HuaweiProjectController::class, 'liquidationsHistory'])->name('huawei.projects.liquidations.history');
    Route::post('huawei/projects/{huawei_project}/liquidations/store_liquidation/{equipment?}', [HuaweiProjectController::class, 'liquidate'])->name('huawei.projects.liquidations.post');
});





