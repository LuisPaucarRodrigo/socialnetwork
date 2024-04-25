<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Purchase_quote;
use Carbon\Carbon;
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
        ]);
    }

    public function search($request)
    {
        $searchTerm = strtolower($request); // Convertir a minúsculas

        $query = Purchase_quote::with('purchasing_requests', 'purchase_order', 'payment')
            ->has('payment');

        $payment_by_request_code = $query->get()->filter(function ($purchase_quote) use ($searchTerm) {
            return str_contains(strtolower($purchase_quote->purchasing_requests->code), $searchTerm);
        });

        $payment_by_code = $query->get()->filter(function ($purchase_quote) use ($searchTerm) {
            // Verificar si algún pago tiene el código buscado
            foreach ($purchase_quote->payment as $payment) {
                if (str_contains(strtolower($payment->cod_payment), $searchTerm)) {
                    return true;
                }
            }
            return false;
        });


        $payment_by_request_title = $query->get()->filter(function ($purchase_quote) use ($searchTerm) {
            return str_contains(strtolower($purchase_quote->purchasing_requests->title), $searchTerm);
        });

        $combined_purchase_quotes = $payment_by_request_code->merge($payment_by_code)->merge($payment_by_request_title)->unique();

        return Inertia::render('ShoppingArea/Payments/index', [
            'payments' => $combined_purchase_quotes,
            'search' => $request
        ]);
    }

    public function alarm_payments()
    {
        $now = Carbon::now();
        $currentDateUpdate = $now->subHours(5);
        $payment3Days = Payment::where('state', 0)
            ->where('register_date', '<=', $currentDateUpdate->copy()->addDays(3))
            ->get();
        $payment7Days = Payment::where('state', 0)
            ->where('register_date', '>=', $currentDateUpdate->copy()->addDays(3))
            ->where('register_date', '<=', $currentDateUpdate->copy()->addDays(7))
            ->get();
        return response()->json([
            'payment3Days' => $payment3Days,
            'payment7Days' => $payment7Days,
        ]);
    }
}
