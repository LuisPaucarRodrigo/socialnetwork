<?php

namespace App\Enums\Permissions;

enum InventoryPermissions: string
{

    // SPECIAL WAREHOUSES
    case SPECIAL_PRODUCTS_DELETE = 'special_products_delete';
    case SPECIAL_DISPATCH_OUTPUT_DELETE = 'special_dispatch_output_delete';


    case INVENTORY_PURCHASE_PRODUCTS_DISABLE = 'purchase_products_disable';
    case INVENTORY_PURCHASE_PRODUCTS_TYPE_STORE = 'purchase_products_type_store';
    case INVENTORY_PURCHASE_PRODUCTS_RESOURCE_TYPE_STORE = 'purchase_products_resource_type_store';

    case WAREHOUSES_RESOURCE_CREATE = 'resource_create';
    case WAREHOUSES_RESOURCE_STORE = 'resource_store';


    
}
