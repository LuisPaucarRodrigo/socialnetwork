<?php

namespace App\Enums\Permissions;

enum PurchasingPermissions: string
{
    

     // PURCHASE REQUESTS
     case PURCHASE_REQUEST_DELETE = 'purchase_request_delete';

     // PURCHASING REQUEST PRODUCTS
    case PURCHASING_REQUEST_PRODUCT_STORE = 'purchasing_request_product_store';
    case PURCHASING_REQUEST_PRODUCT_DELETE = 'purchasing_request_product_delete';

    case PROVIDERS_EDIT = 'providers_edit';
    case PROVIDERS_UPDATE = 'providers_update';
    case PROVIDERS_DESTROY = 'providers_destroy';

    case PURCHASESREQUEST_EDIT = 'purchasesrequest_edit';
    case PURCHASESREQUEST_UPDATE = 'purchasesrequest_update';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
