<?php

use App\Http\Controllers\Huawei\HuaweiBalanceController;
use App\Http\Controllers\Huawei\HuaweiInventory\HuaweiSpecialRefundsController;
use App\Support\RouteDefinitions\HuaweiRoutes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Huawei\Projects\HuaweiProjectController;
use App\Http\Controllers\Huawei\QuickMaterialsController;

foreach (HuaweiRoutes::all() as $route) {
    $routeInstance = Route::{$route['method']}($route['uri'], $route['action'])
        ->name($route['name']);
    if ($route['permission']) {
        $routeInstance->middleware('permission:' . $route['name']);
    }
}

Route::middleware('permission:HuaweiManager')->group(function () {

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

    //SpecialRefunds
    Route::get('huawei/special_refunds/get', [HuaweiSpecialRefundsController::class, 'getSpecialRefunds'])->name('huawei.specialrefunds');
    Route::get('huawei/speacial_refunds/search/{request}', [HuaweiSpecialRefundsController::class, 'searchSpecialRefunds'])->name('huawei.specialrefunds.search');
    Route::post('huawei/special_refunds/post', [HuaweiSpecialRefundsController::class, 'storeSpecialRefund'])->name('huawei.specialrefunds.store');
    Route::put('huawei/special_refunds/{id}/put', [HuaweiSpecialRefundsController::class, 'updateSpecialRefund'])->name('huawei.specialrefunds.update');
    Route::delete('huawei/special_refunds/{id}/delete', [HuaweiSpecialRefundsController::class, 'deleteSpecialRefund'])->name('huawei.specialrefunds.delete');

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

    //deleted
    //Route::post('huawei/projects/general_expenses/massive_validate/post', [HuaweiMonthlyController::class, 'massiveValidate'])->name('huawei.projects.general.expenses.massivevalidate');

});