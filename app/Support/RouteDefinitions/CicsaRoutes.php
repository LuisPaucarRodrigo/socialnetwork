<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\Cicsa\CicsaController;

class CicsaRoutes
{
    public static function all(): array
    {
        return [
            // Rutas Cicsa Process
            [
                'uri' => 'cicsa_process_index/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'index'],
                'permission' => true,
                'name' => 'cicsa.index',
            ],
            [
                'uri' => 'cicsa_process_export/{type}/{stages?}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportCiscaProcess'],
                'permission' => true,
                'name' => 'cicsa.export',
            ],

            // Rutas Cicsa Assignation
            [
                'uri' => 'cicsa_assignation_destroy/destroy/{ca_id}',
                'method' => 'delete',
                'action' => [CicsaController::class, 'destroy'],
                'permission' => true,
                'name' => 'cicsa.assignation.destroy',
            ],
            [
                'uri' => 'cicsa_assignation/{type}/index/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexAssignation'],
                'permission' => true,
                'name' => 'assignation.index',
            ],
            [
                'uri' => 'cicsa_assignation_export/export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportAssignation'],
                'permission' => true,
                'name' => 'assignation.export',
            ],
            [
                'uri' => 'cicsa_assignation_update/{cicsa_assignation_id}',
                'method' => 'post',
                'action' => [CicsaController::class, 'updateAssignation'],
                'permission' => true,
                'name' => 'assignation.update',
            ],

            // Rutas Cicsa Feasibilities
            [
                'uri' => 'cicsa_feasibilities_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexFeasibilities'],
                'permission' => true,
                'name' => 'feasibilities.index',
            ],
            [
                'uri' => 'cicsa_feasibilities_store/store/update/{cicsa_assignation_id?}',
                'method' => 'put',
                'action' => [CicsaController::class, 'updateOrStoreFeasibilities'],
                'permission' => true,
                'name' => 'feasibilities.storeOrUpdate',
            ],
            [
                'uri' => 'cicsa_feasibilities_export/export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportFeasibilities'],
                'permission' => true,
                'name' => 'feasibilities.export',
            ],

            // Rutas Cicsa Material
            [
                'uri' => 'cicsa_material_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexMaterial'],
                'permission' => true,
                'name' => 'material.index',
            ],
            [
                'uri' => 'cicsa_material_store',
                'method' => 'post',
                'action' => [CicsaController::class, 'storeMaterial'],
                'permission' => true,
                'name' => 'material.store',
            ],
            [
                'uri' => 'cicsa_material_update/{cicsa_material_id}',
                'method' => 'put',
                'action' => [CicsaController::class, 'updateMaterial'],
                'permission' => true,
                'name' => 'material.update',
            ],
            [
                'uri' => 'cicsa_material_search/material',
                'method' => 'post',
                'action' => [CicsaController::class, 'searchMaterial'],
                'permission' => true,
                'name' => 'material.search.material',
            ],
            [
                'uri' => 'cicsa_material_export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportMaterial'],
                'permission' => true,
                'name' => 'material.export',
            ],
            [
                'uri' => 'cicsa_material_importmaterial',
                'method' => 'post',
                'action' => [CicsaController::class, 'importMaterial'],
                'permission' => true,
                'name' => 'material.import',
            ],
            [
                'uri' => 'cicsa_material_delete/{c_m_id}',
                'method' => 'delete',
                'action' => [CicsaController::class, 'deleteMaterial'],
                'permission' => true,
                'name' => 'material.delete',
            ],

            // Rutas Cicsa Purchase Order
            [
                'uri' => 'cicsa_purchase_order_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexPurchaseOrder'],
                'permission' => true,
                'name' => 'purchase.order.index',
            ],
            [
                'uri' => 'cicsa_purchase_order_store/update/{cicsa_purchase_order_id?}',
                'method' => 'post',
                'action' => [CicsaController::class, 'updateOrStorePurchaseOrder'],
                'permission' => true,
                'name' => 'purchaseOrder.storeOrUpdate',
            ],
            [
                'uri' => 'cicsa_purchase_order_showDocument/{purchaseOrder}',
                'method' => 'get',
                'action' => [CicsaController::class, 'showDocument'],
                'permission' => true,
                'name' => 'purchase.order.showDocument',
            ],
            [
                'uri' => 'cicsa_purchase_order_export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportPurchaseOrder'],
                'permission' => true,
                'name' => 'purchase.order.export',
            ],
            
            // Rutas para otros procesos Cicsa
            [
                'uri' => 'cicsa_installation_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexInstallation'],
                'permission' => true,
                'name' => 'cicsa.installation.index',
            ],
            [
                'uri' => 'cicsa_installation_storeOrUpdate/{ci_id?}',
                'method' => 'post',
                'action' => [CicsaController::class, 'updateOrStoreInstallation'],
                'permission' => true,
                'name' => 'cicsa.installation.store',
            ],
            [
                'uri' => 'cicsa_installation_export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportInstallation'],
                'permission' => true,
                'name' => 'cicsa.installation.export',
            ],

            // Cicsa Purchase Order Validations
            [
                'uri' => 'cicsa_purchase_order_validation_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexOCValidation'],
                'permission' => true,
                'name' => 'cicsa.purchase_orders.validation',
            ],
            [
                'uri' => 'cicsa_purchase_order_validation_store/{cicsa_validation_order_id}',
                'method' => 'put',
                'action' => [CicsaController::class, 'storeOrUpdateOCValidation'],
                'permission' => true,
                'name' => 'cicsa.purchase_orders.validation.update',
            ],
            [
                'uri' => 'cicsa_purchase_order_validation_export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportOCValidation'],
                'permission' => true,
                'name' => 'cicsa.purchase_orders.validation.export',
            ],

            // Cicsa Service Orders
            [
                'uri' => 'cicsa_service_orders_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexServiceOrder'],
                'permission' => true,
                'name' => 'cicsa.service_orders',
            ],
            [
                'uri' => 'cicsa_service_orders_update/{cicsa_service_order_id}',
                'method' => 'post',
                'action' => [CicsaController::class, 'updateServiceOrder'],
                'permission' => true,
                'name' => 'cicsa.service_orders.update',
            ],
            [
                'uri' => 'cicsa_service_orders_showDocument/OS/{serviceOrder}/Fac/{doc}',
                'method' => 'get',
                'action' => [CicsaController::class, 'showServiceDocument'],
                'permission' => true,
                'name' => 'cicsa.service_orders.showDocument',
            ],
            [
                'uri' => 'cicsa_service_orders_export/{type}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportServiceOrder'],
                'permission' => true,
                'name' => 'cicsa.service_orders.export',
            ],

            // Cicsa Charge Areas
            [
                'uri' => 'cicsa_charge_areas_index/{type}/{searchCondition?}',
                'method' => 'any',
                'action' => [CicsaController::class, 'indexChargeArea'],
                'permission' => true,
                'name' => 'cicsa.charge_areas',
            ],
            [
                'uri' => 'cicsa_charge_areas_update/{cicsa_charge_area_id}',
                'method' => 'post',
                'action' => [CicsaController::class, 'updateChargeArea'],
                'permission' => true,
                'name' => 'cicsa.charge_areas.update',
            ],
            [
                'uri' => 'cicsa_charge_areas_showDocument/{chargeAreaOrder}',
                'method' => 'get',
                'action' => [CicsaController::class, 'showChargeAreaDocument'],
                'permission' => true,
                'name' => 'cicsa.charge_areas.showDocument',
            ],
            [
                'uri' => 'cicsa_charge_areas_export/{cost_line_id}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportChargeArea'],
                'permission' => true,
                'name' => 'cicsa.charge_areas.export',
            ],

            // Cicsa Advanced Search
            [
                'uri' => 'cicsa_advance_search/{type}',
                'method' => 'post',
                'action' => [CicsaController::class, 'search'],
                'permission' => true,
                'name' => 'cicsa.advance.search',
            ],
            [
                'uri' => 'cicsa_export_materials_summary/{ca_id}',
                'method' => 'get',
                'action' => [CicsaController::class, 'exportMaterialsSummary'],
                'permission' => true,
                'name' => 'cicsa.export.materials.summary',
            ],
        ];
    }
}
