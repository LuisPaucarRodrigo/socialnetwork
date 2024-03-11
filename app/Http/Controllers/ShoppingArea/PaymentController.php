<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Purchase_quote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/Payments/index', [
            'payments' => Purchase_quote::with('purchasing_requests', 'purchase_order', 'payment')
                ->has('payment')
                ->paginate()
        ]);

    }

    public function payment_pay(UpdatePaymentRequest $request)
    {
        $validatedata = $request->validated();
        $payment = Payment::find($validatedata['payment_id']);
        $payment->update($validatedata);
        $purchase_quote = Purchase_quote::find($payment->purchase_quote_id);
        $purchase_quote->update([
            'change_value' => $validatedata['price_dolar']
        ]) ;
    }
}
