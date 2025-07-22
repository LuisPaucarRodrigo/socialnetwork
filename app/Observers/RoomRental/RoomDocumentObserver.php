<?php

namespace App\Observers\RoomRental;

use App\Helpers\FileHandler;
use App\Models\RoomDocument;

class RoomDocumentObserver
{
    /**
     * Handle the RoomDocument "created" event.
     */
    private string $pathRoomDocument = 'documents/roomrental/room_documents/';

    public function created(RoomDocument $roomDocument): void
    {
        //
    }

    /**
     * Handle the RoomDocument "updated" event.
     */
    public function updated(RoomDocument $roomDocument): void
    {
        if ($roomDocument->isDirty('archive')) {
            $oldPhoto = $roomDocument->getOriginal('archive');
            if ($roomDocument->archive) {
                FileHandler::deleteFile($this->pathRoomDocument, $oldPhoto);
            }
        }
    }

    /**
     * Handle the RoomDocument "deleted" event.
     */
    public function deleted(RoomDocument $roomDocument): void
    {
        if ($roomDocument->archive) {
            FileHandler::deleteFile($this->pathRoomDocument, $roomDocument->archive);
        }
    }

    /**
     * Handle the RoomDocument "restored" event.
     */
    public function restored(RoomDocument $roomDocument): void
    {
        //
    }

    /**
     * Handle the RoomDocument "force deleted" event.
     */
    public function forceDeleted(RoomDocument $roomDocument): void
    {
        //
    }
}
