<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\BacklogController;
use App\Http\Controllers\ProjectArea\CalendarController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\ProjectArea\LiquidationController;
use App\Http\Controllers\ProjectArea\MonthProjectController;
use App\Http\Controllers\ProjectArea\ProjectDocumentController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\ProjectPintController;
use App\Http\Controllers\ProjectArea\StaticCostsController;
use App\Http\Controllers\ProjectArea\ServicesLiquidationsController;

use App\Support\RouteDefinitions\Projects\AdministrativeRoutes;
use App\Support\RouteDefinitions\Projects\ChecklistRoutes;
use App\Support\RouteDefinitions\Projects\CustomersRoutes;
use App\Support\RouteDefinitions\Projects\PextRoutes;
use App\Support\RouteDefinitions\Projects\PreprojectRoutes;
use App\Support\RouteDefinitions\Projects\ProjectExpensesRoutes;
use App\Support\RouteDefinitions\Projects\ProRoutes;
use App\Support\RouteDefinitions\Projects\TasksRoutes;

class ProjectRoutes
{
    public static function all(): array
    {
        return array_merge(
            ProjectExpensesRoutes::all(),
            ChecklistRoutes::all(),
            CustomersRoutes::all(),
            TasksRoutes::all(),
            AdministrativeRoutes::all(),
            PextRoutes::all(),
            ProRoutes::all(),
            PreprojectRoutes::all(),
            [

                [
                    'uri' => 'projectmanagement/update/{project_id}/type/{type?}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_create'],
                    'permission' => true,
                    'name' => 'projectmanagement.update',
                ],
                [
                    'uri' => 'projectmanagement/delete/{project_id}',
                    'method' => 'delete',
                    'action' => [ProjectManagementController::class, 'project_destroy'],
                    'permission' => true,
                    'name' => 'projectmanagement.delete',
                ],
                [
                    'uri' => 'project/update/delete-employee/{pivot_id}',
                    'method' => 'delete',
                    'action' => [ProjectManagementController::class, 'project_delete_employee'],
                    'permission' => true,
                    'name' => 'projectmanagement.delete.employee',
                ],
                [
                    'uri' => 'cicsaSubSections/{subSection}/update',
                    'method' => 'put',
                    'action' => [CicsaSectionController::class, 'updateSubSection'],
                    'permission' => true,
                    'name' => 'sections.cicsaUpdateSubSection',
                ],
                [
                    'uri' => 'cicsaSubSections/{subSection}/delete',
                    'method' => 'delete',
                    'action' => [CicsaSectionController::class, 'destroySubSection'],
                    'permission' => true,
                    'name' => 'sections.cicsaDestroySubSection',
                ],
                [
                    'uri' => 'cicsaSections/{section}',
                    'method' => 'delete',
                    'action' => [CicsaSectionController::class, 'destroySection'],
                    'permission' => true,
                    'name' => 'sections.cicsaDestroySection',
                ],



                // Project
                [
                    'uri' => 'project/create',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_create'],
                    'permission' => true,
                    'name' => 'projectmanagement.create',
                ],
                [
                    'uri' => 'project/store',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'project_store'],
                    'permission' => true,
                    'name' => 'projectmanagement.store',
                ],
                [
                    'uri' => 'project/update/{project_id}/add-employee',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'project_add_employee'],
                    'permission' => true,
                    'name' => 'projectmanagement.add.employee',
                ],
                [
                    'uri' => 'project/liquidation',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'liquidate_project'],
                    'permission' => true,
                    'name' => 'projectmanagement.liquidation',
                ],


                // Liquidación de recursos
                [
                    'uri' => '/project/resources_liquidation',
                    'method' => 'post',
                    'action' => [ServicesLiquidationsController::class, 'store'],
                    'permission' => true,
                    'name' => 'projectmanagement.resources.liquidate',
                ],

                // Solicitudes de compra
                [
                    'uri' => '/project/purchases_request/{project_id}/create/{purchase_id?}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_purchases_request_create'],
                    'permission' => true,
                    'name' => 'projectmanagement.purchases_request.create',
                ],
                [
                    'uri' => '/project/purchases_request/{project_id}/store',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'project_purchases_request_store'],
                    'permission' => true,
                    'name' => 'projectmanagement.purchases_request.store',
                ],
                [
                    'uri' => '/project/{project_id?}/purchases_request/edit/{id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_purchases_request_edit'],
                    'permission' => true,
                    'name' => 'projectmanagement.purchases_request.edit',
                ],

                // Costos adicionales y estáticos
                [
                    'uri' => '/project/purchases_request/{project_id}/additional_costs',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'store'],
                    'permission' => true,
                    'name' => 'projectmanagement.storeAdditionalCost',
                ],
                [
                    'uri' => '/project/additional_costs/import/{project_id}',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'import'],
                    'permission' => true,
                    'name' => 'projectmanagement.importAdditionalCost',
                ],
                [
                    'uri' => '/project/purchases_request/{project_id}/static_costs',
                    'method' => 'post',
                    'action' => [StaticCostsController::class, 'store'],
                    'permission' => true,
                    'name' => 'projectmanagement.storeStaticCost',
                ],
                [
                    'uri' => '/project/additional_cost/validate/{ac_id}',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'validateRegister'],
                    'permission' => true,
                    'name' => 'projectmanagement.validateAdditionalCost',
                ],
                [
                    'uri' => '/project/expenses/{project_id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_expenses'],
                    'permission' => true,
                    'name' => 'projectmanagement.expenses',
                ],

                // Datos del proyecto
                [
                    'uri' => '/project_last12_utilitites/{project_id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'projects_year_profit'],
                    'permission' => true,
                    'name' => 'projectmanagement.last12.utilities',
                ],
                [
                    'uri' => '/project_zone_expenses_chart/{project_id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_zone_expenses'],
                    'permission' => true,
                    'name' => 'projectmanagement.zoneexpenses.chart',
                ],

                // Descarga de imágenes en ZIP
                [
                    'uri' => '/descargar_zip_add/{project_id}',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'downloadImages'],
                    'permission' => true,
                    'name' => 'zip.additional.descargar',
                ],
                [
                    'uri' => '/descargar_zip_static/{project_id}',
                    'method' => 'get',
                    'action' => [StaticCostsController::class, 'downloadImages'],
                    'permission' => true,
                    'name' => 'zip.static.descargar',
                ],
                // Acciones masivas de costos
                [
                    'uri' => '/project/additional_costs_massive_update/',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'masiveUpdate'],
                    'permission' => true,
                    'name' => 'projectmanagement.additionalCosts.massiveUpdate',
                ],
                [
                    'uri' => '/project/static_costs_massive_swap/',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'swapCosts'],
                    'permission' => true,
                    'name' => 'projectmanagement.additionalCosts.swapCosts',
                ],
                [
                    'uri' => '/project/additionals_to_addproject_massive_swap/',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'swapCostsToAdditionalProject'],
                    'permission' => true,
                    'name' => 'projectmanagement.addctoaddproject.swapCosts',
                ],
                [
                    'uri' => '/project/static_costs_massive_update/',
                    'method' => 'post',
                    'action' => [StaticCostsController::class, 'masiveUpdate'],
                    'permission' => true,
                    'name' => 'projectmanagement.staticCosts.massiveUpdate',
                ],

                [
                    'uri' => '/project_get_regular_projects',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'getRegularProjects'],
                    'permission' => true,
                    'name' => 'projectmanagement.regularprojects.all',
                ],
                [
                    'uri' => '/project_swap_regular_projects',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'swapCostsToRegularProject'],
                    'permission' => true,
                    'name' => 'projectmanagement.regularproject.swapCosts',
                ],

                // Producto del proyecto
                [
                    'uri' => '/project/warehouse_products/{project}/{warehouse}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'warehouse_products'],
                    'permission' => true,
                    'name' => 'projectmanagement.warehouse_products',
                ],
                [
                    'uri' => '/project/inventory_products/{inventory}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'inventory_products'],
                    'permission' => true,
                    'name' => 'projectmanagement.inventory_products',
                ],
                [
                    'uri' => '/project/products/store',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'project_product_store'],
                    'permission' => true,
                    'name' => 'projectmanagement.products.store',
                ],
                [
                    'uri' => '/project/products/update/{project_product}',
                    'method' => 'put',
                    'action' => [ProjectManagementController::class, 'project_product_update'],
                    'permission' => true,
                    'name' => 'projectmanagement.products.update',
                ],
                [
                    'uri' => '/project/warehouse_products/{assigned}',
                    'method' => 'delete',
                    'action' => [ProjectManagementController::class, 'warehouse_products_delete'],
                    'permission' => true,
                    'name' => 'projectmanagement.products.delete',
                ],
                [
                    'uri' => '/project/purchases_request/update/due_date',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'project_purchases_request_update_due_date'],
                    'permission' => true,
                    'name' => 'projectmanagement.update_due_date',
                ],

                // Liquidación del proyecto
                [
                    'uri' => '/project/products/{project_id}/{project_entry}',
                    'method' => 'get',
                    'action' => [LiquidationController::class, 'liquidateForm'],
                    'permission' => true,
                    'name' => 'projectmanagement.liquidate.form',
                ],
                [
                    'uri' => '/project/products/{project_id}/{project_entry}/liquidate',
                    'method' => 'post',
                    'action' => [LiquidationController::class, 'liquidate'],
                    'permission' => true,
                    'name' => 'projectmanagement.liquidate.post',
                ],

                // Miembros de las secciones Cicsa
                [
                    'uri' => '/cicsa/member/store',
                    'method' => 'post',
                    'action' => [CicsaSectionController::class, 'storeSubSection'],
                    'permission' => true,
                    'name' => 'sections.cicsa.member.store',
                ],
                [
                    'uri' => '/cicsa/store',
                    'method' => 'post',
                    'action' => [CicsaSectionController::class, 'storeSection'],
                    'permission' => true,
                    'name' => 'sections.cicsa.section.store',
                ],

                // Gestión de documentos del proyecto
                [
                    'uri' => '/project-document-gestion',
                    'method' => 'get',
                    'action' => [ProjectDocumentController::class, 'project_doc_index'],
                    'permission' => true,
                    'name' => 'project.document.index',
                ],
                [
                    'uri' => '/project-document-gestion/store/{path?}',
                    'method' => 'post',
                    'action' => [ProjectDocumentController::class, 'project_doc_store'],
                    'permission' => true,
                    'name' => 'project.document.store',
                ],
                [
                    'uri' => '/project-document-gestion/folder_archive_delete',
                    'method' => 'post',
                    'action' => [ProjectDocumentController::class, 'project_doc_delete'],
                    'permission' => true,
                    'name' => 'project.folder.delete',
                ],
                [
                    'uri' => '/project-document-gestion/folder_archive_dowload',
                    'method' => 'get',
                    'action' => [ProjectDocumentController::class, 'project_doc_download'],
                    'permission' => true,
                    'name' => 'project.folder.download',
                ],

                // Backlog del proyecto
                [
                    'uri' => '/project-backlog',
                    'method' => 'get',
                    'action' => [BacklogController::class, 'index'],
                    'permission' => true,
                    'name' => 'project.backlog.index',
                ],
                [
                    'uri' => '/project-backlog/autcomplete',
                    'method' => 'get',
                    'action' => [BacklogController::class, 'autocomplete'],
                    'permission' => true,
                    'name' => 'project.backlog.autocomplete',
                ],
                [
                    'uri' => '/project-backlog/store',
                    'method' => 'post',
                    'action' => [BacklogController::class, 'store'],
                    'permission' => true,
                    'name' => 'project.backlog.store',
                ],
                [
                    'uri' => '/project-backlog/delete/{backlog_id}',
                    'method' => 'delete',
                    'action' => [BacklogController::class, 'destroy'],
                    'permission' => true,
                    'name' => 'project.backlog.destroy',
                ],
                // Proyectos administrativos de costos
                [
                    'uri' => '/monthProjects',
                    'method' => 'get',
                    'action' => [MonthProjectController::class, 'index'],
                    'permission' => true,
                    'name' => 'monthproject.index',
                ],
                [
                    'uri' => '/monthProjects_store/{mp_id?}',
                    'method' => 'post',
                    'action' => [MonthProjectController::class, 'store'],
                    'permission' => true,
                    'name' => 'monthproject.store',
                ],
                [
                    'uri' => '/monthProjects_destroy/{mp_id}',
                    'method' => 'delete',
                    'action' => [MonthProjectController::class, 'destroy'],
                    'permission' => true,
                    'name' => 'monthproject.destroy',
                ],



                // Project
                [
                    'uri' => '/project',
                    'method' => 'any',
                    'action' => [ProjectManagementController::class, 'index'],
                    'permission' => true,
                    'name' => 'projectmanagement.index',
                ],
                [
                    'uri' => '/project/historial',
                    'method' => 'any',
                    'action' => [ProjectManagementController::class, 'historial'],
                    'permission' => true,
                    'name' => 'projectmanagement.historial',
                ],

                // Project calendar
                [
                    'uri' => '/calendarProjects',
                    'method' => 'get',
                    'action' => [CalendarController::class, 'index'],
                    'permission' => true,
                    'name' => 'projectscalendar.index',
                ],
                [
                    'uri' => '/calendarTasks/{project}',
                    'method' => 'get',
                    'action' => [CalendarController::class, 'show'],
                    'permission' => true,
                    'name' => 'projectscalendar.show',
                ],

                // Project resources
                [
                    'uri' => '/project/resources/{project_id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_resources'],
                    'permission' => true,
                    'name' => 'projectmanagement.resources',
                ],

                // Project purchase request
                [
                    'uri' => '/project/purchases_request/{project_id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_purchases_request_index'],
                    'permission' => true,
                    'name' => 'projectmanagement.purchases_request.index',
                ],
                [
                    'uri' => '/project/purchases_request/details/{id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_purchases_request_details'],
                    'permission' => true,
                    'name' => 'projectmanagement.purchases_request.details',
                ],
                [
                    'uri' => '/project/purchases_request_index/{project_id}/additional_costs',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'index'],
                    'permission' => true,
                    'name' => 'projectmanagement.additionalCosts',
                ],
                [
                    'uri' => '/project/purchases_request_getAdditionalCost/{project_id}',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'getAdditionalCost'],
                    'permission' => true,
                    'name' => 'projectmanagement.getAdditionalCost',
                ],
                [
                    'uri' => '/project/purchases_request/{project_id}/additional_costs/rejected',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'indexRejected'],
                    'permission' => true,
                    'name' => 'projectmanagement.additionalCosts.rejected',
                ],
                [
                    'uri' => '/additionalcost_advancesearch/{project_id}',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'search_costs'],
                    'permission' => true,
                    'name' => 'additionalcost.advance.search',
                ],
                [
                    'uri' => '/project/additional_Projects',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'additionalProjects'],
                    'permission' => true,
                    'name' => 'additionalcost.additionalProjects',
                ],

                // Static Costs
                [
                    'uri' => '/project/purchases_request/{project_id}/static_costs',
                    'method' => 'get',
                    'action' => [StaticCostsController::class, 'index'],
                    'permission' => true,
                    'name' => 'projectmanagement.staticCosts',
                ],
                [
                    'uri' => '/staticcost_photo/{static_cost_id}',
                    'method' => 'get',
                    'action' => [StaticCostsController::class, 'download_ac_photo'],
                    'permission' => true,
                    'name' => 'staticcost.archive',
                ],
                [
                    'uri' => '/staticcost_advancesearch/{project_id}',
                    'method' => 'post',
                    'action' => [StaticCostsController::class, 'search_costs'],
                    'permission' => true,
                    'name' => 'staticcost.advance.search',
                ],
                [
                    'uri' => '/ad_st_costs_details',
                    'method' => 'post',
                    'action' => [ProjectManagementController::class, 'project_expense_details'],
                    'permission' => true,
                    'name' => 'project.expenses.zones.details',
                ],

                // Project product
                [
                    'uri' => '/project/products/{project_id}',
                    'method' => 'get',
                    'action' => [ProjectManagementController::class, 'project_product_index'],
                    'permission' => true,
                    'name' => 'projectmanagement.products',
                ],

                // Project liquidate
                [
                    'uri' => '/project/{project_id}/products/liquidate',
                    'method' => 'get',
                    'action' => [LiquidationController::class, 'index'],
                    'permission' => true,
                    'name' => 'projectmanagement.liquidate',
                ],
                [
                    'uri' => '/project/{project_id}/product/liquidateHistory',
                    'method' => 'get',
                    'action' => [LiquidationController::class, 'history'],
                    'permission' => true,
                    'name' => 'projectmanagement.liquidate.history',
                ],

                // Member Cicsa Sections
                [
                    'uri' => '/member/cicsa',
                    'method' => 'get',
                    'action' => [CicsaSectionController::class, 'showSubSections'],
                    'permission' => true,
                    'name' => 'member.cicsa',
                ],
                [
                    'uri' => '/member/cicsa/show/{subSection}',
                    'method' => 'get',
                    'action' => [CicsaSectionController::class, 'showSubSection'],
                    'permission' => true,
                    'name' => 'member.cicsa.show',
                ],
                [
                    'uri' => '/member/cicsa/sections',
                    'method' => 'get',
                    'action' => [CicsaSectionController::class, 'showSections'],
                    'permission' => true,
                    'name' => 'cicsa.sections',
                ],
                [
                    'uri' => '/sections/cicsa/alarm',
                    'method' => 'get',
                    'action' => [CicsaSectionController::class, 'alarm'],
                    'permission' => true,
                    'name' => 'member.cicsa.alarm',
                ],
                // Pint auto
                [
                    'uri' => '/preproject/auto-create/index/{type}',
                    'method' => 'get',
                    'action' => [ProjectPintController::class, 'pint_create_project'],
                    'permission' => true,
                    'name' => 'project.auto.pint',
                ],
                [
                    'uri' => '/preproject/auto-create/search_employees/{cc_id}',
                    'method' => 'get',
                    'action' => [ProjectPintController::class, 'getEmployees'],
                    'permission' => true,
                    'name' => 'project.auto.pint.getEmployees',
                ],
                [
                    'uri' => '/preproject/auto-store/pint',
                    'method' => 'post',
                    'action' => [ProjectPintController::class, 'pint_store_project'],
                    'permission' => true,
                    'name' => 'project.auto_store.pint',
                ],
                [
                    'uri' => '/product-CPE/pint',
                    'method' => 'post',
                    'action' => [ProjectPintController::class, 'sameCPEProducts'],
                    'permission' => true,
                    'name' => 'pint_project.products.cpe',
                ],

                // Pext auto
                [
                    'uri' => '/preproject/auto-create/pext/{type}',
                    'method' => 'get',
                    'action' => [ProjectPintController::class, 'pext_create_project'],
                    'permission' => true,
                    'name' => 'project.auto.pext',
                ],
                [
                    'uri' => '/preproject/auto-store/pext',
                    'method' => 'post',

                    'action' => [ProjectPintController::class, 'pext_store_project'],
                    'permission' => true,
                    'name' => 'project.auto_store.pext',
                ],
                [
                    'uri' => '/product-CPE/pext',
                    'method' => 'post',
                    'action' => [ProjectPintController::class, 'sameCPEProductsPext'],
                    'permission' => true,
                    'name' => 'pext_project.products.cpe',
                ],

                // Costs Export
                [
                    'uri' => '/additionalcost_photo/{additional_cost_id}',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'download_ac_photo'],
                    'permission' => true,
                    'name' => 'additionalcost.archive',
                ],
                [
                    'uri' => '/additionalcosts/excel_export/{project_id}',
                    'method' => 'get',
                    'action' => [AdditionalCostsController::class, 'export'],
                    'permission' => true,
                    'name' => 'additionalcost.excel.export',
                ],

                // Update Additional Cost
                [
                    'uri' => '/projectmanagement/purchases_request/additional_costs/{additional_cost}/update',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'update'],
                    'permission' => true,
                    'name' => 'projectmanagement.updateAdditionalCost',
                ],

                // Delete Additional Cost
                [
                    'uri' => '/projectmanagement/purchases_request/additional_costs/{additional_cost}/destroy',
                    'method' => 'delete',
                    'action' => [AdditionalCostsController::class, 'destroy'],
                    'permission' => true,
                    'name' => 'projectmanagement.deleteAdditionalCost',
                ],

                // Export Static Costs
                [
                    'uri' => '/staticcosts/excel_export/{project_id}',
                    'method' => 'get',
                    'action' => [StaticCostsController::class, 'export'],
                    'permission' => true,
                    'name' => 'staticcost.excel.export',
                ],

                // Update Static Cost
                [
                    'uri' => '/projectmanagement/purchases_request/static_costs/{additional_cost}/update',
                    'method' => 'post',
                    'action' => [StaticCostsController::class, 'update'],
                    'permission' => true,
                    'name' => 'projectmanagement.updateStaticCost',
                ],

                // Delete Static Cost
                [
                    'uri' => '/projectmanagement/purchases_request/{project_id}/static_costs/{additional_cost}/destroy',
                    'method' => 'delete',
                    'action' => [StaticCostsController::class, 'destroy'],
                    'permission' => true,
                    'name' => 'projectmanagement.deleteStaticCost',
                ],

                //Swap
                [
                    'uri' => '/project/statics_to_addproject_massive_swap/',
                    'method' => 'post',
                    'action' => [StaticCostsController::class, 'swapCostsToAdditionalProject'],
                    'permission' => true,
                    'name' => 'projectmanagement.statitoaddproject.swapCosts',
                ],
                [
                    'uri' => '/project/additional_cost/reject/{ac_id}',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'rejectRegisters'],
                    'permission' => true,
                    'name' => 'projectmanagement.rejectAdditionalCost',
                ],
                [
                    'uri' => '/project/additional_cost/admin_validate/{ac_id}',
                    'method' => 'post',
                    'action' => [AdditionalCostsController::class, 'administrativeValidateRegisters'],
                    'permission' => true,
                    'name' => 'projectmanagement.administrative.validation',
                ],
            ]
        );
    }
}
