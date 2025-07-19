<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\RoomRental\RoomRentalController;

class RoomRentalRoutes {
    public static function all(): array
    {
        return [
            [
                'uri' => 'room_rental/index/{id?}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'index'],
                'permission' => true,
                'name' => 'room.rental.index',
            ],
            [
                'uri' => 'room_rental/search',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'search'],
                'permission' => true,
                'name' => 'room.rental.search',
            ],
            [
                'uri' => 'room_rental/store',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'store'],
                'permission' => true,
                'name' => 'room.rental.store',
            ],
            [
                'uri' => 'room_rental/update/{car}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'update'],
                'permission' => true,
                'name' => 'room.rental.update',
            ],
            [
                'uri' => 'room_rental/show_image/{car}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showImage'],
                'permission' => true,
                'name' => 'room.rental.show.image',
            ],
            [
                'uri' => 'room_rental/destroy/{car}',
                'method' => 'delete',
                'action' => [RoomRentalController::class, 'destroy'],
                'permission' => true,
                'name' => 'room.rental.destroy',
            ],
            [
                'uri' => 'room_rental/show_documents/{car_document}/document_name/{fieldName}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showDocuments'],
                'permission' => true,
                'name' => 'room.rental.show_documents',
            ],
            [
                'uri' => 'room_rental/store_document',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'storeDocument'],
                'permission' => true,
                'name' => 'room.rental.store_document',
            ],
            [
                'uri' => 'room_rental/update_document/{car_document}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'updateDocument'],
                'permission' => true,
                'name' => 'room.rental.update.document',
            ],
            [
                'uri' => 'room_rental/destroy_document/{car_document}',
                'method' => 'delete',
                'action' => [RoomRentalController::class, 'destroyDocument'],
                'permission' => true,
                'name' => 'room.rental.destroy_document',
            ],
            // [
            //     'uri' => 'room_rental/approvel_car_document/index',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'indexApprovelCarDocument'],
            //     'permission' => true,
            //     'name' => 'room.rental.index.approvel',
            // ],
            // [
            //     'uri' => 'room_rental/approvel_car_document/approve/changes/{id}',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'approveChanges'],
            //     'permission' => true,
            //     'name' => 'room.rental.approve.change',
            // ],
            // [
            //     'uri' => 'room_rental/approvel_car_document/rejected/changes/{id}',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'deleteChanges'],
            //     'permission' => true,
            //     'name' => 'room.rental.rejected.change',
            // ],
            // [
            //     'uri' => 'room_rental/approvel_car_document/show_document/{approval_car}/document_name/{fieldName}',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'showDocumentsApproval'],
            //     'permission' => true,
            //     'name' => 'room.rental.show_approvals_document',
            // ],
            // [
            //     'uri' => 'room_rental/store_changelog/{car}',
            //     'method' => 'post',
            //     'action' => [RoomRentalController::class, 'storeChangelog'],
            //     'permission' => true,
            //     'name' => 'room.rental.store_changelog',
            // ],
            // [
            //     'uri' => 'room_rental/update_changelog/{car_changelog}',
            //     'method' => 'post',
            //     'action' => [RoomRentalController::class, 'updateChangelog'],
            //     'permission' => true,
            //     'name' => 'room.rental.update_changelog',
            // ],
            // [
            //     'uri' => 'room_rental/destroy_changelog/{car_changelog}',
            //     'method' => 'delete',
            //     'action' => [RoomRentalController::class, 'destroyChangelog'],
            //     'permission' => true,
            //     'name' => 'room.rental.destroy_changelog',
            // ],
            // [
            //     'uri' => 'room_rental/show_invoice/{car_changelog}',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'showChangelogInvoice'],
            //     'permission' => true,
            //     'name' => 'room.rental.show_invoice',
            // ],
            // [
            //     'uri' => 'room_rental/show_checklist/{car}',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'showChecklist'],
            //     'permission' => true,
            //     'name' => 'room.rental.show_checklist',
            // ],
            // [
            //     'uri' => 'room_rental/show_checklist/send_images/{checklist}',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'sendChecklistImages'],
            //     'permission' => true,
            //     'name' => 'room.rental.show_checklist.send_images',
            // ],
            [
                'uri' => 'room_rental/expiration/alarms',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'alarms'],
                'permission' => true,
                'name' => 'room.rental.alarms',
            ],
            // [
            //     'uri' => 'room_rental/expiration/alarms_checklist',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'checkListAlarms'],
            //     'permission' => true,
            //     'name' => 'room.rental.checklist.alarms',
            // ],
            [
                'uri' => 'room_rental/specific_expiration/alarms/{car_id}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'specificAlarm'],
                'permission' => true,
                'name' => 'room.rental.specific.alarms',
            ],
            // [
            //     'uri' => 'room_rental/show_checklist/accept_or_decline/{changelog}/{is_accepted}',
            //     'method' => 'put',
            //     'action' => [RoomRentalController::class, 'acceptOrDecline'],
            //     'permission' => true,
            //     'name' => 'room.rental.show_checklist.accept_or_decline',
            // ],
            // [
            //     'uri' => 'room_rental/expiration/alarms/changelogs',
            //     'method' => 'get',
            //     'action' => [RoomRentalController::class, 'getChangelogAlarms'],
            //     'permission' => true,
            //     'name' => 'room.rental.alarms.changelogs',
            // ],
        ];
        
    }
}