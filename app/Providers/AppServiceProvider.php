<?php

namespace App\Providers;

use App\Models\Provider;
use App\Models\Room;
use App\Models\RoomDocument;
use App\Models\RoomImage;
use App\Models\ShoppingArea\PaymentApproval;
use App\Observers\RoomRental\RoomDocumentObserver;
use App\Observers\RoomRental\RoomImageObserver;
use App\Observers\RoomRental\RoomObserver;
use App\Observers\ShoppingArea\PaymentApprobal\PaymentApprobalObserver;
use App\Observers\ShoppingArea\ProviderObserver;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'flash' => fn() => [
                'message' => session('message'),
            ],
        ]);
        PaymentApproval::observe(PaymentApprobalObserver::class);
        Room::observe(RoomObserver::class);
        RoomDocument::observe(RoomDocumentObserver::class);
        RoomImage::observe(RoomImageObserver::class);
        Provider::observe(ProviderObserver::class);
    }
}
