<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Huawei\GeneralExpenses\HuaweiMonthlyController;
use App\Http\Controllers\Huawei\Projects\HuaweiProjectController;
use App\Http\Controllers\Huawei\Projects\Liquidations\HuaweiLiquidationController;
use App\Http\Controllers\Huawei\Projects\Resources\HuaweiResourceController;
use App\Http\Controllers\Huawei\Sites\HuaweiSitesController;
use App\Http\Controllers\Huawei\Projects\ProjectExpenses\HuaweiProjectExpensesController;

class HuaweiRoutes
{
    public static function all(): array
    {
        return [
            //main_projects
            [
                'uri' => 'huawei/projects/show/{status}/{prefix}/get',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'show'],
                'permission' => true,
                'name' => 'huawei.projects',
            ],
            [
                'uri' => 'huawei/projects/search/{status}/{prefix}/get/{request}',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'searchProject'],
                'permission' => true,
                'name' => 'huawei.projects.search',
            ],
            [
                'uri' => 'huawei/projects/create',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'create'],
                'permission' => true,
                'name' => 'huawei.projects.create',
            ],
            [
                'uri' => 'huawei/projects/store',
                'method' => 'post',
                'action' => [HuaweiProjectController::class, 'store'],
                'permission' => true,
                'name' => 'huawei.projects.store',
            ],
            [
                'uri' => 'huawei/projects/toUpdate/{huawei_project}',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'toUpdate'],
                'permission' => true,
                'name' => 'huawei.projects.toupdate',
            ],
            [
                'uri' => 'huawei/projects/update/{huawei_project}',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'update'],
                'permission' => true,
                'name' => 'huawei.projects.update',
            ],
            [
                'uri' => 'huawei/projects/import_base_lines/{zone}',
                'method' => 'post',
                'action' => [HuaweiProjectController::class, 'importBaseLines'],
                'permission' => true,
                'name' => 'huawei.projects.import.baselines',
            ],
            [
                'uri' => 'huawei/projects/base_lines/donwload_template/get',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'downloadTemplate'],
                'permission' => true,
                'name' => 'huawei.projects.baselines.template',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/liquidate/put',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'liquidateProject'],
                'permission' => true,
                'name' => 'huawei.projects.liquidateproject',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/cancel/put',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'cancelProject'],
                'permission' => true,
                'name' => 'huawei.projects.cancelproject',
            ],
            [
                'uri' => 'huawei/projects/stopped/{huawei_project}/put',
                'method' => 'put',
                'action' => [HuaweiProjectController::class, 'resumeProject'],
                'permission' => true,
                'name' => 'huawei.projects.stopped.resume',
            ],
            [
                'uri' => 'huawei/projects/balance/{huawei_project}/get',
                'method' => 'get',
                'action' => [HuaweiProjectController::class, 'projectBalance'],
                'permission' => true,
                'name' => 'huawei.projects.balance',
            ],

            //sites
            [
                'uri' => 'huawei/sites/get',
                'method' => 'get',
                'action' => [HuaweiSitesController::class, 'getSites'],
                'permission' => true,
                'name' => 'huawei.sites',
            ],
            [
                'uri' => 'huawei/sites/store',
                'method' => 'post',
                'action' => [HuaweiSitesController::class, 'storeSite'],
                'permission' => true,
                'name' => 'huawei.sites.post',
            ],
            [
                'uri' => 'huawei/sites/update/{site}',
                'method' => 'put',
                'action' => [HuaweiSitesController::class, 'updateSite'],
                'permission' => true,
                'name' => 'huawei.sites.put',
            ],
            [
                'uri' => 'huawei/sites/delete/{site}',
                'method' => 'delete',
                'action' => [HuaweiSitesController::class, 'destroySite'],
                'permission' => true,
                'name' => 'huawei.sites.delete',
            ],
            [
                'uri' => 'huawei/sites/verify/{update?}',
                'method' => 'post',
                'action' => [HuaweiSitesController::class, 'verifySiteName'],
                'permission' => true,
                'name' => 'huawei.sites.verify',
            ],
            [
                'uri' => 'huawei/sites/search/{request}',
                'method' => 'get',
                'action' => [HuaweiSitesController::class, 'searchSites'],
                'permission' => true,
                'name' => 'huawei.sites.search',
            ],



            //project_expenses
            [
                'uri' => 'huawei/projects/{huawei_project}/additional_costs/get/{mode?}',
                'method' => 'get',
                'action' => [HuaweiProjectExpensesController::class, 'getAdditionalCosts'],
                'permission' => true,
                'name' => 'huawei.projects.additionalcosts',
            ],
            [
                'uri' => 'huawei/projects/{expense}/huawei_projects/show_image',
                'method' => 'get',
                'action' => [HuaweiProjectExpensesController::class, 'showImage'],
                'permission' => true,
                'name' => 'huawei.projects.additionalcosts.showimage',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/additional_costs/search/{request}/{mode?}',
                'method' => 'get',
                'action' => [HuaweiProjectExpensesController::class, 'searchAdditionalCosts'],
                'permission' => true,
                'name' => 'huawei.projects.additionalcosts.search',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/addtitional_costs/summary',
                'method' => 'get',
                'action' => [HuaweiProjectExpensesController::class, 'getCostSummary'],
                'permission' => true,
                'name' => 'huawei.projects.additionalcosts.summary',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project_id}/additional_cost/advanced_search/{mode?}',
                'method' => 'post',
                'action' => [HuaweiProjectExpensesController::class, 'search_costs'],
                'permission' => true,
                'name' => 'huawei.projects.additionalcosts.advancedsearch',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project_id}/additional_costs/export/{mode?}',
                'method' => 'get',
                'action' => [HuaweiProjectExpensesController::class, 'exportAdditionalCosts'],
                'permission' => true,
                'name' => 'huawei.projects.additionalcosts.export',
            ],

            //resources
            [
                'uri' => 'huawei/projects/{huawei_project}/resources/get/{equipment?}',
                'method' => 'get',
                'action' => [HuaweiResourceController::class, 'getResources'],
                'permission' => true,
                'name' => 'huawei.projects.resources',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/resources/search/{request}/{equipment?}',
                'method' => 'get',
                'action' => [HuaweiResourceController::class, 'searchResources'],
                'permission' => true,
                'name' => 'huawei.projects.resources.search',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/resources/post/{equipment?}',
                'method' => 'post',
                'action' => [HuaweiResourceController::class, 'storeProjectResource'],
                'permission' => true,
                'name' => 'huawei.projects.resources.store',
            ],
            [
                'uri' => 'huawei/projects/refund_resource/{huawei_resource}/{equipment?}',
                'method' => 'put',
                'action' => [HuaweiResourceController::class, 'refundResource'],
                'permission' => true,
                'name' => 'huawei.projects.refund',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/resources/search_details/{id}/post/{equipment?}',
                'method' => 'post',
                'action' => [HuaweiResourceController::class, 'searchEntryDetails'],
                'permission' => true,
                'name' => 'huawei.projects.resources.searchdetails',
            ],



            //liquidations
            [
                'uri' => 'huawei/projects/{huawei_project}/liquidations/get_resources',
                'method' => 'get',
                'action' => [HuaweiLiquidationController::class, 'geResourcesToLiquidate'],
                'permission' => true,
                'name' => 'huawei.projects.liquidations',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/liquidations/history/{equipment?}',
                'method' => 'get',
                'action' => [HuaweiLiquidationController::class, 'liquidationsHistory'],
                'permission' => true,
                'name' => 'huawei.projects.liquidations.history',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/liquidations/store_liquidation/{equipment?}',
                'method' => 'post',
                'action' => [HuaweiLiquidationController::class, 'liquidate'],
                'permission' => true,
                'name' => 'huawei.projects.liquidations.post',
            ],
            [
                'uri' => 'huawei/projects/{huawei_project}/liquidations/get_resources/search_advance/post',
                'method' => 'post',
                'action' => [HuaweiLiquidationController::class, 'search_advance_liquidate'],
                'permission' => true,
                'name' => 'huawei.projects.liquidations.searchadvance',
            ],
            [
                'uri' => 'huawei/projects/liquidations/massive_liquidation/post/liquidations/{equipment?}',
                'method' => 'post',
                'action' => [HuaweiLiquidationController::class, 'massiveLiquidation'],
                'permission' => true,
                'name' => 'huawei.projects.liquidations.massiveliquidation',
            ],



            //general_expenses
            [
                'uri' => 'huawei/projects/monthly_expenses/general_balance/get',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'getGeneralBalance'],
                'permission' => true,
                'name' => 'huawei.projects.generalbalance',
            ],
            [
                'uri' => 'huawei/projects/monthly_expenses/general_balance/expenses_by_zone/{expenseType}',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'getExpensesByZone'],
                'permission' => true,
                'name' => 'huawei.projects.generalbalance.expensesbyzone',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/get/{mode?}',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'getExpenses'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{request}/search/{mode?}',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'searchExpenses'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.search',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/search_advance/search/{mode?}',
                'method' => 'post',
                'action' => [HuaweiMonthlyController::class, 'searchAdvance'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.searchadvance',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/store/post',
                'method' => 'post',
                'action' => [HuaweiMonthlyController::class, 'storeExpense'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.store',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{expense}/update',
                'method' => 'post',
                'action' => [HuaweiMonthlyController::class, 'updateExpense'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.update',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{expense}/delete',
                'method' => 'delete',
                'action' => [HuaweiMonthlyController::class, 'deleteExpense'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.delete',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{expense}/validate',
                'method' => 'put',
                'action' => [HuaweiMonthlyController::class, 'validateExpense'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.validate',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{expense}/show_image',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'showImage'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.showimage',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/export/excel/{mode?}',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'exportMonthlyExpenses'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.export',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/massive_update/{mode}/post',
                'method' => 'post',
                'action' => [HuaweiMonthlyController::class, 'massiveUpdate'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.massiveupdate',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{macro}/fetch_sites/get',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'fetchSites'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.fetchsites',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/{macro}/{site_id}/fetch_projects/get',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'fetchProjects'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.fetchprojects',
            ],
            [
                'uri' => 'huawei/monthly_projects/general_expenses/import_costs/post',
                'method' => 'post',
                'action' => [HuaweiMonthlyController::class, 'importCosts'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.import',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/donwload_template/get',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'downloadTemplate'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.donwloadtemplate',
            ],
            [
                'uri' => 'huawei/projects/general_expenses/download_zip/{project_id}/{mode?}',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'downloadImages'],
                'permission' => true,
                'name' => 'huawei.projects.general.expenses.downloadzip',
            ],
            [
                'uri' => 'huawei/projects/create/fetch_sites/post',
                'method' => 'post',
                'action' => [HuaweiProjectController::class, 'fetchSites'],
                'permission' => false,
                'name' => 'huawei.projects.create.fetchsites',
            ],
            [
                'uri' => 'huawei/projects/monthly_expenses/download_images/post/{mode?}',
                'method' => 'get',
                'action' => [HuaweiMonthlyController::class, 'downloadImages'],
                'permission' => true,
                'name' => 'huawei.projects.monthlyexpenses.downloadimages',
            ],
        ];
    }
}
