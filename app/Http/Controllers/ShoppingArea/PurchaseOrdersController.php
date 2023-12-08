<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseOrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchaseOrders/Orders');
    }
}
