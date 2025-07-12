<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\RoomRental\RoomRentalController;

class RoomRentalRoutes {
    public static function all(): array
    {
        return [
            [
                'uri' => 'fleet_cars/index/{id?}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'index'],
                'permission' => true,
                'name' => 'room.rental.index',
            ],
            [
                'uri' => 'fleet_cars/search',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'search'],
                'permission' => true,
                'name' => 'room.rental.search',
            ],
            [
                'uri' => 'fleet_cars/store',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'store'],
                'permission' => true,
                'name' => 'room.rental.store',
            ],
            [
                'uri' => 'fleet_cars/update/{car}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'update'],
                'permission' => true,
                'name' => 'room.rental.update',
            ],
            [
                'uri' => 'fleet_cars/show_image/{car}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showImage'],
                'permission' => true,
                'name' => 'room.rental.show.image',
            ],
            [
                'uri' => 'fleet_cars/destroy/{car}',
                'method' => 'delete',
                'action' => [RoomRentalController::class, 'destroy'],
                'permission' => true,
                'name' => 'room.rental.destroy',
            ],
            [
                'uri' => 'fleet_cars/show_documents/{car_document}/document_name/{fieldName}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showDocuments'],
                'permission' => true,
                'name' => 'room.rental.show_documents',
            ],
            [
                'uri' => 'fleet_cars/store_document',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'storeDocument'],
                'permission' => true,
                'name' => 'room.rental.store_document',
            ],
            [
                'uri' => 'fleet_cars/update_document/{car_document}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'updateDocument'],
                'permission' => true,
                'name' => 'room.rental.update.document',
            ],
            [
                'uri' => 'fleet_cars/destroy_document/{car_document}',
                'method' => 'delete',
                'action' => [RoomRentalController::class, 'destroyDocument'],
                'permission' => true,
                'name' => 'room.rental.destroy_document',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/index',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'indexApprovelCarDocument'],
                'permission' => true,
                'name' => 'room.rental.index.approvel',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/approve/changes/{id}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'approveChanges'],
                'permission' => true,
                'name' => 'room.rental.approve.change',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/rejected/changes/{id}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'deleteChanges'],
                'permission' => true,
                'name' => 'room.rental.rejected.change',
            ],
            [
                'uri' => 'fleet_cars/approvel_car_document/show_document/{approval_car}/document_name/{fieldName}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showDocumentsApproval'],
                'permission' => true,
                'name' => 'room.rental.show_approvals_document',
            ],
            [
                'uri' => 'fleet_cars/store_changelog/{car}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'storeChangelog'],
                'permission' => true,
                'name' => 'room.rental.store_changelog',
            ],
            [
                'uri' => 'fleet_cars/update_changelog/{car_changelog}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'updateChangelog'],
                'permission' => true,
                'name' => 'room.rental.update_changelog',
            ],
            [
                'uri' => 'fleet_cars/destroy_changelog/{car_changelog}',
                'method' => 'delete',
                'action' => [RoomRentalController::class, 'destroyChangelog'],
                'permission' => true,
                'name' => 'room.rental.destroy_changelog',
            ],
            [
                'uri' => 'fleet_cars/show_invoice/{car_changelog}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showChangelogInvoice'],
                'permission' => true,
                'name' => 'room.rental.show_invoice',
            ],
            [
                'uri' => 'fleet_cars/show_checklist/{car}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showChecklist'],
                'permission' => true,
                'name' => 'room.rental.show_checklist',
            ],
            [
                'uri' => 'fleet_cars/show_checklist/send_images/{checklist}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'sendChecklistImages'],
                'permission' => true,
                'name' => 'room.rental.show_checklist.send_images',
            ],
            [
                'uri' => 'fleet_cars/expiration/alarms',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'alarms'],
                'permission' => true,
                'name' => 'room.rental.alarms',
            ],
            [
                'uri' => 'fleet_cars/expiration/alarms_checklist',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'checkListAlarms'],
                'permission' => true,
                'name' => 'room.rental.checklist.alarms',
            ],
            [
                'uri' => 'fleet_cars/specific_expiration/alarms/{car_id}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'specificAlarm'],
                'permission' => true,
                'name' => 'room.rental.specific.alarms',
            ],
            [
                'uri' => 'fleet_cars/show_checklist/accept_or_decline/{changelog}/{is_accepted}',
                'method' => 'put',
                'action' => [RoomRentalController::class, 'acceptOrDecline'],
                'permission' => true,
                'name' => 'room.rental.show_checklist.accept_or_decline',
            ],
            [
                'uri' => 'fleet_cars/expiration/alarms/changelogs',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'getChangelogAlarms'],
                'permission' => true,
                'name' => 'room.rental.alarms.changelogs',
            ],
        ];
        
    }
}