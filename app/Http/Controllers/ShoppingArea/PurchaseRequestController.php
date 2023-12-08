<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchasing_request;
use App\Models\Provider;
use App\Models\Purchase_order;
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

    public function destroy($id)
    {
        Purchasing_request::destroy($id);
        return to_route('purchasesrequest.index');
    }

    public function details($id)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', ['details' => Purchasing_request::find($id)]);
    }

    public function orders(Request $request)
    {   
        $request->validate([
            'provider' => 'required|string',
            'project' => 'required|string',
            'title' => 'required|string',
            'product_description' => 'required|string',
            'amount' => 'required|string|numeric',
            'quote_deadline' => 'required|date',
            'response' => 'required|string',
            'purchase_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $croppedImage = $request->file('purchase_image');
        $imageName = 'purchase_image' . time() . '.' . $croppedImage->getClientOriginalExtension();
        $croppedImage->move(public_path('image'), $imageName);
        $imageUrl = url('image/' . $imageName);

        Purchase_order::create([
            'provider_id' => $request->provider,
            'project' => $request->project,
            'title' => $request->title,
            'product_description' => $request->product_description,
            'amount' => $request->amount,
            'quote_deadline' => $request->quote_deadline,
            'response' => $request->response,
            'purchase_image' => $imageUrl,
        ]);
        return to_route('purchasesrequest.index');
    }
}
