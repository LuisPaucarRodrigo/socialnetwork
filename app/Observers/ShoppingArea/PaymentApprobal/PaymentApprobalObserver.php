<?php

namespace App\Observers\ShoppingArea\PaymentApprobal;

use App\Models\ShoppingArea\PaymentApproval;

class PaymentApprobalObserver
{
    /**
     * Handle the PaymentApproval "created" event.
     */
    public function created(PaymentApproval $paymentApproval): void
    {
        //
    }

    /**
     * Handle the PaymentApproval "updated" event.
     */
    public function updated(PaymentApproval $paymentApproval): void
    {
        //
    }

    /**
     * Handle the PaymentApproval "deleted" event.
     */
    public function deleted(PaymentApproval $paymentApproval): void
    {
        if ($paymentApproval->document) {
            $file = public_path('documents/shoppingArea/paymentApproval/' . $paymentApproval->document);
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Handle the PaymentApproval "restored" event.
     */
    public function restored(PaymentApproval $paymentApproval): void
    {
        //
    }

    /**
     * Handle the PaymentApproval "force deleted" event.
     */
    public function forceDeleted(PaymentApproval $paymentApproval): void
    {
        //
    }
}
