<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest\CreatePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/Payments/index',[
            'payments' => Payment::paginate()
        ]);
    }

    public function payment_pay(CreatePaymentRequest $request,$id)
    {
        $validatedata = $request->validated();
        $payment = Payment::find($id);
        $payment->update($validatedata);
    }
}
