<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchase_order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PurchaseOrdersController extends Controller
{
    public function index() {
        return Inertia::render('ShoppingArea/PurchaseOrders/Orders', ['orders' => Purchase_order::with('purchase_quote.purchasing_requests.project')->paginate()]);
    }


    public function state(Request $request, $id){
        $request->validate([
            'state' => 'required'
        ]);
        $purchase_order = Purchase_order::find($id);
        $purchase_order -> update([
            'state' => $request->state
        ]);
    }

    public function purchase_orders_alarms () {
        $today = Carbon::now();
        $purchaseOrders3d = Purchase_order::with('purchase_quote.purchasing_requests')
            ->where('state','!=', 'Completada')
            ->where('date_issue', '<=', $today->copy()->addDays(3))
            ->get();
        $purchaseOrders7d = Purchase_order::with('purchase_quote.purchasing_requests')
            ->where('state','!=', 'Completada')
            ->where('date_issue', '>=', $today->copy()->addDays(3)) 
            ->where('date_issue', '<=', $today->copy()->addDays(7)) 
            ->get();
        return response()->json([
            'purchaseOrders3d' => $purchaseOrders3d,
            'purchaseOrders7d' => $purchaseOrders7d,
        ]);
    }
}
