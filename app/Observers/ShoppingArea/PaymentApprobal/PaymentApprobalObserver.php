<?php

namespace App\Observers\ShoppingArea\PaymentApprobal;

use App\Helpers\FileHandler;
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
        $path = 'documents/shoppingArea/paymentApproval/';
        if ($paymentApproval->document) {
            FileHandler::deleteFile($path, $paymentApproval->document);
        }

        if ($paymentApproval->proof_payment) {
            FileHandler::deleteFile($path, $paymentApproval->proof_payment);
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
