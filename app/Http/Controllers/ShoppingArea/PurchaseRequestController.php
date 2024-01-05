<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Models\Purchasing_request;
use App\Models\Provider;
use App\Models\Purchase_order;
use App\Models\Purchase_quote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/Purchases', ['purchases' => Purchasing_request::with('project')->paginate()]);
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
            'purchases' => Purchasing_request::with('project')->find($id),
        ]);
    }

    public function destroy($id)
    {
        Purchasing_request::destroy($id);
        return redirect()->back();
    }

    public function details($id)
    {
        return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', ['details' => Purchasing_request::with('project')->find($id)]);
    }

    public function orders(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'purchasing_request_id' => 'required|numeric',
            'amount' => 'required|string|numeric',
            'quote_deadline' => 'required|date',
            'response' => 'required|string',
            'purchase_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $croppedImage = $request->file('purchase_image');
        $imageName = 'purchase_image' . time() . '.' . $croppedImage->getClientOriginalExtension();
        $croppedImage->move(public_path('image'), $imageName);
        $imageUrl = url('image/' . $imageName);

        Purchase_quote::create([
            'provider' => $request->provider,
            'amount' => $request->amount,
            'quote_deadline' => $request->quote_deadline,
            'response' => $request->response,
            'purchase_image' => $imageUrl,
            'purchasing_request_id' => $request->purchasing_request_id,
        ]);

        $purchasingRequest = Purchasing_request::find($request->purchasing_request_id);
        $purchasingRequest -> update([
            'state' => 'En Progreso'
        ]);

        return to_route('purchasesrequest.index');
    }
}
