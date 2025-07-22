<?php

namespace App\Support\RouteDefinitions\Projects;

use App\Http\Controllers\ProjectArea\PreProjectController;

class PreprojectRoutes
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

            // PreProjects
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
                'uri' => '/preprojects/{preproject_id}/report/image_index',
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
        ];
    }
}
