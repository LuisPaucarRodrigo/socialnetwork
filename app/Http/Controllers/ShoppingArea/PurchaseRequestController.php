<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseQuoteRequest\CreatePurchaseQuoteRequest;
use App\Http\Requests\PurchaseRequest\CreatePurchaseRequest;
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

    public function store(CreatePurchaseRequest $request)
    {
        $validateData = $request->validate();
        Purchasing_request::create($validateData);
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

    public function quote(CreatePurchaseQuoteRequest $request)
    {
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
        $purchasingRequest->update([
            'state' => 'En Progreso'
        ]);

        return to_route('purchasesrequest.index');
    }

    public function reject_request (Request $request, Purchasing_request $id) {
        $id->update(['state'=>'Rechazado']);
    }
}
