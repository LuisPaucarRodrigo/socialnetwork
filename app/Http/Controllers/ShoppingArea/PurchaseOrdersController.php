<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchase_order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PurchaseOrdersController extends Controller
{
    public function index() {
        return Inertia::render('ShoppingArea/PurchaseOrders/Orders', ['orders' => Purchase_order::with('purchase_quote.purchasing_requests.project')->paginate()]);
    }

    public function history() {
        $completedOrders = Purchase_order::with('purchase_quote.purchasing_requests.project')
                                         ->where('state', 'Completada')
                                         ->paginate();
    
        return Inertia::render('ShoppingArea/PurchaseOrders/HistoryOrders', ['orders' => $completedOrders]);
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
        Log::info($today->copy()->addDays(3));
        Log::info($today->copy()->addDays(3));
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

    public function purchase_order_view ($purchase_order_id){
        return Inertia::render('ShoppingArea/PurchaseOrders/OrderDetails',[
            'purchase_order' => Purchase_order::with('purchase_quote.purchasing_requests.project.preproject')->find($purchase_order_id)
        ]);
    }
}
