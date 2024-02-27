<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchase_order;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
