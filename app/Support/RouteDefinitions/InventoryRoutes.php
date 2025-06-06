<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Inventory\PurchaseProductsController;
use App\Http\Controllers\Inventory\SpecialWarehouseController;
use App\Http\Controllers\Inventory\WarehousesController;

class InventoryRoutes
{
    public static function all(): array
    {
        return [
            // Purchase Products
            [
                'uri' => 'inventory/purchase_products/products',
                'method' => 'get',
                'action' => [PurchaseProductsController::class, 'index'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts',
            ],
            [
                'uri' => 'inventory/purchase_products/products/search/{request}',
                'method' => 'get',
                'action' => [PurchaseProductsController::class, 'search'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts.search',
            ],

            // Inventory - Purchase Products Disable
            [
                'uri' => 'inventory/purchase_products/products/{purchase_product}/disable',
                'method' => 'put',
                'action' => [PurchaseProductsController::class, 'disable'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts.disable',
            ],

            // Inventory Product Type and Active Type
            [
                'uri' => 'inventory/purchase_products/type_product/store',
                'method' => 'post',
                'action' => [PurchaseProductsController::class, 'typeProducts'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts.typeProduct',
            ],
            [
                'uri' => 'inventory/purchase_products/resource_type/store',
                'method' => 'post',
                'action' => [PurchaseProductsController::class, 'resourceType'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts.resourceType',
            ],

            // Resource
            [
                'uri' => 'inventory/resource/purchase_orders/create',
                'method' => 'get',
                'action' => [WarehousesController::class, 'resource_create'],
                'permission' => true,
                'name' => 'warehouses.resource.create',
            ],
            [
                'uri' => 'inventory/resource/purchase_orders/store',
                'method' => 'post',
                'action' => [WarehousesController::class, 'resource_store'],
                'permission' => true,
                'name' => 'warehouses.resource.store',
            ],

            // Purchase Products
            [
                'uri' => 'inventory/purchase_products/products/post',
                'method' => 'post',
                'action' => [PurchaseProductsController::class, 'store'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts.store',
            ],
            [
                'uri' => 'inventory/purchase_products/products/{purchase_product}/update',
                'method' => 'put',
                'action' => [PurchaseProductsController::class, 'update'],
                'permission' => true,
                'name' => 'inventory.purchaseproducts.update',
            ],

            // Special Warehouses
            [
                'uri' => 'inventory/special_products/destroy/{special_inventory_id?}',
                'method' => 'delete',
                'action' => [SpecialWarehouseController::class, 'special_products_destroy'],
                'permission' => true,
                'name' => 'inventory.special_products.destroy',
            ],
            [
                'uri' => 'inventory/special_dispatch_output_delete/{project_entry_output_id}',
                'method' => 'delete',
                'action' => [SpecialWarehouseController::class, 'special_dispatch_output_destroy'],
                'permission' => true,
                'name' => 'inventory.special_dispatch_output.destroy',
            ],

            // Special Warehouses
            [
                'uri' => 'inventory/{warehouse_id}/special_products/create/{special_inventory_id?}',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_products_create'],
                'permission' => true,
                'name' => 'inventory.special_products.create',
            ],
            [
                'uri' => 'inventory/special_products/store/{special_inventory_id?}',
                'method' => 'post',
                'action' => [SpecialWarehouseController::class, 'special_products_store'],
                'permission' => true,
                'name' => 'inventory.special_products.store',
            ],
            [
                'uri' => 'inventory/special_refund_index/accept-decline/{refund_id}',
                'method' => 'post',
                'action' => [SpecialWarehouseController::class, 'special_refund_accept_decline'],
                'permission' => true,
                'name' => 'inventory.special_refund.accept_decline',
            ],
            [
                'uri' => 'inventory/special_dispatch_accept_decline/{project_entry_id}',
                'method' => 'post',
                'action' => [SpecialWarehouseController::class, 'special_dispatch_accept_decline'],
                'permission' => true,
                'name' => 'inventory.special_dispatch.accept_decline',
            ],

            // Conproco
            [
                'uri' => 'inventory/approve_purchase_orders/{warehouse}/approve',
                'method' => 'post',
                'action' => [WarehousesController::class, 'approve'],
                'permission' => true,
                'name' => 'warehouses.purchaseorders.approve.post',
            ],
            [
                'uri' => 'inventory/conproco/{warehouse}/create',
                'method' => 'get',
                'action' => [WarehousesController::class, 'createProducts'],
                'permission' => true,
                'name' => 'warehouses.createNormalProduct',
            ],
            [
                'uri' => 'inventory/conproco/{warehouse}/store',
                'method' => 'post',
                'action' => [WarehousesController::class, 'storeProducts'],
                'permission' => true,
                'name' => 'warehouses.storeNormalProduct',
            ],

            // Retrieval
            [
                'uri' => 'inventory/warehouses/retrieval/approve',
                'method' => 'post',
                'action' => [WarehousesController::class, 'retrievalEntryApprove'],
                'permission' => true,
                'name' => 'retrieval.entry.approbe',
            ],

            // Special dispatch, conproco and retrieval
            [
                'uri' => 'inventory/special_dispatch_output_store',
                'method' => 'post',
                'action' => [SpecialWarehouseController::class, 'special_dispatch_output_store'],
                'permission' => true,
                'name' => 'inventory.special_dispatch_output.store',
            ],

            // Dispatch conproco and retrieval
            [
                'uri' => 'inventory/dispatches/accept_or_decline',
                'method' => 'post',
                'action' => [WarehousesController::class, 'acceptOrDeclineDispatch'],
                'permission' => true,
                'name' => 'warehouses.dispatches.acceptordecline',
            ],

            // Resources
            [
                'uri' => 'inventory/resource/approve/purchase_orders',
                'method' => 'post',
                'action' => [WarehousesController::class, 'approveResourcePurchaseOrders'],
                'permission' => true,
                'name' => 'warehouses.resource.approve',
            ],

            // Services
            [
                'uri' => 'inventory/warehouses/services',
                'method' => 'get',
                'action' => [WarehousesController::class, 'service_index'],
                'permission' => true,
                'name' => 'warehouses.service.approve.index',
            ],
            [
                'uri' => 'inventory/warehouses/services/store',
                'method' => 'post',
                'action' => [WarehousesController::class, 'service_store'],
                'permission' => true,
                'name' => 'warehouses.service.store',
            ],
            [
                'uri' => 'inventory/warehouses/services/delete/{id}',
                'method' => 'delete',
                'action' => [WarehousesController::class, 'service_delete'],
                'permission' => true,
                'name' => 'warehouses.service.delete',
            ],
            [
                'uri' => 'inventory/resource/serial_number/purchase_orders',
                'method' => 'post',
                'action' => [WarehousesController::class, 'serialNumberResourcePurchaseOrders'],
                'permission' => true,
                'name' => 'warehouses.resource.add.serial_number',
            ],

            // Warehouses
            [
                'uri' => 'inventory/warehouses',
                'method' => 'get',
                'action' => [WarehousesController::class, 'showWarehouses'],
                'permission' => true,
                'name' => 'warehouses.warehouses',
            ],

            // Special Warehouses
            [
                'uri' => 'inventory/{warehouse_id}/special_products',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_products_index'],
                'permission' => true,
                'name' => 'inventory.special_products.index',
            ],
            [
                'uri' => 'inventory/{warehouse_id}/special_dispatch_index/',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_dispatch_index'],
                'permission' => true,
                'name' => 'inventory.special_dispatch.index',
            ],
            [
                'uri' => 'inventory/{warehouse_id}/special_dispatch_approved/',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_dispatch_approved'],
                'permission' => true,
                'name' => 'inventory.special_dispatch.approved',
            ],
            [
                'uri' => 'inventory/{warehouse_id}/special_dispatch_rejected/',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_dispatch_rejected'],
                'permission' => true,
                'name' => 'inventory.special_dispatch.rejected',
            ],
            [
                'uri' => 'inventory/{warehouse_id}/special_refund_index/',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_refund_index'],
                'permission' => true,
                'name' => 'inventory.special_refund.index',
            ],
            [
                'uri' => 'inventory/{warehouse_id}/special_refund_historial/',
                'method' => 'get',
                'action' => [SpecialWarehouseController::class, 'special_refund_historial'],
                'permission' => true,
                'name' => 'inventory.special_refund.historial',
            ],
            // Conproco
            [
                'uri' => 'inventory/conproco/approve_purchase_orders/{warehouse}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'approvePurchaseOrders'],
                'permission' => true,
                'name' => 'warehouses.purchaseorders.approve',
            ],
            [
                'uri' => 'inventory/conproco/{warehouse}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'showProducts'],
                'permission' => true,
                'name' => 'warehouses.conproco.products',
            ],
            [
                'uri' => 'inventory/conproco/{warehouse}/{inventory}/get',
                'method' => 'get',
                'action' => [WarehousesController::class, 'showEntries'],
                'permission' => true,
                'name' => 'warehouses.products.entries',
            ],
            [
                'uri' => 'inventory/conproco/dispatches/{warehouse}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'showDispatches'],
                'permission' => true,
                'name' => 'warehouses.dispatches',
            ],
            [
                'uri' => 'inventory/conproco/dispatches_approved/{warehouse}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'showApprovedDispatches'],
                'permission' => true,
                'name' => 'warehouses.dispatches.approved',
            ],
            [
                'uri' => 'inventory/conproco/dispatches_rejected/{warehouse}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'showRejectedDispatches'],
                'permission' => true,
                'name' => 'warehouses.dispatches.rejected',
            ],

            // Retrieval
            [
                'uri' => 'inventory/retrieval/entry/{boolean?}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'retrieval_entry_index'],
                'permission' => true,
                'name' => 'inventory.retrieval.entry.index',
            ],
            [
                'uri' => 'inventory/retrieval_product/warehouses',
                'method' => 'get',
                'action' => [WarehousesController::class, 'retrievalProduct'],
                'permission' => true,
                'name' => 'inventory.retrieval.product.index',
            ],
            [
                'uri' => 'inventory/show/retrieval_product/{product}/warehouses',
                'method' => 'get',
                'action' => [WarehousesController::class, 'retrievalProductShow'],
                'permission' => true,
                'name' => 'inventory.retrieval.product.show',
            ],
            [
                'uri' => 'inventory/warehouses/retrieval/dispatch',
                'method' => 'get',
                'action' => [WarehousesController::class, 'retrievalDispatch'],
                'permission' => true,
                'name' => 'inventory.retrievalDispatch.index',
            ],
            [
                'uri' => 'inventory/warehouses/retrieval/dispatch/approved',
                'method' => 'get',
                'action' => [WarehousesController::class, 'retrievalDispatchApproved'],
                'permission' => true,
                'name' => 'inventory.retrievalDispatch.approved',
            ],
            [
                'uri' => 'inventory/warehouses/retrieval/dispatch/rejected',
                'method' => 'get',
                'action' => [WarehousesController::class, 'retrievalDispatchRejected'],
                'permission' => true,
                'name' => 'inventory.retrievalDispatch.rejected',
            ],

            // Resources
            [
                'uri' => 'inventory/resource/purchase_orders',
                'method' => 'get',
                'action' => [WarehousesController::class, 'resourcePurchaseOrders'],
                'permission' => true,
                'name' => 'warehouses.resource',
            ],
            [
                'uri' => 'inventory/resource/products/purchase_orders/{boolean?}',
                'method' => 'get',
                'action' => [WarehousesController::class, 'productResourcePurchaseOrders'],
                'permission' => true,
                'name' => 'warehouses.resource.active.index',
            ],


        ];
    }
}
