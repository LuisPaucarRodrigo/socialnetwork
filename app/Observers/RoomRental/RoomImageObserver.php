<?php

namespace App\Observers\RoomRental;

use App\Helpers\FileHandler;
use App\Models\RoomImage;

class RoomImageObserver
{   

    private string $pathRoom = 'image/room/';

    /**
     * Handle the RoomImage "created" event.
     */
    public function created(RoomImage $roomImage): void
    {
        //
    }

    /**
     * Handle the RoomImage "updated" event.
     */
    public function updated(RoomImage $roomImage): void
    {
        if ($roomImage->isDirty('photo')) {
            $oldPhoto = $roomImage->getOriginal('photo');
            if ($oldPhoto) {
                FileHandler::deleteFile($this->pathRoom, $oldPhoto);
            }
        }
    }

    /**
     * Handle the RoomImage "deleted" event.
     */
    public function deleted(RoomImage $roomImage): void
    {
        if ($roomImage->photo) {
            FileHandler::deleteFile($this->pathRoom, $roomImage->photo);
        }
    }

    /**
     * Handle the RoomImage "restored" event.
     */
    public function restored(RoomImage $roomImage): void
    {
        //
    }

    /**
     * Handle the RoomImage "force deleted" event.
     */
    public function forceDeleted(RoomImage $roomImage): void
    {
        //
    }
}
