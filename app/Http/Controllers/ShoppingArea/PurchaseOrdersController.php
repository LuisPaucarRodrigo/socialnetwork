<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchase_order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseOrdersController extends Controller
{
    public function index()
    {

        //dd(Purchase_order::with('purchase_quote.purchasing_requests')->get());
        return Inertia::render('ShoppingArea/PurchaseOrders/Orders', ['orders' => Purchase_order::with('purchase_quote.purchasing_requests.project')->paginate()]);
    }
}
