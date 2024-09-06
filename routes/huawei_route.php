<?php

use App\Http\Controllers\Huawei\HuaweiApiController;
use App\Http\Controllers\Huawei\HuaweiBalanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\HuaweiController;
use App\Http\Controllers\Huawei\HuaweiManagementController;
use App\Http\Controllers\Huawei\HuaweiMobileController;
use App\Http\Controllers\Huawei\HuaweiProjectController;

Route::middleware('permission:HuaweiManager')->group(function () {
    // Route::get('/huaweiLoads', [HuaweiController::class, 'show'])->name('huawei.loads');
    // Route::post('/huaweiLoads/import', [HuaweiController::class, 'import'])->name('huawei.loads.import');
    // Route::get('/huaweiLoads/{loadId}/products/{noPg?}', [HuaweiController::class, 'renderByLoad'])->name('huawei.loads.products');
    // Route::get('/huaweiLoads/products/{huawei_product}/similarity', [HuaweiController::class, 'searchSimilarities'])->name('huawei.loads.products.similarities');
    // Route::put('/huaweiLoads/{loadId}/products/associate/{huawei_product}', [HuaweiController::class, 'associate'])->name('huawei.loads.products.associate');
    // Route::get('/huaweiLoads/{loadId}/exportpdf', [HuaweiController::class, 'exportHuaweiProducts'])->name('huawei.loads.exportpdf');

    //Inventory
    Route::get('/huawei/inventory/{equipment?}', [HuaweiManagementController::class, 'show'])->name('huawei.inventory.show');
    Route::get('/huawei/inventory/search/{request}/{equipment?}', [HuaweiManagementController::class, 'searchIndex'])->name('huawei.inventory.show.search');
    Route::get('/huawei/inventory/create/get', [HuaweiManagementController::class, 'create'])->name('huawei.inventory.create');
    Route::post('/huawei/inventory/create/post', [HuaweiManagementController::class, 'store'])->name('huawei.inventory.store');
    Route::post('/huawei/inventory/create/get/brand_post', [HuaweiManagementController::class, 'storeBrand'])->name('huawei.inventory.create.brand');
    Route::post('/huawei/inventory/create/get/brand_model_post', [HuaweiManagementController::class, 'storeBrandModel'])->name('huawei.inventory.create.brandmodel');
    Route::get('/huawei/inventory/details/{id}/{equipment?}', [HuaweiManagementController::class, 'showDetails'])->name('huawei.inventory.show.details');
    Route::get('/huawei/inventory/details/{id}/without_diu/get', [HuaweiManagementController::class, 'detailsWithoutDiu'])->name('huawei.inventory.show.details.withoutdiu');
    Route::get('/huawei/inventory/details/search/{id}/{request}/{equipment?}', [HuaweiManagementController::class, 'search'])->name('huawei.inventory.show.details.search');
    Route::post('/huawei/inventory/details/refunds/post/{equipment?}', [HuaweiManagementController::class, 'refund'])->name('huawei.inventory.details.refund');
    Route::get('/huawei/inventory/refunds/get/{equipment?}', [HuaweiManagementController::class, 'getRefunds'])->name('huawei.inventory.refunds');
    Route::get('/huawei/inventory/refunds/search/{request}/{equipment?}', [HuaweiManagementController::class, 'searchRefunds'])->name('huawei.inventory.refunds.search');
    //Route::get('huawei/inventory/antiquation/get', [HuaweiManagementController::class, 'antiquation'])->name('huawei.inventory.antiquation');
    Route::post('/huawei/inventory/details/assign_diu/post', [HuaweiManagementController::class, 'assignDIU'])->name('huawei.inventory.details.assigndiu');
    Route::get('/huawei/inventory/export/get', [HuaweiManagementController::class, 'exportInventory'])->name('huawei.inventory.export');
    Route::get('/huawei/inventory/general_equipments/get', [HuaweiManagementController::class, 'getGeneralEquipments'])->name('huawei.inventory.general.equipments');
    Route::get('/huawei/inventory/general_equipments/search/{request}', [HuaweiManagementController::class, 'searchGeneralEquipments'])->name('huawei.inventory.general.equipments.search');
    Route::put('huawei/inventory/update_entry_detail_date/{huawei_entry_detail}/put', [HuaweiManagementController::class, 'updateEntryDate'])->name('huawei.inventory.update.entrydetail');
    Route::put('huawei/inventory/update_entry_detail_site/{huawei_entry_detail}/put', [HuaweiManagementController::class, 'updateSite'])->name('huawei.inventory.update.entrydetail.site');

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
    Route::put('huawei/projects/editemployee/{huawei_project}/edit/{id}', [HuaweiProjectController::class, 'edit_employee'])->name('huawei.projects.editemployee');
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
    Route::post('huawei/projects/{huawei_project}/additional_costs/update/{huawei_additional_cost}', [HuaweiProjectController::class, 'updateAdditionalCosts'])->name('huawei.projects.additionalcosts.update');
    Route::delete('huawei/projects/{huawei_project}/additional_costs/delete/{huawei_additional_cost}', [HuaweiProjectController::class, 'deleteAdditionalCost'])->name('huawei.projects.additionalcosts.delete');
    Route::get('huawei/projects/{huawei_project}/additional_costs/search/{request}', [HuaweiProjectController::class, 'searchAdditionalCosts'])->name('huawei.projects.additionalcosts.search');
    Route::get('huawei/projects/{huawei_project}/addtitional_costs/summary', [HuaweiProjectController::class, 'getCostSummary'])->name('huawei.projects.additionalcosts.summary');
    Route::get('huawei/projects/additional_costs/preview/{huawei_additional_cost}', [HuaweiProjectController::class, 'showAdditionalArchive'])->name('huawei.projects.additionalcosts.preview');
    Route::post('huawei/projects/{huawei_project_id}/additional_cost/advanced_search', [HuaweiProjectController::class, 'search_costs'])->name('huawei.projects.additionalcosts.advancedsearch');
    Route::get('huawei/projects/{huawei_project}/additional_costs/export', [HuaweiProjectController::class, 'exportAdditionalCosts'])->name('huawei.projects.additionalcosts.export');
    Route::post('huawei/projects/{huawei_project}/additional_costs/import/post', [HuaweiProjectController::class, 'importCosts'])->name('huawei.projects.additionalcosts.import');

    //static costs

    Route::get('huawei/projects/{huawei_project}/static_costs/get', [HuaweiProjectController::class, 'getStaticCosts'])->name('huawei.projects.staticcosts');
    Route::post('huawei/projects/{huawei_project}/static_costs/store', [HuaweiProjectController::class, 'storeStaticCosts'])->name('huawei.projects.staticcosts.store');
    Route::post('huawei/projects/{huawei_project}/static_costs/update/{static_additional_cost}', [HuaweiProjectController::class, 'updateStaticCosts'])->name('huawei.projects.staticcosts.update');
    Route::delete('huawei/projects/{huawei_project}/static_costs/delete/{static_additional_cost}', [HuaweiProjectController::class, 'deleteStaticCost'])->name('huawei.projects.staticcosts.delete');
    Route::get('huawei/projects/{huawei_project}/static_costs/search/{request}', [HuaweiProjectController::class, 'searchStaticCosts'])->name('huawei.projects.staticcosts.search');
    Route::get('huawei/projects/static_costs/preview/{static_additional_cost}', [HuaweiProjectController::class, 'showStaticArchive'])->name('huawei.projects.staticcosts.preview');
    Route::post('huawei/projects/{huawei_project_id}/static_costs/advanced_search', [HuaweiProjectController::class, 'search_static_costs'])->name('huawei.projects.staticcosts.advancedsearch');
    Route::get('huawei/projects/{huawei_project}/static_costs/export', [HuaweiProjectController::class, 'exportStaticCosts'])->name('huawei.projects.staticcosts.export');

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
    Route::post('huawei/projects/{huawei_project}/earnings/import', [HuaweiProjectController::class, 'importEarnings'])->name('huawei.projects.earnings.import');
    Route::get('huawei/projects/{huawei_project}/earnings/export', [HuaweiProjectController::class, 'exportEarnings'])->name('huawei.projects.earnings.export');

    //real_earnings
    Route::get('huawei/projects/{huawei_project}/real_earnings/get', [HuaweiProjectController::class, 'getRealEarnings'])->name('huawei.projects.realearnings');
    Route::get('huawei/projects/{huawei_project}/real_earnings/search/{request}', [HuaweiProjectController::class, 'searchRealEarnings'])->name('huawei.projects.realearnings.search');
    Route::post('huawei/projects/real_earnings/post', [HuaweiProjectController::class, 'storeRealEarning'])->name('huawei.projects.realearnings.store');
    Route::put('huawei/projects/real_earnings/{huawei_project_real_earning}/put', [HuaweiProjectController::class, 'updateRealEarning'])->name('huawei.projects.realearnings.update');
    Route::delete('huawei/projects/real_earnings/{huawei_project_real_earning}/delete', [HuaweiProjectController::class, 'deleteRealEarning'])->name('huawei.projects.realearnings.delete');
    Route::get('huawei/projects/{huawei_project}/real_earnings/export', [HuaweiProjectController::class, 'exportRealEarnings'])->name('huawei.projects.realearnings.export');
    Route::post('huawei/projects/{huawei_project}/real_earnings/import', [HuaweiProjectController::class, 'importRealEarnings'])->name('huawei.projects.realearnings.import');

    //liquidations
    Route::get('huawei/projects/{huawei_project}/liquidations/get_resources', [HuaweiProjectController::class, 'geResourcesToLiquidate'])->name('huawei.projects.liquidations');
    Route::get('huawei/projects/{huawei_project}/liquidations/history/{equipment?}', [HuaweiProjectController::class, 'liquidationsHistory'])->name('huawei.projects.liquidations.history');
    Route::post('huawei/projects/{huawei_project}/liquidations/store_liquidation/{equipment?}', [HuaweiProjectController::class, 'liquidate'])->name('huawei.projects.liquidations.post');

    //SpecialRefunds
    Route::get('huawei/special_refunds/get', [HuaweiManagementController::class, 'getSpecialRefunds'])->name('huawei.specialrefunds');
    Route::get('huawei/speacial_refunds/search/{request}', [HuaweiManagementController::class, 'searchSpecialRefunds'])->name('huawei.specialrefunds.search');
    Route::post('huawei/special_refunds/post', [HuaweiManagementController::class, 'storeSpecialRefund'])->name('huawei.specialrefunds.store');
    Route::put('huawei/special_refunds/{id}/put', [HuaweiManagementController::class, 'updateSpecialRefund'])->name('huawei.specialrefunds.update');
    Route::delete('huawei/special_refunds/{id}/delete', [HuaweiManagementController::class, 'deleteSpecialRefund'])->name('huawei.specialrefunds.delete');

    //huawei_balance
    Route::get('huawei/general_balance/get', [HuaweiBalanceController::class, 'getGeneralSummary'])->name('huawei.generalbalance');

    Route::get('huawei/general_balance/earnings/get', [HuaweiBalanceController::class, 'getEarnings'])->name('huawei.generalbalance.earnings');
    Route::get('huawei/general_balance/earnings/{request}/search', [HuaweiBalanceController::class, 'searchEarnings'])->name('huawei.generalbalance.earnings.search');
    Route::post('huawei/general_balance/earnings/post', [HuaweiBalanceController::class, 'storeEarnings'])->name('huawei.generalbalance.earnings.store');
    Route::put('huawei/general_balance/earnings/{huawei_balance_earning}/put', [HuaweiBalanceController::class, 'updateEarning'])->name('huawei.generalbalance.earnings.update');
    Route::delete('huawei/general_balance/earnings/{huawei_balance_earning}/delete', [HuaweiBalanceController::class, 'deleteEarning'])->name('huawei.generalbalance.earnings.delete');
    Route::post('huawei/general_balance/earnings/import', [HuaweiBalanceController::class, 'importEarnings'])->name('huawei.generalbalance.earnings.import');
    Route::get('huawei/general_balance/earnings/export_excel', [HuaweiBalanceController::class, 'exportEarnings'])->name('huawei.generalbalance.earnings.export');

    Route::get('huawei/general_balance/costs/get/{type?}', [HuaweiBalanceController::class, 'getCosts'])->name('huawei.generalbalance.costs');
    Route::get('huawei/general_balance/costs/{request}/search/{type?}', [HuaweiBalanceController::class, 'searchCosts'])->name('huawei.generalbalance.costs.search');
    Route::post('huawei/general_balance/costs/post/{type?}', [HuaweiBalanceController::class, 'storeCosts'])->name('huawei.generalbalance.costs.store');
    Route::put('huawei/general_balance/costs/{huawei_balance_cost}/put', [HuaweiBalanceController::class, 'updateCost'])->name('huawei.generalbalance.costs.update');
    Route::delete('huawei/general_balance/costs/{huawei_balance_cost}/delete', [HuaweiBalanceController::class, 'deleteCost'])->name('huawei.generalbalance.costs.delete');
    Route::post('huawei/general_balance/costs/advanced_search/{type?}', [HuaweiBalanceController::class, 'search_costs'])->name('huawei.generalbalance.costs.advancedsearch');
    Route::get('huawei/general_balance/costs/summary/get', [HuaweiBalanceController::class, 'getSummary'])->name('huawei.generalbalance.costs.summary');
    Route::post('huawei/general_balance/costs/import_excel', [HuaweiBalanceController::class, 'importCosts'])->name('huawei.generalbalance.costs.import');
    Route::get('huawei/general_balance/costs/export_excel', [HuaweiBalanceController::class, 'exportCosts'])->name('huawei.generalbalance.costs.export');

    //mobile_section
    Route::get('huawei/titles/get', [HuaweiMobileController::class, 'getTitles'])->name('huawei.titles');
    Route::post('huawei/titles/post', [HuaweiMobileController::class, 'storeTitle'])->name('huawei.titles.store');
    Route::put('huawei/titles/{huawei_title}/put', [HuaweiMobileController::class, 'updateTitle'])->name('huawei.titles.update');
    Route::delete('huawei/titles/{huawei_title}/delete', [HuaweiMobileController::class, 'deleteTitle'])->name('huawei.titles.delete');

    Route::get('huawei/codes/get', [HuaweiMobileController::class, 'getCodes'])->name('huawei.codes');
    Route::post('huawei/codes/post', [HuaweiMobileController::class, 'storeCode'])->name('huawei.codes.store');
    Route::put('huawei/codes/{huawei_code}/put', [HuaweiMobileController::class, 'updateCode'])->name('huawei.codes.update');
    Route::delete('huawei/codes/{huawei_code}/delete', [HuaweiMobileController::class, 'deleteCode'])->name('huawei.codes.delete');

    //mobile_section_projects
    Route::get('huawei/projects/{huawei_project}/stages', [HuaweiMobileController::class, 'getProjectStages'])->name('huawei.projects.stages');
    Route::get('huawei/projects/{huawei_project}/stages/{stage}/filter', [HuaweiMobileController::class, 'filterProjectStages'])->name('huawei.projects.stages.filter');
    Route::get('huawei/projects/stages/view_image/{image}/get', [HuaweiMobileController::class, 'viewImage'])->name('huawei.projects.stages.viewimage');
    Route::get('huawei/projects/stages/download_image/{image}/get', [HuaweiMobileController::class, 'downloadImage'])->name('huawei.projects.stages.downloadimage');
    Route::delete('huawei/projects/stages/delete_image/{image}/delete', [HuaweiMobileController::class, 'deleteImage'])->name('huawei.projects.stages.deleteimage');
    Route::put('huawei/projects/stages/approve_or_reject/{image}/put', [HuaweiMobileController::class, 'approveOrReject'])->name('huawei.projects.stages.approveReject');
    Route::put('huawei/projects/stages/approve_code/{code}/put', [HuaweiMobileController::class, 'approveCode'])->name('huawei.projects.stages.approveCode');
    Route::post('huawei/projects/{huawei_project}/add_stage/post', [HuaweiMobileController::class, 'addStage'])->name('huawei.projects.stages.addStage');
    Route::put('huawei/projects/{stage}/update_stage/put', [HuaweiMobileController::class, 'enableOrDisable'])->name('huawei.projects.stages.updatestage');
});





