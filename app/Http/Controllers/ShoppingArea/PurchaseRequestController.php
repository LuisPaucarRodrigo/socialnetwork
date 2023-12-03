<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchasing_request;
use App\Models\Provider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/Purchases', ['purchases' => Purchasing_request::paginate()]);
    }

    public function create()
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateRequest');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'product_description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        Purchasing_request::create([
            'title' => $request->title,
            'product_description' => $request->product_description,
            'due_date' => $request->due_date,
        ]);

        return to_route('purchasesrequest.index');
    }

    public function index_quotes($id)
    {

        return Inertia::render('ShoppingArea/PurchaseRequest/RequestQuotes', [
            'providers' => Provider::all(),
            'purchases' => Purchasing_request::find($id),
        ]);
    }
}
