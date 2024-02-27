<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseQuoteRequest\CreatePurchaseQuoteRequest;
use App\Http\Requests\PurchaseRequest\CreatePurchaseRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
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
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest');
    }

    public function store(CreatePurchaseRequest $request)
    {
        $validateData = $request->validated();
        Purchasing_request::create($validateData);
    }

    public function edit($id)
    {
        $purchases = Purchasing_request::find($id);
        return Inertia::render('ShoppingArea/PurchaseRequest/CreateAndUpdateRequest', [
            'purchases' => $purchases,
        ]);
    }
    public function update(UpdatePurchaseRequest $request, $id)
    {   
        $validateData = $request->validated();
        $purchases = Purchasing_request::findOrFail($id);
        $purchases->update($validateData);

        return to_route('purchasesrequest.index');
    }

    public function index_quotes(Purchasing_request $id)
    {
        $purchases = null;
        if ($id->project_id) {
            $purchases = Purchasing_request::with('project')->find($id->id);
        } else {
            $purchases = Purchasing_request::find($id->id);
        }
        return Inertia::render('ShoppingArea/PurchaseRequest/RequestQuotes', [
            'providers' => Provider::all(),
            'purchases' => $purchases
        ]);
    }

    public function destroy($id)
    {
        Purchasing_request::destroy($id);
        return redirect()->back();
    }

    public function details(Purchasing_request $id)
    {
        if ($id->project_id) {
            return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', ['details' => Purchasing_request::with('project')->find($id->id)]);
        } else {
            return Inertia::render('ShoppingArea/PurchaseRequest/PurchasingDetails', ['details' => Purchasing_request::find($id->id)]);
        }
    }

    public function quote(CreatePurchaseQuoteRequest $request)
    {
        $imageName =  null;
        if ($request->hasFile('purchase_doc')) {
            $croppedImage = $request->file('purchase_doc');
            $imageName = 'purchase_doc' . time() . '.' . $croppedImage->getClientOriginalExtension();
            $croppedImage->move(public_path('documents/quote/'), $imageName);
        }

        Purchase_quote::create([
            'provider' => $request->provider,
            'amount' => $request->amount,
            'quote_deadline' => $request->quote_deadline,
            'response' => $request->response,
            'purchase_doc' => $imageName,
            'purchasing_request_id' => $request->purchasing_request_id,
        ]);

        return to_route('purchasesrequest.index');
    }

    public function showDocument(Purchase_quote $id)
    {
        $fileName = $id->purchase_doc;
        $filePath = public_path("documents/quote/$fileName");
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        abort(404, 'Documento no encontrado');
    }

    public function reject_request(Purchasing_request $id)
    {
        $id->update(['is_accepted' => false]);
    }
}
