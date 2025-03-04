<?php

use App\Http\Controllers\Huawei\HuaweiBalanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\HuaweiController;
use App\Http\Controllers\Huawei\HuaweiManagementController;
use App\Http\Controllers\Huawei\HuaweiMobileController;
use App\Http\Controllers\Huawei\HuaweiMonthlyController;
use App\Http\Controllers\Huawei\HuaweiProjectController;
use App\Http\Controllers\Huawei\QuickMaterialsController;

Route::middleware('permission:HuaweiManager')->group(function () {
    // Route::get('/huaweiLoads', [HuaweiController::class, 'show'])->name('huawei.loads');
    // Route::post('/huaweiLoads/import', [HuaweiController::class, 'import'])->name('huawei.loads.import');
    // Route::get('/huaweiLoads/{loadId}/products/{noPg?}', [HuaweiController::class, 'renderByLoad'])->name('huawei.loads.products');
    // Route::get('/huaweiLoads/products/{huawei_product}/similarity', [HuaweiController::class, 'searchSimilarities'])->name('huawei.loads.products.similarities');
    // Route::put('/huaweiLoads/{loadId}/products/associate/{huawei_product}', [HuaweiController::class, 'associate'])->name('huawei.loads.products.associate');
    // Route::get('/huaweiLoads/{loadId}/exportpdf', [HuaweiController::class, 'exportHuaweiProducts'])->name('huawei.loads.exportpdf');

    //Inventory
    Route::get('/huawei/inventory/{warehouse}/get/{equipment?}', [HuaweiManagementController::class, 'show'])->name('huawei.inventory.show');
    Route::get('/huawei/inventory/orders/pending_orders/get', [HuaweiManagementController::class, 'getPendingOrders'])->name('huawei.inventory.pendingorders');
    Route::post('/huawei/inventory/orders/pending_orders/search_advance/post', [HuaweiManagementController::class, 'ordersSearchAdvance'])->name('huawei.inventory.pendingorders.searchadvance');
    Route::post('/huawei/inventory/orders/pending_orders/assign_guide/{order}/post', [HuaweiManagementController::class, 'orderAssignGuide'])->name('huawei.inventory.pendingorders.assignguide');
    Route::get('/huawei/inventory/orders/pending_orders/fetch_orders/get', [HuaweiManagementController::class, 'fetchPendingOrders'])->name('huawei.inventory.pendingorders.fetch');
    Route::get('/huawei/inventory/search/{warehouse}/get/{request}/{equipment?}', [HuaweiManagementController::class, 'searchIndex'])->name('huawei.inventory.show.search');
    Route::get('/huawei/inventory/create/form/get', [HuaweiManagementController::class, 'create'])->name('huawei.inventory.create');
    Route::post('/huawei/inventory/create/form/post', [HuaweiManagementController::class, 'store'])->name('huawei.inventory.store');
    Route::post('/huawei/inventory/create/form/post_order/order', [HuaweiManagementController::class, 'storeOrder'])->name('huawei.inventory.store.order');
    Route::post('/huawei/inventory/create/get/brand_post', [HuaweiManagementController::class, 'storeBrand'])->name('huawei.inventory.create.brand');
    Route::post('/huawei/inventory/create/get/brand_model_post', [HuaweiManagementController::class, 'storeBrandModel'])->name('huawei.inventory.create.brandmodel');
    Route::get('/huawei/inventory/details/{id}/{equipment?}', [HuaweiManagementController::class, 'showDetails'])->name('huawei.inventory.show.details');
    Route::get('/huawei/inventory/details/{id}/without_diu/get', [HuaweiManagementController::class, 'detailsWithoutDiu'])->name('huawei.inventory.show.details.withoutdiu');
    Route::get('/huawei/inventory/details/search/{id}/{request}/{equipment?}', [HuaweiManagementController::class, 'search'])->name('huawei.inventory.show.details.search');
    Route::post('/huawei/inventory/details/refunds/post/{equipment?}', [HuaweiManagementController::class, 'refund'])->name('huawei.inventory.details.refund');
    Route::get('/huawei/inventory/refunds/view/get/{warehouse}/{equipment?}', [HuaweiManagementController::class, 'getRefunds'])->name('huawei.inventory.refunds');
    Route::get('/huawei/inventory/refunds/view/search/{warehouse}/{request}/{equipment?}', [HuaweiManagementController::class, 'searchRefunds'])->name('huawei.inventory.refunds.search');
    Route::get('/huawei/inventory/show_guide/{entry}/get', [HuaweiManagementController::class, 'showGuide'])->name('huawei.inventory.showguide');

    //Route::get('huawei/inventory/antiquation/get', [HuaweiManagementController::class, 'antiquation'])->name('huawei.inventory.antiquation');
    Route::post('/huawei/inventory/details/assign_diu/post', [HuaweiManagementController::class, 'assignDIU'])->name('huawei.inventory.details.assigndiu');
    Route::get('/huawei/inventory/export/general/export/get', [HuaweiManagementController::class, 'exportInventory'])->name('huawei.inventory.export');
    Route::get('/huawei/inventory/general/general_equipments/{prefix}/get', [HuaweiManagementController::class, 'getGeneralEquipments'])->name('huawei.inventory.general.equipments');
    Route::post('/huawei/inventory/general/general_equipments/{prefix}/search_advance/post', [HuaweiManagementController::class, 'searchGeneralAdvance'])->name('huawei.inventory.general.equipments.searchadvance');
    Route::post('/huawei/inventory/general/general_equipments/massive_update/post', [HuaweiManagementController::class, 'generalMassiveUpdate'])->name('huawei.inventory.general.equipments.massiveupdate');
    Route::get('/huawei/inventory/general/general_equipments/{prefix}/search/{request}', [HuaweiManagementController::class, 'searchGeneralEquipments'])->name('huawei.inventory.general.equipments.search');
    Route::put('huawei/inventory/update_entry_detail_date/{huawei_entry_detail}/put', [HuaweiManagementController::class, 'updateEntryDate'])->name('huawei.inventory.update.entrydetail');
    Route::put('huawei/inventory/update_entry_detail_site/{huawei_entry_detail}/put', [HuaweiManagementController::class, 'updateSite'])->name('huawei.inventory.update.entrydetail.site');
    Route::post('huawei/inventory/create/{equipment}/verify_serie/post', [HuaweiManagementController::class, 'verifySerie'])->name('huawei.inventory.create.verifyserie');
    Route::get('huawei/inventory/create/{value}/get_inventory/get', [HuaweiManagementController::class, 'getInventoryPerWarehouse'])->name('huawei.inventory.create.getinventory');
    //projects
    Route::get('huawei/projects/show/{status}/{prefix}/get', [HuaweiProjectController::class, 'show'])->name('huawei.projects');
    Route::get('huawei/projects/search/{status}/{prefix}/get/{request}', [HuaweiProjectController::class, 'searchProject'])->name('huawei.projects.search');
    Route::get('huawei/projects/prereport/{huawei_project}', [HuaweiProjectController::class, 'showPreReport'])->name('huawei.projects.prereport');
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
    Route::put('huawei/projects/stopped/{huawei_project}/put', [HuaweiProjectController::class, 'resumeProject'])->name('huawei.projects.stopped.resume');
    Route::post('huawei/projects/import_base_lines/{zone}', [HuaweiProjectController::class, 'importBaseLines'])->name('huawei.projects.import.baselines');
    Route::get('huawei/projects/base_lines/donwload_template/get', [HuaweiProjectController::class, 'downloadTemplate'])->name('huawei.projects.baselines.template');

    //sites
    Route::get('huawei/sites/get', [HuaweiProjectController::class, 'getSites'])->name('huawei.sites');
    Route::post('huawei/sites/store', [HuaweiProjectController::class, 'storeSite'])->name('huawei.sites.post');
    Route::put('huawei/sites/update/{site}', [HuaweiProjectController::class, 'updateSite'])->name('huawei.sites.put');
    Route::delete('huawei/sites/delete/{site}', [HuaweiProjectController::class, 'destroySite'])->name('huawei.sites.delete');
    Route::post('huawei/sites/verify/{update?}', [HuaweiProjectController::class, 'verifySiteName'])->name('huawei.sites.verify');
    Route::get('huawei/sites/search/{request}', [HuaweiProjectController::class, 'searchSites'])->name('huawei.sites.search');

    //additional_costs
    Route::get('huawei/projects/{huawei_project}/additional_costs/get', [HuaweiProjectController::class, 'getAdditionalCosts'])->name('huawei.projects.additionalcosts');
    Route::post('huawei/projects/{huawei_project}/additional_costs/store', [HuaweiProjectController::class, 'storeAdditionalCosts'])->name('huawei.projects.additionalcosts.store');
    Route::post('huawei/projects/{huawei_project}/additional_costs/update/{huawei_additional_cost}', [HuaweiProjectController::class, 'updateAdditionalCosts'])->name('huawei.projects.additionalcosts.update');
    Route::get('huawei/projects/{expense}/huawei_projects/{image}/show_image', [HuaweiProjectController::class, 'showImage'])->name('huawei.projects.additionalcosts.showimage');
    Route::delete('huawei/projects/{huawei_project}/additional_costs/delete/{huawei_additional_cost}', [HuaweiProjectController::class, 'deleteAdditionalCost'])->name('huawei.projects.additionalcosts.delete');
    Route::get('huawei/projects/{huawei_project}/additional_costs/search/{request}', [HuaweiProjectController::class, 'searchAdditionalCosts'])->name('huawei.projects.additionalcosts.search');
    Route::put('huawei/projects/{expense}/huawei_projects/validate', [HuaweiProjectController::class, 'validateExpense'])->name('huawei.projects.additionalcosts.validate');
    Route::get('huawei/projects/{huawei_project}/addtitional_costs/summary', [HuaweiProjectController::class, 'getCostSummary'])->name('huawei.projects.additionalcosts.summary');
    Route::get('huawei/projects/additional_costs/preview/{huawei_additional_cost}', [HuaweiProjectController::class, 'showAdditionalArchive'])->name('huawei.projects.additionalcosts.preview');
    Route::post('huawei/projects/{huawei_project_id}/additional_cost/advanced_search', [HuaweiProjectController::class, 'search_costs'])->name('huawei.projects.additionalcosts.advancedsearch');
    Route::get('huawei/projects/{huawei_project}/additional_costs/export', [HuaweiProjectController::class, 'exportAdditionalCosts'])->name('huawei.projects.additionalcosts.export');
    Route::post('huawei/projects/{huawei_project}/additional_costs/import/post', [HuaweiProjectController::class, 'importCosts'])->name('huawei.projects.additionalcosts.import');
    Route::post('huawei/projects/massive_update/huawei_projects/post', [HuaweiProjectController::class, 'massiveUpdate'])->name('huawei.projects.additionalcosts.massiveupdate');

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
    Route::post('huawei/projects/{huawei_project}/resources/search_details/{id}/post/{equipment?}', [HuaweiProjectController::class, 'searchEntryDetails'])->name('huawei.projects.resources.searchdetails');
    //earnings
    Route::get('huawei/projects/{huawei_project}/earnings/get', [HuaweiProjectController::class, 'getEarnings'])->name('huawei.projects.earnings');
    Route::get('huawei/projects/{huawei_project}/earnings/search/{request}', [HuaweiProjectController::class, 'searchEarnings'])->name('huawei.projects.earnings.search');
    Route::post('huawei/projects/earnings/post', [HuaweiProjectController::class, 'storeEarning'])->name('huawei.projects.earnings.store');
    Route::put('huawei/projects/earnings/{huawei_project_earning}/put', [HuaweiProjectController::class, 'updateEarning'])->name('huawei.projects.earnings.update');
    Route::delete('huawei/projects/earnings/{huawei_project_earning}/delete', [HuaweiProjectController::class, 'deleteEarning'])->name('huawei.projects.earnings.delete');
    Route::post('huawei/projects/{huawei_project}/earnings/import', [HuaweiProjectController::class, 'importEarnings'])->name('huawei.projects.earnings.import');
    Route::get('huawei/projects/{huawei_project}/earnings/export', [HuaweiProjectController::class, 'exportEarnings'])->name('huawei.projects.earnings.export');
    Route::put('huawei/projects/{huawei_project}/earnings/{earning}/update_state', [HuaweiProjectController::class, 'updateEarningState'])->name('huawei.projects.earnings.updatestate');
    //real_earnings
    Route::get('huawei/projects/{huawei_project}/real_earnings/get', [HuaweiProjectController::class, 'getRealEarnings'])->name('huawei.projects.realearnings');
    Route::get('huawei/projects/{huawei_project}/real_earnings/search/{request}', [HuaweiProjectController::class, 'searchRealEarnings'])->name('huawei.projects.realearnings.search');
    Route::post('huawei/projects/real_earnings/post', [HuaweiProjectController::class, 'storeRealEarning'])->name('huawei.projects.realearnings.store');
    Route::put('huawei/projects/real_earnings/{huawei_project_real_earning}/put', [HuaweiProjectController::class, 'updateRealEarning'])->name('huawei.projects.realearnings.update');
    Route::delete('huawei/projects/real_earnings/{huawei_project_real_earning}/delete', [HuaweiProjectController::class, 'deleteRealEarning'])->name('huawei.projects.realearnings.delete');
    Route::get('huawei/projects/{huawei_project}/real_earnings/export', [HuaweiProjectController::class, 'exportRealEarnings'])->name('huawei.projects.realearnings.export');
    Route::post('huawei/projects/{huawei_project}/real_earnings/import', [HuaweiProjectController::class, 'importRealEarnings'])->name('huawei.projects.realearnings.import');
    Route::post('huawei/projects/{huawei_project}/real_earnings/verify_import', [HuaweiProjectController::class, 'verifyImportRealEarnings'])->name('huawei.projects.realearnings.verify');

    //liquidations
    Route::get('huawei/projects/{huawei_project}/liquidations/get_resources', [HuaweiProjectController::class, 'geResourcesToLiquidate'])->name('huawei.projects.liquidations');
    Route::get('huawei/projects/{huawei_project}/liquidations/history/{equipment?}', [HuaweiProjectController::class, 'liquidationsHistory'])->name('huawei.projects.liquidations.history');
    Route::post('huawei/projects/{huawei_project}/liquidations/store_liquidation/{equipment?}', [HuaweiProjectController::class, 'liquidate'])->name('huawei.projects.liquidations.post');
    Route::post('huawei/projects/{huawei_project}/liquidations/get_resources/search_advance/post', [HuaweiProjectController::class, 'search_advance_liquidate'])->name('huawei.projects.liquidations.searchadvance');
    Route::post('huawei/projects/liquidations/massive_liquidation/post/liquidations/{equipment?}', [HuaweiProjectController::class, 'massiveLiquidation'])->name('huawei.projects.liquidations.massiveliquidation');

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
    Route::post('huawei/general_balance/earnings/verify_import/post', [HuaweiBalanceController::class, 'verifyImportEarnings'])->name('huawei.generalbalance.earnings.verify');

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

    //quick_materials
    Route::get('huawei/quick_materials/get', [QuickMaterialsController::class, 'getMaterials'])->name('huawei.quickmaterials');
    Route::get('huawei/quick_materials/{request}/search', [QuickMaterialsController::class, 'searchMaterial'])->name('huawei.quickmaterials.search');
    Route::post('huawei/quick_materials/post', [QuickMaterialsController::class, 'storeMaterial'])->name('huawei.quickmaterials.store');
    Route::put('huawei/quick_materials/{material}/update', [QuickMaterialsController::class, 'updateMaterial'])->name('huawei.quickmaterials.update');
    Route::delete('huawei/quick_materials/{material}/delete', [QuickMaterialsController::class, 'deleteMaterial'])->name('huawei.quickmaterials.delete');
    Route::post('huawei/quick_materials/verify_name/{update?}', [QuickMaterialsController::class, 'verifyName'])->name('huawei.quickmaterials.verifyname');
    //quick_materials_details
    Route::get('huawei/quick_materials/{material_id}/details/get', [QuickMaterialsController::class, 'index'])->name('huawei.quickmaterials.details');
    Route::post('huawei/quick_materials/{material_id}/details/store', [QuickMaterialsController::class, 'store'])->name('huawei.quickmaterials.details.store');
    Route::delete('huawei/quick_materials/details/{quick_material}/delete', [QuickMaterialsController::class, 'destroy'])->name('huawei.quickmaterials.details.delete');

    //outputs-
    Route::post('huawei/quick_materials/details/store/output/{entry_id}', [QuickMaterialsController::class, 'storeOutput'])->name('huawei.quickmaterials.details.output.store');
    Route::delete('huawei/quick_materials/details/delete/output/{output}', [QuickMaterialsController::class, 'destroyOutput'])->name('huawei.quickmaterials.details.output.delete');
    Route::post('huawei/quick_materials/details/fetchprojects/output/{site_id}', [QuickMaterialsController::class, 'fetchProjects'])->name('huawei.quickmaterials.details.output.fetchprojects');
    Route::post('huawei/quick_materials/details/selectproject/output/{entry_id}/{project_id}/{output_id?}', [QuickMaterialsController::class, 'selectProject'])->name('huawei.quickmaterials.details.output.selectproject');

    //internal_guide
    Route::get('huawei/internal_guides/get', [QuickMaterialsController::class, 'internalGuides'])->name('huawei.internalguides');
    Route::post('huawei/internal_guides/generate', [QuickMaterialsController::class, 'generateInternalGuide'])->name('huawei.internalguides.store');
    Route::delete('huawei/internal_guides/{id}/delete', [QuickMaterialsController::class, 'deleteInternalGuide'])->name('huawei.internalguides.delete');
    Route::get('huawei/internal_guides/{id}/show', [QuickMaterialsController::class, 'showInternalGuide'])->name('huawei.internalguides.show');

    //monthly_projects
    // Route::get('huawei/monthly_projects/get', [HuaweiMonthlyController::class, 'getProjects'])->name('huawei.monthlyprojects');
    // Route::get('huawei/monthly_projects/{request}/search', [HuaweiMonthlyController::class, 'searchProjects'])->name('huawei.monthlyprojects.search');
    // Route::post('huawei/monthly_projects/store', [HuaweiMonthlyController::class, 'storeProject'])->name('huawei.monthlyprojects.store');
    // Route::put('huawei/monthly_projects/{project}/update', [HuaweiMonthlyController::class, 'updateProject'])->name('huawei.monthlyprojects.update');

    //monthly_expenses
    Route::get('huawei/projects/monthly_expenses/general_balance/get', [HuaweiMonthlyController::class, 'getGeneralBalance'])->name('huawei.projects.generalbalance');
    Route::get('huawei/projects/general_expenses/get/{mode?}', [HuaweiMonthlyController::class, 'getExpenses'])->name('huawei.projects.general.expenses');
    Route::get('huawei/projects/general_expenses/{request}/search/{mode?}', [HuaweiMonthlyController::class, 'searchExpenses'])->name('huawei.projects.general.expenses.search');
    Route::post('huawei/projects//general_expenses/search_advance/search/{mode?}', [HuaweiMonthlyController::class, 'searchAdvance'])->name(name: 'huawei.projects.general.expenses.searchadvance');
    Route::post('huawei/projects/general_expenses/store/post', [HuaweiMonthlyController::class, 'storeExpense'])->name('huawei.projects.general.expenses.store');
    Route::post('huawei/projects/general_expenses/{expense}/update', [HuaweiMonthlyController::class, 'updateExpense'])->name('huawei.projects.general.expenses.update');
    Route::delete('huawei/projects/general_expenses/{expense}/delete', [HuaweiMonthlyController::class, 'deleteExpense'])->name('huawei.projects.general.expenses.delete');
    Route::put('huawei/projects/general_expenses/{expense}/validate', [HuaweiMonthlyController::class, 'validateExpense'])->name('huawei.projects.general.expenses.validate');
    Route::get('huawei/projects/general_expenses/{expense}/show_image', [HuaweiMonthlyController::class, 'showImage'])->name('huawei.projects.general.expenses.showimage');
    Route::get('huawei/projects/general_expenses/export/excel', [HuaweiMonthlyController::class, 'exportMonthlyExpenses'])->name('huawei.projects.general.expenses.export');
    Route::post('huawei/projects/general_expenses/massive_update/post', [HuaweiMonthlyController::class, 'massiveUpdate'])->name('huawei.projects.general.expenses.massiveupdate');
    Route::post('huawei/projects/general_expenses/massive_validate/post', [HuaweiMonthlyController::class, 'massiveValidate'])->name('huawei.projects.general.expenses.massivevalidate');
    Route::get('huawei/projects/general_expenses/{macro}/fetch_sites/get', [HuaweiMonthlyController::class, 'fetchSites'])->name('huawei.projects.general.expenses.fetchsites');
    Route::get('huawei/projects/general_expenses/{macro}/{site_id}/fetch_projects/get', [HuaweiMonthlyController::class, 'fetchProjects'])->name('huawei.projects.general.expenses.fetchprojects');
    Route::post('huawei/monthly_projects/general_expenses/import_costs/post', [HuaweiMonthlyController::class, 'importCosts'])->name('huawei.projects.general.expenses.import');
    Route::get('huawei/projects/general_expenses/donwload_template/get', [HuaweiMonthlyController::class, 'downloadTemplate'])->name('huawei.projects.general.expenses.donwloadtemplate');
});





