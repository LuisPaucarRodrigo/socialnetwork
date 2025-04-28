<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\ProjectArea\AdditionalCostsController;
use App\Http\Controllers\ProjectArea\BacklogController;
use App\Http\Controllers\ProjectArea\CalendarController;
use App\Http\Controllers\ProjectArea\ChecklistsController;
use App\Http\Controllers\ProjectArea\CicsaSectionController;
use App\Http\Controllers\ProjectArea\CustomersController;
use App\Http\Controllers\ProjectArea\LiquidationController;
use App\Http\Controllers\ProjectArea\MonthProjectController;
use App\Http\Controllers\ProjectArea\PextController;
use App\Http\Controllers\ProjectArea\PreProjectController;
use App\Http\Controllers\ProjectArea\ProjectDocumentController;
use App\Http\Controllers\ProjectArea\ProjectManagementController;
use App\Http\Controllers\ProjectArea\ProjectPintController;
use App\Http\Controllers\ProjectArea\StaticCostsController;
use App\Http\Controllers\ProjectArea\TaskManagementController;
use App\Http\Controllers\ProjectArea\ServicesLiquidationsController;
use App\Http\Controllers\ProjectArea\AdministrativeCostsController;



class ProjectRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'preprojects/{preproject}/update',
                'method' => 'post',
                'action' => [PreProjectController::class, 'update'],
                'permission' => true,
                'name' => 'preprojects.update',
            ],
            [
                'uri' => 'preprojects/{preproject}/destroy',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'destroy'],
                'permission' => true,
                'name' => 'preprojects.destroy',
            ],
            [
                'uri' => 'preprojects/photoreport_update/{photoreport}',
                'method' => 'post',
                'action' => [PreProjectController::class, 'photoreport_update'],
                'permission' => true,
                'name' => 'preprojects.photoreport.update',
            ],
            [
                'uri' => 'preprojects/photoreport_delete/{photoreport}',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'photoreport_delete'],
                'permission' => true,
                'name' => 'preprojects.photoreport.delete',
            ],
            [
                'uri' => 'preprojects/providers_quotes/delete/{providerquote_id}',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'preproject_providersquotes_delete'],
                'permission' => true,
                'name' => 'preprojects.providersquotes.delete',
            ],
            [
                'uri' => 'preprojects/quote_item_delete',
                'method' => 'post',
                'action' => [PreProjectController::class, 'quote_item_delete'],
                'permission' => true,
                'name' => 'preprojects.quote.item.delete',
            ],
            [
                'uri' => 'preprojects/quote_item_store',
                'method' => 'post',
                'action' => [PreProjectController::class, 'quote_item_store'],
                'permission' => true,
                'name' => 'preprojects.quote.item.store',
            ],
            [
                'uri' => 'preprojects/quote_product_store',
                'method' => 'post',
                'action' => [PreProjectController::class, 'quote_product_store'],
                'permission' => true,
                'name' => 'preprojects.quote.product.store',
            ],
            [
                'uri' => 'preprojects/quote_product_delete/{quote_product_id}',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'quote_product_delete'],
                'permission' => true,
                'name' => 'preprojects.quote.product.delete',
            ],
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
                'uri' => 'edittask/delete',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'delete_employee'],
                'permission' => true,
                'name' => 'tasks.delete.employee',
            ],
            [
                'uri' => 'deletetask/{taskId}',
                'method' => 'delete',
                'action' => [TaskManagementController::class, 'delete_task'],
                'permission' => true,
                'name' => 'tasks.delete',
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
            // Customers
            [
                'uri' => 'customers/post',
                'method' => 'post',
                'action' => [CustomersController::class, 'store'],
                'permission' => true,
                'name' => 'customers.store',
            ],
            [
                'uri' => 'customers/{customer}/update',
                'method' => 'put',
                'action' => [CustomersController::class, 'update'],
                'permission' => true,
                'name' => 'customers.update',
            ],
            [
                'uri' => 'customers/{customer}/destroy',
                'method' => 'delete',
                'action' => [CustomersController::class, 'destroy'],
                'permission' => true,
                'name' => 'customers.destroy',
            ],

            // Customers contacts
            [
                'uri' => 'customers/{customer}/contacts',
                'method' => 'get',
                'action' => [CustomersController::class, 'show_contacts'],
                'permission' => true,
                'name' => 'customers.contacts.index',
            ],
            [
                'uri' => 'customers/{customer}/contacts/post',
                'method' => 'post',
                'action' => [CustomersController::class, 'store_contact'],
                'permission' => true,
                'name' => 'customers.contacts.store',
            ],
            [
                'uri' => 'customers/{customer}/contacts/{customer_contact}/update',
                'method' => 'put',
                'action' => [CustomersController::class, 'update_contact'],
                'permission' => true,
                'name' => 'customers.contacts.update',
            ],
            [
                'uri' => 'customers/{customer}/contacts/{customer_contact}/destroy',
                'method' => 'delete',
                'action' => [CustomersController::class, 'destroy_contact'],
                'permission' => true,
                'name' => 'customers.contacts.destroy',
            ],

            // Preproject
            [
                'uri' => 'preprojects/create/{type}/{preproject_id?}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'create'],
                'permission' => true,
                'name' => 'preprojects.create',
            ],
            [
                'uri' => 'preprojects/store',
                'method' => 'post',
                'action' => [PreProjectController::class, 'store'],
                'permission' => true,
                'name' => 'preprojects.store',
            ],

            // Assign users
            [
                'uri' => 'preprojects/assignUser',
                'method' => 'post',
                'action' => [PreProjectController::class, 'preproject_users'],
                'permission' => true,
                'name' => 'preprojects.assign.users',
            ],

            // Preproject images
            [
                'uri' => 'preprojects/report/stages/store/{preproject_id}',
                'method' => 'put',
                'action' => [PreProjectController::class, 'stages_store'],
                'permission' => true,
                'name' => 'preprojects.stages.store',
            ],
            [
                'uri' => 'preprojects/report/stages/{title_id}/delete',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'delete_stages'],
                'permission' => true,
                'name' => 'preprojects.stages.delete',
            ],
            [
                'uri' => 'preprojects/{preproject_image_id}/report/image',
                'method' => 'put',
                'action' => [PreProjectController::class, 'approve_reject_image'],
                'permission' => true,
                'name' => 'preprojects.imagereport.approveReject',
            ],
            [
                'uri' => 'preprojects/{preproject_code_id}/codereport',
                'method' => 'get',
                'action' => [PreProjectController::class, 'approve_code'],
                'permission' => true,
                'name' => 'preprojects.codereport.approveCode',
            ],
            [
                'uri' => 'preprojects/{preproject_title_id}/titlereport',
                'method' => 'get',
                'action' => [PreProjectController::class, 'approve_title'],
                'permission' => true,
                'name' => 'preprojects.codereport.approveTitle',
            ],
            [
                'uri' => 'preprojects/{code_id}/approve_images',
                'method' => 'get',
                'action' => [PreProjectController::class, 'approve_images'],
                'permission' => true,
                'name' => 'preprojects.codereport.approveImages',
            ],
            [
                'uri' => 'preprojects/{preproject_id}/report/delete',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'delete_image'],
                'permission' => true,
                'name' => 'preprojects.imagereport.delete',
            ],
            [
                'uri' => 'preprojects/{preproject_title_id}/download/kmz',
                'method' => 'get',
                'action' => [PreProjectController::class, 'downloadKmz'],
                'permission' => true,
                'name' => 'preprojects.download.kmz',
            ],

            // Photographic report
            [
                'uri' => 'preprojects/photoreport_store',
                'method' => 'post',
                'action' => [PreProjectController::class, 'photoreport_store'],
                'permission' => true,
                'name' => 'preprojects.photoreport.store',
            ],
            // Preproject products
            [
                'uri' => 'preprojects/products/post',
                'method' => 'post',
                'action' => [PreProjectController::class, 'preproject_product_store'],
                'permission' => true,
                'name' => 'preprojects.products.store',
            ],
            [
                'uri' => 'preprojects/products/{warehouse_id}/getWarehouse',
                'method' => 'get',
                'action' => [PreProjectController::class, 'preproject_warehouse_products'],
                'permission' => true,
                'name' => 'preprojects.warehouse_products',
            ],
            [
                'uri' => 'preprojects/products/{inventory_id}/getInventory',
                'method' => 'get',
                'action' => [PreProjectController::class, 'preproject_inventory_products'],
                'permission' => true,
                'name' => 'preprojects.inventory_products',
            ],

            // Preproject purchase request
            [
                'uri' => 'preprojects/request/{id}/shopping',
                'method' => 'get',
                'action' => [PreProjectController::class, 'request_shopping_create'],
                'permission' => true,
                'name' => 'preprojects.request.create',
            ],
            [
                'uri' => 'preprojects/request/store/shopping',
                'method' => 'post',
                'action' => [PreProjectController::class, 'request_shopping_store'],
                'permission' => true,
                'name' => 'preprojects.request.store',
            ],
            [
                'uri' => 'preprojects/request/{id}/edit',
                'method' => 'get',
                'action' => [PreProjectController::class, 'request_shopping_edit'],
                'permission' => true,
                'name' => 'preprojects.request.edit',
            ],
            [
                'uri' => 'preprojects/request/{id}/update',
                'method' => 'put',
                'action' => [PreProjectController::class, 'request_shopping_update'],
                'permission' => true,
                'name' => 'preprojects.request.update',
            ],
            [
                'uri' => 'preprojects/purchase_quote/accept_decline/{purchase_quote_id}',
                'method' => 'post',
                'action' => [PreProjectController::class, 'accept_decline_quote'],
                'permission' => true,
                'name' => 'preprojects.purchase_quote.accept_decline',
            ],

            // Preproject quote
            [
                'uri' => 'preprojects/{preproject_id}/quote',
                'method' => 'get',
                'action' => [PreProjectController::class, 'quote'],
                'permission' => true,
                'name' => 'preprojects.quote',
            ],
            [
                'uri' => 'preprojects/quote/oficially/rejected',
                'method' => 'post',
                'action' => [PreProjectController::class, 'preproject_quote_rejected'],
                'permission' => true,
                'name' => 'preproject_quote.rejected',
            ],
            [
                'uri' => 'preprojects/quote/oficially/canceled',
                'method' => 'post',
                'action' => [PreProjectController::class, 'preproject_quote_canceled'],
                'permission' => true,
                'name' => 'preproject_quote.canceled',
            ],
            [
                'uri' => 'cotizationPDF/{preproject}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'getPDF'],
                'permission' => true,
                'name' => 'preprojects.pdf',
            ],
            [
                'uri' => 'preprojects/quote/{quote_id}',
                'method' => 'post',
                'action' => [PreProjectController::class, 'acceptCotization'],
                'permission' => true,
                'name' => 'preprojects.accept',
            ],
            [
                'uri' => 'preprojects/quote_store/{quote_id?}',
                'method' => 'post',
                'action' => [PreProjectController::class, 'quote_store'],
                'permission' => true,
                'name' => 'preprojects.quote.store',
            ],

            [
                'uri' => 'preprojects/load_resource_entries/{service_id}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'load_resource_entries'],
                'permission' => true,
                'name' => 'load.resource_entries',
            ],

            [
                'uri' => 'projectPext/storeProjectAndAssignation',
                'method' => 'post',
                'action' => [PextController::class, 'storeProjectAndAssignation'],
                'permission' => true,
                'name' => 'projectmanagement.pext.storeProjectAndAssignation',
            ],


            [
                'uri' => 'projectPext/expenses/storeOrUpdate/{expense_id?}',
                'method' => 'post',
                'action' => [PextController::class, 'expenses_storeOrUpdate'],
                'permission' => true,
                'name' => 'pext.expenses.storeOrUpdate',
            ],
            [
                'uri' => 'projectPext/expenses/delete/{expense_id}',
                'method' => 'delete',
                'action' => [PextController::class, 'expenses_delete'],
                'permission' => true,
                'name' => 'pext.expenses.delete',
            ],
            [
                'uri' => 'projectPext/expenses/expenseValidate/{expense_id}',
                'method' => 'put',
                'action' => [PextController::class, 'expense_validate'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.validate',
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
            // Tareas del proyecto
            [
                'uri' => '/project/task/create/{project_id?}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'create'],
                'permission' => true,
                'name' => 'tasks.create',
            ],
            [
                'uri' => '/projecmanagement/task/store',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'store'],
                'permission' => true,
                'name' => 'tasks.store',
            ],
            [
                'uri' => '/projecmanagement/task/add/comment',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'comment'],
                'permission' => true,
                'name' => 'tasks.add.comment',
            ],
            [
                'uri' => '/projecmanagement/task/add/employee',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'add_employee'],
                'permission' => true,
                'name' => 'tasks.add.employee',
            ],
            [
                'uri' => '/statustask/{taskId}/{status}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'status_task'],
                'permission' => true,
                'name' => 'tasks.edit.status',
            ],
            [
                'uri' => '/tasks/duplicated',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'task_duplicated'],
                'permission' => true,
                'name' => 'tasks.duplicated',
            ],
            [
                'uri' => '/tasks/edit/date',
                'method' => 'post',
                'action' => [TaskManagementController::class, 'task_edit_date'],
                'permission' => true,
                'name' => 'tasks.edit.date',
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

            // Códigos de preproyecto
            [
                'uri' => '/preprojects/codes/post',
                'method' => 'post',
                'action' => [PreProjectController::class, 'storeCode'],
                'permission' => true,
                'name' => 'preprojects.codes.post',
            ],
            [
                'uri' => '/preprojects/codes/{code}/put',
                'method' => 'put',
                'action' => [PreProjectController::class, 'updateCode'],
                'permission' => true,
                'name' => 'preprojects.codes.put',
            ],
            [
                'uri' => '/preprojects/codes/{code}/delete',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'deleteCode'],
                'permission' => true,
                'name' => 'preprojects.codes.delete',
            ],
            [
                'uri' => '/preprojects/codes/{code_id}/images/show',
                'method' => 'get',
                'action' => [PreProjectController::class, 'indexImages'],
                'permission' => true,
                'name' => 'preprojects.code.images.index',
            ],
            [
                'uri' => '/preprojects/codes/images/store',
                'method' => 'post',
                'action' => [PreProjectController::class, 'storeCodeImages'],
                'permission' => true,
                'name' => 'preprojects.code.images.store',
            ],
            [
                'uri' => '/preprojects/codes/images/{image_id}/show',
                'method' => 'get',
                'action' => [PreProjectController::class, 'show_code_image'],
                'permission' => true,
                'name' => 'preprojects.code.images.show',
            ],
            [
                'uri' => '/preprojects/codes/images/{image_id}/delete',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'deleteCodeImages'],
                'permission' => true,
                'name' => 'preprojects.code.images.delete',
            ],
            // Títulos
            [
                'uri' => '/preprojects/titles/post',
                'method' => 'post',
                'action' => [PreProjectController::class, 'postTitle'],
                'permission' => true,
                'name' => 'preprojects.titles.post',
            ],
            [
                'uri' => '/preprojects/titles/{title}/put',
                'method' => 'put',
                'action' => [PreProjectController::class, 'putTitle'],
                'permission' => true,
                'name' => 'preprojects.titles.put',
            ],
            [
                'uri' => '/preprojects/titles/{title}/delete',
                'method' => 'delete',
                'action' => [PreProjectController::class, 'deleteTitle'],
                'permission' => true,
                'name' => 'preprojects.titles.delete',
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
            // Checklist
            [
                'uri' => '/checklist',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'index'],
                'permission' => true,
                'name' => 'checklist.index',
            ],
            [
                'uri' => '/checklist/car',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'car_index'],
                'permission' => true,
                'name' => 'checklist.car.index',
            ],
            [
                'uri' => '/checklist/car/{id}/{photoProp}',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'car_photo'],
                'permission' => true,
                'name' => 'checklist.car.photo',
            ],
            [
                'uri' => '/checklist/toolkit/{id}/{photoProp}',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'toolkit_photo'],
                'permission' => true,
                'name' => 'checklist.toolkit.photo',
            ],
            [
                'uri' => '/checklist/dailytoolkit',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'dailytoolkit_index'],
                'permission' => true,
                'name' => 'checklist.dailytoolkit.index',
            ],
            [
                'uri' => '/checklist/epp',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'epp_index'],
                'permission' => true,
                'name' => 'checklist.epp.index',
            ],
            [
                'uri' => '/checklist/toolkit',
                'method' => 'get',
                'action' => [ChecklistsController::class, 'toolkit_index'],
                'permission' => true,
                'name' => 'checklist.toolkit.index',
            ],
            [
                'uri' => '/checklist/car/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'car_destroy'],
                'permission' => true,
                'name' => 'checklist.car.destroy',
            ],
            [
                'uri' => '/checklist/toolkit/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'toolkit_destroy'],
                'permission' => true,
                'name' => 'checklist.toolkit.destroy',
            ],
            [
                'uri' => '/checklist/dailytoolkit/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'dailytoolkit_destroy'],
                'permission' => true,
                'name' => 'checklist.dailytoolkit.destroy',
            ],
            [
                'uri' => '/checklist/epp/{id}/destroy',
                'method' => 'delete',
                'action' => [ChecklistsController::class, 'epp_destroy'],
                'permission' => true,
                'name' => 'checklist.epp.destroy',
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
            [
                'uri' => '/project/purchases_request/{month_project_id}/administrative_costs',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'store'],
                'permission' => true,
                'name' => 'projectmanagement.storeAdministrativeCost',
            ],
            [
                'uri' => '/descargar_zip_administrative/{month_project_id}',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'downloadImages'],
                'permission' => true,
                'name' => 'zip.administrative.descargar',
            ],
            [
                'uri' => '/project/administrative_costs_massive_update/',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'masiveUpdate'],
                'permission' => true,
                'name' => 'projectmanagement.administrativeCosts.massiveUpdate',
            ],
            [
                'uri' => '/project/purchases_request/{month_project_id}/administrative_costs',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'index'],
                'permission' => true,
                'name' => 'projectmanagement.administrativeCosts',
            ],
            [
                'uri' => '/administrativecosts_photo/{static_cost_id}',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'download_ac_photo'],
                'permission' => true,
                'name' => 'administrativeCosts.archive',
            ],
            [
                'uri' => '/administrativecosts_advancesearch/{month_project_id}',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'search_costs'],
                'permission' => true,
                'name' => 'administrativeCosts.advance.search',
            ],
            [
                'uri' => '/administrativecosts/excel_export/{month_project_id}',
                'method' => 'get',
                'action' => [AdministrativeCostsController::class, 'export'],
                'permission' => true,
                'name' => 'administrativeCosts.excel.export',
            ],
            [
                'uri' => '/projectmanagement/purchases_request/administrative_costs/{additional_cost}/update',
                'method' => 'post',
                'action' => [AdministrativeCostsController::class, 'update'],
                'permission' => true,
                'name' => 'projectmanagement.updateAdministrativeCost',
            ],
            [
                'uri' => '/projectmanagement/purchases_request/{month_project_id}/administrative_costs/{additional_cost}/destroy',
                'method' => 'delete',
                'action' => [AdministrativeCostsController::class, 'destroy'],
                'permission' => true,
                'name' => 'projectmanagement.deleteAdministrativeCost',
            ],
            // Customers
            [
                'uri' => '/customers',
                'method' => 'any',
                'action' => [CustomersController::class, 'index'],
                'permission' => true,
                'name' => 'customers.index',
            ],
            [
                'uri' => '/customers/search',
                'method' => 'get',
                'action' => [CustomersController::class, 'index'],
                'permission' => true,
                'name' => 'customers.search',
            ],

            // Codes and Titles
            [
                'uri' => '/preprojects/codes',
                'method' => 'get',
                'action' => [PreProjectController::class, 'showCodes'],
                'permission' => true,
                'name' => 'preprojects.codes',
            ],
            [
                'uri' => '/preprojects/titles',
                'method' => 'get',
                'action' => [PreProjectController::class, 'showTitles'],
                'permission' => true,
                'name' => 'preprojects.titles',
            ],

            // PreProjects PEXT
            [
                'uri' => '/preprojects_index/{type}/{preprojects_status?}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'index'],
                'permission' => true,
                'name' => 'preprojects.index',
            ],
            [
                'uri' => '/preprojects_index/{type}/{preprojects_status?}',
                'method' => 'post',
                'action' => [PreProjectController::class, 'index'],
                'permission' => true,
                'name' => 'preprojects.index.post',
            ],

            // Preproject image
            [
                'uri' => '/preprojects/{preproject_id}/report/image',
                'method' => 'get',
                'action' => [PreProjectController::class, 'index_image'],
                'permission' => true,
                'name' => 'preprojects.imagereport.index',
            ],
            [
                'uri' => '/preprojects/report/showimage/{image}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'show_image'],
                'permission' => true,
                'name' => 'preprojects.imagereport.show',
            ],
            [
                'uri' => '/preprojects/{preproject_id}/report/download_image',
                'method' => 'get',
                'action' => [PreProjectController::class, 'download_image'],
                'permission' => true,
                'name' => 'preprojects.imagereport.download',
            ],
            [
                'uri' => '/preprojects/{preproject_title_id}/report/download_report',
                'method' => 'get',
                'action' => [PreProjectController::class, 'download_report'],
                'permission' => true,
                'name' => 'preprojects.report.download',
            ],

            // Photographic report
            [
                'uri' => '/preprojects/{preproject_id}/photoreport',
                'method' => 'get',
                'action' => [PreProjectController::class, 'photoreport_index'],
                'permission' => true,
                'name' => 'preprojects.photoreport.index',
            ],
            [
                'uri' => '/preprojects/photoreport_download/{report_name}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'downloadPR'],
                'permission' => true,
                'name' => 'preprojects.photoreport.download',
            ],
            [
                'uri' => '/preprojects/photoreport_show/{pr_id}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'showPR'],
                'permission' => true,
                'name' => 'preprojects.photoreport.show',
            ],
            [
                'uri' => '/preprojects/photoreport_download_pdf/{pr_id}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'download_pdf_PR'],
                'permission' => true,
                'name' => 'preprojects.photoreport_pdf.download',
            ],

            // Preproject products
            [
                'uri' => '/preprojects/products/{preproject}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'preproject_products_index'],
                'permission' => true,
                'name' => 'preprojects.products',
            ],

            // Preproject purchase request
            [
                'uri' => '/preprojects/request/{id}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'request'],
                'permission' => true,
                'name' => 'preprojects.request.index',
            ],
            [
                'uri' => '/preprojects/request/{id}/details',
                'method' => 'get',
                'action' => [PreProjectController::class, 'request_shopping_details'],
                'permission' => true,
                'name' => 'preprojects.request.details',
            ],

            // Preproject purchase quote
            [
                'uri' => '/preprojects/purchase_quote/{id}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'purchase_quote'],
                'permission' => true,
                'name' => 'preprojects.purchase_quote',
            ],
            [
                'uri' => '/preprojects/purchase_quote/details/{id}',
                'method' => 'get',
                'action' => [PreProjectController::class, 'purchase_quote_details'],
                'permission' => true,
                'name' => 'preprojects.purchase.quote.details',
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
            // Project Pext
            [
                'uri' => '/projectPext/index',
                'method' => 'any',
                'action' => [PextController::class, 'index'],
                'permission' => true,
                'name' => 'projectmanagement.pext.index',
            ],
            [
                'uri' => '/projectPext/projectOrPreproject',
                'method' => 'get',
                'action' => [PextController::class, 'requestProjectOrPreproject'],
                'permission' => true,
                'name' => 'projectmanagement.pext.requestProjectOrPreproject',
            ],
            [
                'uri' => '/projectPext/projectOrPreproject/historial_pext',
                'method' => 'any',
                'action' => [PextController::class, 'historial_pext'],
                'permission' => true,
                'name' => 'projectmanagement.pext.historial',
            ],

            // [
            //     'uri' => '/projectPext/export/expenses',
            //     'method' => 'get',
            //     'action' => [PextController::class, 'export_expenses'],
            //     'permission' => true,
            //     'name' => 'projectmanagement.pext.export.expenses',
            // ],

            [
                'uri' => '/projectPext/expenses/monthly/{project_id}/index/{fixedOrAdditional}/status/{status?}',
                'method' => 'get',
                'action' => [PextController::class, 'index_expenses'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.index',
            ],
            [
                'uri' => '/projectPext/expenses/showImage/{expense_id}',
                'method' => 'get',
                'action' => [PextController::class, 'expense_show_image'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.image.show',
            ],
            [
                'uri' => '/projectPext/expenses/{project_id}/export/{fixedOrAdditional}',
                'method' => 'get',
                'action' => [PextController::class, 'expense_export'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.export',
            ],
            [
                'uri' => '/projectPext/expenses/export/general/{fixedOrAdditional}/cost_line/{cost_line}',
                'method' => 'get',
                'action' => [PextController::class, 'expense_export_general'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenses.general.export',
            ],

            // Project Pext Additional
            [
                'uri' => '/projectPext/additional_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [PextController::class, 'index_additional'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.index',
            ],
            [
                'uri' => '/projectPext/additional_rejected/index/{type}',
                'method' => 'any',
                'action' => [PextController::class, 'index_additional_rejected'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.index_rejected',
            ],
            [
                'uri' => '/projectPext/additional_reject/{pa_id}',
                'method' => 'post',
                'action' => [PextController::class, 'rejectProjectAdditional'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.reject',
            ],

            [
                'uri' => '/projectPext/additional/store_quote/{project_quote_id?}',
                'method' => 'post',
                'action' => [PextController::class, 'store_quote'],
                'permission' => true,
                'name' => 'projectmanagement.pext.store.quote',
            ],
            [
                'uri' => '/projectPext/additional/export/quote/{project_id}',
                'method' => 'get',
                'action' => [PextController::class, 'export_quote'],
                'permission' => true,
                'name' => 'projectmanagement.pext.export.pdf.quote',
            ],
            [
                'uri' => '/projectPext/additional/store/{cicsa_assignation_id?}',
                'method' => 'post',
                'action' => [PextController::class, 'updateOrStoreAdditional'],
                'permission' => true,
                'name' => 'projectmanagement.pext.additional.store',
            ],
            [
                'uri' => '/projectPext/additionalOrFixed/expenses/{project_id}/index/{fixedOrAdditional}/{type}',
                'method' => 'get',
                'action' => [PextController::class, 'additional_expense_index'],
                'permission' => true,
                'name' => 'pext.additional.expense.index',
            ],
            [
                'uri' => '/projectPext/additionalOrFixed/expenses/general/{fixedOrAdditional}/index/{type}',
                'method' => 'get',
                'action' => [PextController::class, 'additional_expense_index_general'],
                'permission' => true,
                'name' => 'pext.additional.expense.general.index',
            ],
            [
                'uri' => '/projectPext/additionalOrFixed/getCicsaAssignation/search_zone',
                'method' => 'post',
                'action' => [PextController::class, 'getCicsaAssignation'],
                'permission' => true,
                'name' => 'pext.additional.expense.general.getCicsaAssignation',
            ],

            [
                'uri' => '/projectPext/additionalOrFixed/expense/search/{project_id}',
                'method' => 'post',
                'action' => [PextController::class, 'search_advance_monthly_or_additional_expense'],
                'permission' => true,
                'name' => 'pext.monthly.additional.expense.search_advance',
            ],
            [
                'uri' => '/projectPext/additionalOrFixed/expense/general/search',
                'method' => 'post',
                'action' => [PextController::class, 'search_advance_additional_expense_general'],
                'permission' => true,
                'name' => 'pext.monthly.additional.expense.general.search_advance',
            ],
            [
                'uri' => '/projectPext/massive_update',
                'method' => 'post',
                'action' => [PextController::class, 'masiveUpdate'],
                'permission' => true,
                'name' => 'projectmanagement.pext.massiveUpdate',
            ],
            [
                'uri' => '/projectPext/massive_update_swap',
                'method' => 'post',
                'action' => [PextController::class, 'masiveUpdateSwap'],
                'permission' => true,
                'name' => 'projectmanagement.pext.massiveUpdate.swap',
            ],

            [
                'uri' => '/projectPext/expense_dashboard/{project_id}',
                'method' => 'get',
                'action' => [PextController::class, 'expense_dashboard'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expense_dashboard',
            ],
            [
                'uri' => '/projectPext/expense_type_zone',
                'method' => 'post',
                'action' => [PextController::class, 'expense_type_zone'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expenset_type_zone',
            ],
            [
                'uri' => '/projectPext/expense_dashboard_bar/{project_id}',
                'method' => 'get',
                'action' => [PextController::class, 'barChart'],
                'permission' => true,
                'name' => 'projectmanagement.pext.expense_dashboard_bar',
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

            // Project task
            [
                'uri' => '/tasks/{id?}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'index'],
                'permission' => true,
                'name' => 'tasks.index',
            ],
            [
                'uri' => '/projecmanagement/task/edit/{taskId}',
                'method' => 'get',
                'action' => [TaskManagementController::class, 'edit'],
                'permission' => true,
                'name' => 'tasks.show',
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
                'uri' => '/preproject/auto-create/pint/{type}',
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
                'uri' => '/projectmanagement/purchases_request/{project_id}/additional_costs/{additional_cost}/destroy',
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
        ];

    }
}