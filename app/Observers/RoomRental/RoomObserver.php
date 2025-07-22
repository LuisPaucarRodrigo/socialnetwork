<?php

namespace App\Observers\RoomRental;

use App\Helpers\FileHandler;
use App\Models\Room;

class RoomObserver
{
    /**
     * Handle the Room "created" event.
     */
    

    public function created(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "updated" event.
     */
    public function updated(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "deleting" event.
     */

    public function deleting(Room $room): void
    {
        $room->loadMissing('room_documents', 'room_images');
        foreach ($room->room_documents as $item) {
            $item->delete();
        }
        foreach ($room->room_images as $item) {
            $item->delete();
        }
    }

    /**
     * Handle the Room "deleted" event.
     */
    public function deleted(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "restored" event.
     */
    public function restored(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "force deleted" event.
     */
    public function forceDeleted(Room $room): void
    {
        //
    }
}
