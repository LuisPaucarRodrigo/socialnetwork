<?php

namespace App\Support\RouteDefinitions;

use App\Http\Controllers\RoomRental\RoomRentalController;

class RoomRentalRoutes
{
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
                'uri' => 'room_rental/store_images/{room}',
                'method' => 'post',
                'action' => [RoomRentalController::class, 'storeImages'],
                'permission' => true,
                'name' => 'room.rental.store_images',
            ],
            [
                'uri' => 'room_rental/getImages/{room_id}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'getImages'],
                'permission' => true,
                'name' => 'room.rental.getImages',
            ],
            [
                'uri' => 'room_rental/show_image/{image}',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'showImage'],
                'permission' => true,
                'name' => 'room.rental.show.image',
            ],
            [
                'uri' => 'room_rental/destroy_images/{image}',
                'method' => 'delete',
                'action' => [RoomRentalController::class, 'deleteImage'],
                'permission' => true,
                'name' => 'room.rental.destroy_image',
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
            [
                'uri' => 'room_rental/expiration/alarms',
                'method' => 'get',
                'action' => [RoomRentalController::class, 'alarms'],
                'permission' => true,
                'name' => 'room.rental.alarms',
            ],
        ];
    }
}
