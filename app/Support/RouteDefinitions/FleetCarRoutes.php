<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\CarManagement\CarsController;

class FleetCarRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'fleet_cars/index/{id?}',
                'method' => 'get',
                'action' => [CarsController::class, 'index'],
                'permission' => true,
                'name' => 'fleet.cars.index',
            ],
            [
                'uri' => 'fleet_cars/search',
                'method' => 'post',
                'action' => [CarsController::class, 'search'],
                'permission' => true,
                'name' => 'fleet.cars.search',
            ],
            [
                'uri' => 'fleet_cars/store',
                'method' => 'post',
                'action' => [CarsController::class, 'store'],
                'permission' => true,
                'name' => 'fleet.cars.store',
            ],
            [
                'uri' => 'fleet_cars/update/{car}',
                'method' => 'post',
                'action' => [CarsController::class, 'update'],
                'permission' => true,
                'name' => 'fleet.cars.update',
            ],
            [
                'uri' => 'fleet_cars/show_image/{car}',
                'method' => 'get',
                'action' => [CarsController::class, 'showImage'],
                'permission' => true,
                'name' => 'fleet.cars.show.image',
            ],
            [
                'uri' => 'fleet_cars/destroy/{car}',
                'method' => 'delete',
                'action' => [CarsController::class, 'destroy'],
                'permission' => true,
                'name' => 'fleet.cars.destroy',
            ],
            [
                'uri' => 'fleet_cars/show_documents/{car_document}/document_name/{fieldName}',
                'method' => 'get',
                'action' => [CarsController::class, 'showDocuments'],
                'permission' => true,
                'name' => 'fleet.cars.show_documents',
            ],
            [
                'uri' => 'fleet_cars/store_document',
                'method' => 'post',
                'action' => [CarsController::class, 'storeDocument'],
                'permission' => true,
                'name' => 'fleet.cars.store_document',
            ],
            [
                'uri' => 'fleet_cars/update_document/{car_document}',
                'method' => 'post',
                'action' => [CarsController::class, 'updateDocument'],
                'permission' => true,
                'name' => 'fleet.cars.update.document',
            ],
            [
                'uri' => 'fleet_cars/destroy_document/{car_document}',
                'method' => 'delete',
                'action' => [CarsController::class, 'destroyDocument'],
                'permission' => true,
                'name' => 'fleet.cars.destroy_document',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/index',
                'method' => 'get',
                'action' => [CarsController::class, 'indexApprovelCarDocument'],
                'permission' => true,
                'name' => 'fleet.cars.index.approvel',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/approve/changes/{id}',
                'method' => 'get',
                'action' => [CarsController::class, 'approveChanges'],
                'permission' => true,
                'name' => 'fleet.cars.approve.change',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/rejected/changes/{id}',
                'method' => 'get',
                'action' => [CarsController::class, 'deleteChanges'],
                'permission' => true,
                'name' => 'fleet.cars.rejected.change',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/show_document/{approval_car}/document_name/{fieldName}',
                'method' => 'get',
                'action' => [CarsController::class, 'showDocumentsApproval'],
                'permission' => true,
                'name' => 'fleet.cars.show_approvals_document',
            ],
            [
                'uri' => 'fleet_cars/store_changelog/{car}',
                'method' => 'post',
                'action' => [CarsController::class, 'storeChangelog'],
                'permission' => true,
                'name' => 'fleet.cars.store_changelog',
            ],
            [
                'uri' => 'fleet_cars/update_changelog/{car_changelog}',
                'method' => 'post',
                'action' => [CarsController::class, 'updateChangelog'],
                'permission' => true,
                'name' => 'fleet.cars.update_changelog',
            ],
            [
                'uri' => 'fleet_cars/destroy_changelog/{car_changelog}',
                'method' => 'delete',
                'action' => [CarsController::class, 'destroyChangelog'],
                'permission' => true,
                'name' => 'fleet.cars.destroy_changelog',
            ],
            [
                'uri' => 'fleet_cars/show_invoice/{car_changelog}',
                'method' => 'get',
                'action' => [CarsController::class, 'showChangelogInvoice'],
                'permission' => true,
                'name' => 'fleet.cars.show_invoice',
            ],
            [
                'uri' => 'fleet_cars/show_checklist/{car}',
                'method' => 'get',
                'action' => [CarsController::class, 'showChecklist'],
                'permission' => true,
                'name' => 'fleet.cars.show_checklist',
            ],
            [
                'uri' => 'fleet_cars/show_checklist/send_images/{checklist}',
                'method' => 'get',
                'action' => [CarsController::class, 'sendChecklistImages'],
                'permission' => true,
                'name' => 'fleet.cars.show_checklist.send_images',
            ],
            [
                'uri' => 'fleet_cars/expiration/alarms',
                'method' => 'get',
                'action' => [CarsController::class, 'alarms'],
                'permission' => true,
                'name' => 'fleet.cars.alarms',
            ],
            [
                'uri' => 'fleet_cars/expiration/alarms_checklist',
                'method' => 'get',
                'action' => [CarsController::class, 'checkListAlarms'],
                'permission' => true,
                'name' => 'fleet.cars.checklist.alarms',
            ],
            [
                'uri' => 'fleet_cars/specific_expiration/alarms/{car_id}',
                'method' => 'get',
                'action' => [CarsController::class, 'specificAlarm'],
                'permission' => true,
                'name' => 'fleet.cars.specific.alarms',
            ],
            [
                'uri' => 'fleet_cars/show_checklist/accept_or_decline/{changelog}/{is_accepted}',
                'method' => 'put',
                'action' => [CarsController::class, 'acceptOrDecline'],
                'permission' => true,
                'name' => 'fleet.cars.show_checklist.accept_or_decline',
            ],
            [
                'uri' => 'fleet_cars/expiration/alarms/changelogs',
                'method' => 'get',
                'action' => [CarsController::class, 'getChangelogAlarms'],
                'permission' => true,
                'name' => 'fleet.cars.alarms.changelogs',
            ],
        ];
        
    }
}