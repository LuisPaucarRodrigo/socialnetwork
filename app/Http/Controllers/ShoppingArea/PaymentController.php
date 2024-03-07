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
        // return Inertia::render('ShoppingArea/Payments/index',[
        //     'payments' => Purchase_quote::with('purchase_order')->paginate()
        // ]);
        return Inertia::render('ShoppingArea/Payments/index',[
            'payments' => Payment::with('purchase_quote.purchasing_requests')->paginate()
        ]);
    }

    public function payment_pay(UpdatePaymentRequest $request)
    {
        $validatedata = $request->validated();
        $payment = Payment::find($validatedata['payment_id']);
        $payment->update($validatedata);
    }
    
}
