<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreprojectRequest;
use App\Http\Requests\PreprojectRequest\PreprojectQuoteRequest;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\Customer;
use App\Models\PhotoReport;
use App\Models\Preproject;
use App\Models\Customervisit;
use App\Models\Imagespreproject;
use App\Models\PreprojectProvidersQuote;
use App\Models\PreProjectQuote;
use App\Models\PreProjectQuoteItem;
use App\Models\PreprojectQuoteProduct;
use App\Models\PreprojectQuoteService;
use App\Models\Purchase_product;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Entry;
use App\Models\Warehouse;
use App\Models\PreprojectEntry;
use App\Models\Purchase_quote;
use App\Models\Purchasing_request;
use App\Models\Purchasing_requests_product;
use App\Models\ResourceEntry;
use App\Models\Service;
use App\Models\TypeProduct;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PreProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return Inertia::render('ProjectArea/PreProject/PreProjects', [
                'preprojects' => Preproject::with('project')->paginate(12),
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $preprojects = Preproject::where('code', 'like', "%$searchQuery%")
                ->paginate(12);

            return response()->json([
                'preprojects' => $preprojects
            ]);
        }
    }

    public function create($preproject_id = null)
    {
        return Inertia::render('ProjectArea/PreProject/CreatePreProject', [
            'preproject' => Preproject::with('project', 'contacts')->find($preproject_id),
            'customers' => Customer::with('customer_contacts')->get(),
        ]);
    }


    public function getCode($date, $code)
    {
        $year = date('Y', strtotime($date));
        $totalYearProjects = Preproject::whereYear('date', $year)->count() + 1;
        $formattedTotal = str_pad($totalYearProjects, 3, '0', STR_PAD_LEFT);
        return $year . '-' . $formattedTotal . '-' . strtoupper($code);
    }


    public function store(PreprojectRequest $request)
    {
        $data = $request->validated();
        $data['code'] = $this->getCode($data['date'], $data['code']);
        $preproject = Preproject::create($data);
        $preproject->contacts()->sync($data['contacts']);
    }


    // public function showPreprojectFacade(Preproject $preproject)
    // {
    //     $facadeName = $preproject->facade;
    //     $facadePath = '/image/facades/' . $facadeName;
    //     $path = public_path($facadePath);
    //     if (file_exists($path)) {
    //         return response()->file($path);
    //     }
    //     abort(404, 'Documento no encontrado');
    // }


    public function update(PreprojectRequest $request, Preproject $preproject)
    {
        $data = $request->validated();
        $preprojectYear = date('Y', strtotime($preproject->date));
        $requestYear = date('Y', strtotime($request->date));
        if ($preprojectYear == $requestYear) {
            $data['code'] = substr($preproject->code, 0, 9) . strtoupper($data['code']);
        } else {
            $data['code'] = $this->getCode($data['date'], $data['code']);
        }
        $preproject->update($data);
        $preproject->contacts()->sync($data['contacts']);
        return redirect()->back();
    }


    public function destroy(Preproject $preproject)
    {
        $preproject->delete();
        return redirect()->back();
    }


    public function quote($preproject_id)
    {
        $allPurchasingRequest = Purchasing_request::with([
            'purchase_quotes' => function ($query) {
                $query->where('preproject_state', true);
            }, 'purchase_quotes.purchase_quote_products.purchase_product'
        ])
            ->where('preproject_id', $preproject_id)
            ->get();

        $existingProducts = [];

        foreach ($allPurchasingRequest as $pr_item) {
            $allPurchaseQuotes = $pr_item->purchase_quotes;
            foreach ($allPurchaseQuotes as $pq_item) {
                $allPurchaseQuoteProducts = $pq_item->purchase_quote_products;
                foreach ($allPurchaseQuoteProducts as $pqp_item) {
                    $id = $pqp_item->purchase_product_id;
                    $key = array_search($id, array_column($existingProducts, 'purchase_product_id'));
                    if ($pq_item->quantity !== 0) {
                        if ($key !== false) {
                            $newQuantity = $existingProducts[$key]["quantity"] + $pqp_item->quantity;
                            $newUnitaryPrice = (($existingProducts[$key]["quantity"] * $existingProducts[$key]["unitary_price"]) +
                                ($pqp_item->quantity * $pqp_item->unitary_amount_no_igv)) / $newQuantity;
                            $existingProducts[$key]["quantity"] = $newQuantity;
                            $existingProducts[$key]["unitary_price"] = $newUnitaryPrice;
                        } else {
                            array_push($existingProducts, [
                                "profit_margin" => "",
                                "purchase_product" => $pqp_item->purchase_product,
                                "purchase_product_id" => $pqp_item->purchase_product_id,
                                "quantity" => $pqp_item->quantity,
                                "unitary_price" => $pqp_item->unitary_amount_no_igv
                            ]);
                        }
                    }
                }
            }
        }

        $preproject_products = PreprojectEntry::where('preproject_id', $preproject_id)->with('entry.inventory.purchase_product')->get();

        return Inertia::render('ProjectArea/PreProject/PreProjectQuote', [
            'preproject' => Preproject::with(
                'quote.preproject_quote_services.resource_entry.purchase_product',
                'quote.preproject_quote_services.service',
                'quote.products.purchase_product',
            )
                ->find($preproject_id),
            'existingProducts' => $existingProducts,
            'products' => Purchase_product::all(),
            'purchasing_requests' => Purchasing_request::with('products')
                ->where('preproject_id', $preproject_id)->get(),
            'services' => Service::with('purchase_product')->get(),
            'preproject_products' => $preproject_products
        ]);
    }

    public function load_resource_entries ($service_id) {
        $service = Service::find($service_id);
        $resources_entries = ResourceEntry::with('purchase_product')->where('state', true)
                                ->where('condition', 'Disponible')
                                ->where('purchase_product_id', $service->purchase_product_id)
                                ->get();
        return response()->json($resources_entries, 200);
    }

    public function accept_decline_quote(Request $request, $purchase_quote_id)
    {
        $data = $request->validate([
            "preproject_state" => "required|boolean"
        ]);
        Purchase_quote::find($purchase_quote_id)->update($data);
        return redirect()->back();
    }


    public function quote_store(PreprojectQuoteRequest $request, $quote_id = null)
    {
        $data = $request->validated();
        if ($quote_id) {
            $quote = PreProjectQuote::find($quote_id);
            $quote->update($data);
        } else {
            $preproject_quote = PreProjectQuote::create($data);
            if ($request->has("items")) {
                foreach ($request->get("items") as $item) {
                    if (count($item["resource_entries"]) !== 0){
                        foreach($item["resource_entries"] as $subItem) {
                            $service_item_with_sub = new PreprojectQuoteService([
                                'preproject_quote_id' => $preproject_quote->id,
                                'service_id' => $item["service_info"]["id"],
                                'resource_entry_id' => $subItem["id"],
                                'days' => $item["days"],
                                'profit_margin' => $item["profit_margin"],
                                'rent_price' => $item["service_info"]["rent_price"],
                            ]);
                            $preproject_quote->preproject_quote_services()->save($service_item_with_sub);
                        }
                    } else {
                        $service_item = new PreprojectQuoteService([
                            'preproject_quote_id' => $preproject_quote->id,
                            'service_id' => $item["service_info"]["id"],
                            'days' => $item["days"],
                            'profit_margin' => $item["profit_margin"],
                            'rent_price' => $item["service_info"]["rent_price"],
                            
                        ]);
                        $preproject_quote->preproject_quote_services()->save($service_item);
                    }
                }
            }
            if ($request->has("products")) {
                foreach ($request->get("products") as $item) {
                    $quoteProduct = new PreprojectQuoteProduct($item);
                    $preproject_quote->products()->save($quoteProduct);
                }
            }
        }
        return redirect()->back();
    }


    public function quote_item_store(Request $request)
    {
        $data = $request->validate([
            // "description" => 'required',
            // "unit" => 'required',
            // "days" => 'required',
            // "quantity" => 'required',
            // "unit_price" => 'required',
            // "service_id" => 'required',
            // "preproject_quote_id" => 'required',
            "resource_entry_id" => 'required',
            "service_id" => 'required|numeric',
            "preproject_quote_id" => 'required|numeric',
        ]);
        $newItem = PreprojectQuoteService::create($data);
        return response()->json(['id' => $newItem->id]);
    }


    public function quote_item_delete(PreProjectQuoteItem $quote_item_id)
    {
        $quote_item_id->delete();
        return redirect()->back();
    }


    public function quote_product_store(Request $request)
    {
        $data = $request->validate([
            'preproject_quote_id' => 'required',
            'purchase_product_id' => 'required',
            'quantity' => 'required',
            'unitary_price' => 'required',
            'profit_margin' => 'required',
        ]);
        $newProd = PreprojectQuoteProduct::create($data);
        return response()->json(['id' => $newProd->id]);
    }


    public function quote_product_delete($quote_product_id)
    {
        PreprojectQuoteProduct::find($quote_product_id)->delete();
        return redirect()->back();
    }


    public function getPDF(Preproject $preproject)
    {
        $preproject = $preproject->load('quote.preproject_quote_services', 'quote.products.purchase_product', 'preproject_entries.entry.inventory.purchase_product');
        $pdf = Pdf::loadView('pdf.CotizationPDF', compact('preproject'));
        return $pdf->stream();
        //return view('pdf.CotizationPDF', compact('preproject'));
    }

    // ------------------------------- PHOTO REPORT -------------------------------

    public function photoreport_index($preproject_id)
    {
        return Inertia::render('ProjectArea/PreProject/PhotoReport', [
            'preproject' => Preproject::find($preproject_id),
            'photoreport' => PhotoReport::where('preproject_id', $preproject_id)->first(),
        ]);
    }


    public function photoreport_store(Request $request)
    {
        $data = $request->validate([
            'excel_report' => 'sometimes|file|mimes:xls,xlsx',
            'pdf_report'   => 'sometimes|file|mimes:pdf',
            'preproject_id' => 'required',
        ]);
        if ($request->hasfile('excel_report')) {
            $excel_report = $request->file('excel_report');
            $er_name = time() . '_' . $excel_report->getClientOriginalName();
            $excel_report->move(public_path('documents/photoreports/'), $er_name);
            $data['excel_report'] = $er_name;
        }
        if ($request->hasfile('pdf_report')) {
            $pdf_report = $request->file('pdf_report');
            $pr_name = time() . '_' . $pdf_report->getClientOriginalName();
            $pdf_report->move(public_path('documents/photoreports/'), $pr_name);
            $data['pdf_report'] = $pr_name;
        }
        PhotoReport::create($data);
        return redirect()->back();
    }


    public function photoreport_update(Request $request, PhotoReport $photoreport)
    {
        $data = $request->validate([
            'excel_report' => 'nullable|sometimes|file|mimes:xls,xlsx',
            'pdf_report'   => 'nullable|sometimes|file|mimes:pdf',
        ]);
        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $this->file_delete($photoreport->$key, 'documents/photoreports/');
                $data[$key] = $this->file_store($request->file($key), 'documents/photoreports/');
            } else {
                unset($data[$key]);
            }
        }
        $photoreport->update($data);
        return redirect()->back();
    }


    public function photoreport_delete(PhotoReport $photoreport)
    {
        $files = [
            $photoreport->excel_report,
            $photoreport->pdf_report
        ];
        foreach ($files as $file) {
            $file_path = "documents/photoreports/$file";
            $path = public_path($file_path);
            if (file_exists($path)) unlink($path);
        }
        $photoreport->delete();
        return redirect()->back();
    }


    public function file_delete($filename, $path)
    {
        $file_path = $path . $filename;
        $path = public_path($file_path);
        if (file_exists($path)) unlink($path);
    }

    public function file_store($file, $path)
    {
        $name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $name);
        return $name;
    }


    public function downloadPR($report_name)
    {
        $fileName = $report_name;
        $filePath = "documents/photoreports/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }


    public function download_pdf_PR(PhotoReport $pr_id)
    {
        $fileName = $pr_id->pdf_report;
        $filePath = "documents/photoreports/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($path, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }


    public function showPR(PhotoReport $pr_id)
    {
        $fileName = $pr_id->pdf_report;
        $filePath = 'documents/photoreports/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path, ['title' => $fileName]);
        }
        abort(404, 'Documento no encontrado');
    }

    // ------------------------------- REQUEST SHOPPING -------------------------------

    public function request($id)
    {
        return Inertia::render('ProjectArea/PreProject/PurchaseRequest', [
            'purchases' => Purchasing_request::with('project')->where('preproject_id', $id)->paginate(),
            'preproject' => Preproject::find($id)
        ]);
    }

    public function request_shopping_create($id)
    {
        return Inertia::render('ProjectArea/PreProject/CreateAndUpdateRequest', [
            'allProducts' => Purchase_product::all(),
            'preproject' => Preproject::find($id),
            'typeProduct' => TypeProduct::all()
        ]);
    }

    public function request_shopping_store(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|string',
            'preproject_id' => 'required|numeric',
            'products' => 'required|array'
        ]);

        $lastRequestId = Purchasing_request::latest()->first()->id ?? 0;
        $data['code'] = 'SC' . str_pad($lastRequestId + 1, 5, '0', STR_PAD_LEFT);

        $prToCreate = Purchasing_request::create($validateData);
        $pr_products = $request->products;
        foreach ($pr_products as $item) {
            Purchasing_requests_product::create([
                'purchase_product_id' => $item['id'],
                'purchasing_request_id' => $prToCreate->id,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    public function request_shopping_details($id)
    {
        return Inertia::render('ProjectArea/PreProject/PurchasingDetails', ['details' => Purchasing_request::with('preproject', 'products')->find($id)]);
    }

    public function request_shopping_edit($id)
    {
        $purchase = Purchasing_request::with('products', 'preproject')->find($id);
        return Inertia::render('ProjectArea/PreProject/CreateAndUpdateRequest', [
            'purchase' => $purchase,
            'allProducts' => Purchase_product::all(),
        ]);
    }


    public function request_shopping_update(UpdatePurchaseRequest $request, $id)
    {
        $validateData = $request->validated();
        $purchases = Purchasing_request::with('preproject')->findOrFail($id);
        $purchases->update($validateData);

        return redirect()->back();
    }

    public function purchase_quote($id)
    {
        return Inertia::render('ProjectArea/PreProject/Expense', [
            'quotes' => Purchase_quote::with('provider', 'purchasing_requests')
                ->whereHas('purchasing_requests', function ($query) use ($id) {
                    $query->where('preproject_id', $id);
                })
                ->paginate()
        ]);
    }

    public function purchase_quote_details(Purchase_quote $id)
    {
        $expense = $id->load('purchasing_requests.preproject', 'purchase_order', 'provider', 'products');
        return Inertia::render('ProjectArea/PreProject/Details', ['expense' => $expense]);
    }

    // return Inertia::render('ProjectArea/PreProject/CreateAndUpdateRequest', [
    //     'allProducts' => Purchase_product::all(),
    //     'preproject' => Preproject::find($id),
    // ]);


    // ------------------------------- PROVIDER QUOTE -------------------------------

    // public function preproject_providersquotes_index($preproject_id)
    // {
    //     return Inertia::render('ProjectArea/PreProject/PreprojectProvidersQuotes', [
    //         'providersquotes' => PreprojectProvidersQuote::where('preproject_id', $preproject_id)->paginate(12),
    //         'preproject' => Preproject::find($preproject_id),
    //     ]);
    // }


    // public function preproject_providersquotes_store(Request $request)
    // {
    //     $data = $request->validate([
    //         'provider_quote' => 'required|file|mimes:pdf,jpeg,png,jpg',
    //         'preproject_id' => 'required',
    //     ]);
    //     if ($request->hasFile('provider_quote')) {
    //         $filename = $this->file_store($request->file('provider_quote'), 'documents/providers_quote/');
    //         $data['provider_quote'] = $filename;
    //     }
    //     PreprojectProvidersQuote::create($data);
    //     return redirect()->back();
    // }


    // public function preproject_providersquotes_delete(PreprojectProvidersQuote $providerquote_id)
    // {
    //     $this->file_delete($providerquote_id->provider_quote, 'documents/providers_quote/');
    //     $providerquote_id->delete();
    //     return redirect()->back();
    // }


    // public function preproject_providersquotes_show(PreprojectProvidersQuote $providerquote_id)
    // {
    //     $name = $providerquote_id->provider_quote;
    //     $path = 'documents/providers_quote/' . $name;
    //     $path = public_path($path);
    //     if (file_exists($path)) {
    //         return response()->file($path);
    //     }
    //     abort(404, 'Documento no encontrado');
    // }


    // public function preproject_providersquotes_download(PreprojectProvidersQuote $providerquote_id)
    // {
    //     $fileName = $providerquote_id->provider_quote;
    //     $filePath = "documents/providers_quote/$fileName";
    //     $path = public_path($filePath);
    //     if (file_exists($path)) {
    //         return response()->download($filePath, $fileName);
    //     }
    //     abort(404, 'Documento no encontrado');
    // }



    public function generarPDF()
    {
        $data = [];
        $pdf = PDF::loadView('pdf_export', $data);
        return $pdf->download('archivo-pdf.pdf');
    }

    public function index_image($preproject_id)
    {
        $images = Imagespreproject::where('preproject_id', $preproject_id)->get();
        return Inertia::render('ProjectArea/PreProject/ImageReport/index', ['images' => $images]);
    }

    public function download_image($id)
    {
        $image = Imagespreproject::find($id);
        $fileName = $image->image;
        $filePath = "image/imagereportpreproject/{$fileName}";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($path, $fileName);
        }
        abort(404, 'Imagen no encontrado');
    }

    public function delete_image($id)
    {
        $image = Imagespreproject::find($id);
        if ($image->image != null) {
            $filePath = "/image/imagereportpreproject/{$image->image}";
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
            } else {
                abort(404, 'Imagen no encontrada');
            }
        }
        Imagespreproject::destroy($id);
        return to_route('preprojects.imagereport.index');
    }

    public function show_image(Imagespreproject $image)
    {
        $fileName = $image->image;
        $filePath = '/image/imagereportpreproject/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Imagen no encontrada');
    }

    public function acceptCotization(Request $request, $quote_id)
    {
        $data = $request->validate([
            "state" => 'required',
        ]);

        $quote = PreProjectQuote::find($quote_id);
        $quote->update($data);

        return redirect()->back();
    }

    public function preproject_products_index(Preproject $preproject)
    {
        $assigned_products = PreprojectEntry::where('preproject_id', $preproject->id)->with('entry.inventory.purchase_product')->paginate(10);
        $warehouses = Warehouse::whereIn('id', [3,4])->get();
        return Inertia::render('ProjectArea/PreProject/PreProjectProducts', [
            'assigned_products' => $assigned_products,
            'warehouses' => $warehouses,
            'preproject_id' => $preproject->id
        ]);
    }

    public function preproject_warehouse_products($warehouse_id)
    {
        $products = Inventory::with('entry', 'purchase_product')->where('warehouse_id', $warehouse_id)->get();
        return response()->json(['products' => $products]);
    }

    public function preproject_inventory_products($inventory_id)
    {
        $inventory = Entry::with('normal_entry', 'purchase_entry', 'inventory.purchase_product', 'retrieval_entry')->where('inventory_id', $inventory_id)->get();
        return response()->json(['inventory' => $inventory]);
    }

    public function preproject_product_store(Request $request)
    {
        $request->validate([
            'preproject_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'margin' => 'required|numeric',
            'entry_id' => 'nullable|numeric'
        ]);

        $unitary_price = Entry::find($request->entry_id);
        PreprojectEntry::create([
            'preproject_id' => $request->preproject_id,
            'entry_id' => $request->entry_id,
            'quantity' => $request->quantity,
            'margin' => $request->margin,
            'unitary_price' => $unitary_price->unitary_price
        ]);

        return redirect()->back();
    }

}
