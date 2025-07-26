<?php

namespace App\Observers\ShoppingArea;

use App\Models\Provider;

class ProviderObserver
{
    /**
     * Handle the Provider "created" event.
     */
    public function created(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "updated" event.
     */
    public function updated(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "deleting" event.
     */
    public function deleting(Provider $provider): void
    {
        // $provider->loadMissing('room');
        // foreach ($provider->room as $item) {
        //     $item->delete();
        // }
    }

    /**
     * Handle the Provider "deleted" event.
     */
    public function deleted(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "restored" event.
     */
    public function restored(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "force deleted" event.
     */
    public function forceDeleted(Provider $provider): void
    {
        //
    }
}
