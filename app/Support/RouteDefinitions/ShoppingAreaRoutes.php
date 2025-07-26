<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\ShoppingArea\PurchaseOrdersController;
use App\Http\Controllers\ShoppingArea\PurchaseRequestController;
use App\Support\RouteDefinitions\ShoppingArea\MCSRoutes;
use App\Support\RouteDefinitions\ShoppingArea\PaymentApprovalRoutes;
use App\Support\RouteDefinitions\ShoppingArea\ProvidersRoutes;

class ShoppingAreaRoutes
{
    public static function all(): array
    {
        return array_merge(
            PaymentApprovalRoutes::all(),
            ProvidersRoutes::all(),
            MCSRoutes::all(),
            [
                // Purchase Request
                [
                    'uri' => 'shopping_area/purchasesrequest/destroy/{id}',
                    'method' => 'delete',
                    'action' => [PurchaseRequestController::class, 'destroy'],
                    'permission' => true,
                    'name' => 'purchasesrequest.destroy',
                ],
                // Purchasing Request Products
                [
                    'uri' => 'purchasing_request_product_store',
                    'method' => 'post',
                    'action' => [PurchaseRequestController::class, 'purchasing_request_product_store'],
                    'permission' => true,
                    'name' => 'purchasing_request_product.store',
                ],
                [
                    'uri' => 'purchasing_request_product_delete/{purchasing_request_product_id}',
                    'method' => 'delete',
                    'action' => [PurchaseRequestController::class, 'purchasing_request_product_delete'],
                    'permission' => true,
                    'name' => 'purchasing_request_product.delete',
                ],

                // Purchase Request Edit & Update
                [
                    'uri' => 'shopping_area/purchasesrequest/edit/{id}/{project_id?}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'edit'],
                    'permission' => true,
                    'name' => 'purchasesrequest.edit',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/update/{id}',
                    'method' => 'put',
                    'action' => [PurchaseRequestController::class, 'update'],
                    'permission' => true,
                    'name' => 'purchasesrequest.update',
                ],


                // Purchase Request
                [
                    'uri' => 'shopping_area/purchasesrequest/create',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'create'],
                    'permission' => true,
                    'name' => 'purchasesrequest.create',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/store',
                    'method' => 'post',
                    'action' => [PurchaseRequestController::class, 'store'],
                    'permission' => true,
                    'name' => 'purchasesrequest.store',
                ],

                // Purchase Quote
                [
                    'uri' => 'shopping_area/purchasesrequest/deadline_complete/quotes/{id}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'purchase_quote_deadline_complete'],
                    'permission' => true,
                    'name' => 'purchasesrequest.quote_deadline.complete',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/quotes/{id}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'index_quotes'],
                    'permission' => true,
                    'name' => 'purchasesrequest.quotes.create',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/orders',
                    'method' => 'post',
                    'action' => [PurchaseRequestController::class, 'quote'],
                    'permission' => true,
                    'name' => 'purchasesrequest.quotes.store',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/reject/{id}',
                    'method' => 'post',
                    'action' => [PurchaseRequestController::class, 'reject_request'],
                    'permission' => true,
                    'name' => 'purchasesrequest.reject_request',
                ],

                // Update Quote Deadline
                [
                    'uri' => 'shopping_area/purchasesrequest/purchase_quote/details/{id}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'purchase_quote_complete_details'],
                    'permission' => true,
                    'name' => 'purchase.quote.details.complete',
                ],
                [
                    'uri' => 'shopping_area/project/purchases_request/update/quotedeadline',
                    'method' => 'post',
                    'action' => [PurchaseRequestController::class, 'project_purchases_request_update_quote_deadline'],
                    'permission' => true,
                    'name' => 'purchase.update_quotedeadline',
                ],

                // Orders
                [
                    'uri' => 'shopping_area/purchaseorders/state',
                    'method' => 'post',
                    'action' => [PurchaseOrdersController::class, 'state'],
                    'permission' => true,
                    'name' => 'purchaseorders.state',
                ],


                // Purchase Request
                [
                    'uri' => 'shopping_area/purchasesrequest',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'index'],
                    'permission' => true,
                    'name' => 'purchasesrequest.index',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/search/{request}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'search'],
                    'permission' => true,
                    'name' => 'purchasesrequest.search',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/export/{id}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'export'],
                    'permission' => true,
                    'name' => 'purchasingrequest.export',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/details/{id}',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'details'],
                    'permission' => true,
                    'name' => 'purchasingrequest.details',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/alarm',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'alarm'],
                    'permission' => true,
                    'name' => 'purchasesrequest.alarm',
                ],

                // Purchase Orders
                [
                    'uri' => 'shopping_area/purchaseorders',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'index'],
                    'permission' => true,
                    'name' => 'purchaseorders.index',
                ],
                [
                    'uri' => 'shopping_area/purchaseorders/search/{request}/{history}',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'search'],
                    'permission' => true,
                    'name' => 'purchaseorders.search',
                ],
                [
                    'uri' => 'shopping_area/purchaseorders_details/{purchase_order_id}',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'purchase_order_view'],
                    'permission' => true,
                    'name' => 'purchaseorders.details',
                ],
                [
                    'uri' => 'shopping_area/purchaseorders/{id}/export',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'purchase_orders_export'],
                    'permission' => true,
                    'name' => 'purchaseorders.export.order',
                ],
                [
                    'uri' => 'shopping_area/purchaseorders/alarms',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'purchase_orders_alarms'],
                    'permission' => true,
                    'name' => 'purchaseorders.alarms',
                ],

                // Purchase Completed
                [
                    'uri' => 'shopping_area/purchaseorders/history',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'history'],
                    'permission' => true,
                    'name' => 'purchaseorders.history',
                ],
                [
                    'uri' => 'shopping_area/purchasesrequest/quotes/{id}/preview',
                    'method' => 'get',
                    'action' => [PurchaseRequestController::class, 'showDocument'],
                    'permission' => true,
                    'name' => 'purchasesrequest.show',
                ],
                [
                    'uri' => 'shopping_area/purchasesorder/{id}/preview',
                    'method' => 'get',
                    'action' => [PurchaseOrdersController::class, 'showFacture'],
                    'permission' => true,
                    'name' => 'purchasesorder.showFacture',
                ],
            ]
        );
    }
}
