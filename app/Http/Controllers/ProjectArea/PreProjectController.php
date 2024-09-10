<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreprojectRequest;
use App\Http\Requests\PreprojectRequest\PreprojectQuoteRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\Customer;
use App\Models\PhotoReport;
use App\Models\Preproject;
use App\Models\Imagespreproject;
use App\Models\PreProjectQuote;
use App\Models\PreprojectQuoteProduct;
use App\Models\PreprojectQuoteService;
use App\Models\Purchase_product;
use App\Models\Inventory;
use App\Models\Entry;
use App\Models\Warehouse;
use App\Models\PreprojectEntry;
use App\Models\Purchase_quote;
use App\Models\Purchasing_request;
use App\Models\Purchasing_requests_product;
use App\Models\ResourceEntry;
use App\Models\Service;
use App\Models\Code;
use App\Models\Customers_contact;
use App\Models\PreprojectCode;
use App\Models\PreprojectTitle;
use App\Models\ReportStage;
use App\Models\Title;
use App\Models\TitleCode;
use App\Models\User;
use App\Models\TypeProduct;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;
use ZipArchive;

class PreProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $preprojects_status = $request->input('preprojects_status');
            return Inertia::render('ProjectArea/PreProject/PreProjects', [
                'preprojects' => Preproject::where('status', $preprojects_status)
                    ->orderBy('created_at', 'desc')
                    ->paginate(12),
                'preprojects_status' => $preprojects_status,
                'users' => User::select('id', 'name')->orderBy('name', 'asc')->get()
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $preprojects_status = $request->input('preprojects_status');
            $preprojects = Preproject::orWhere('code', 'like', "%$searchQuery%")
                ->orWhere('description', 'like', "%$searchQuery%")
                ->where('status', $preprojects_status)
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return response()->json([
                'preprojects' => $preprojects,
            ]);
        }
    }

    public function create($preproject_id = null)
    {
        return Inertia::render('ProjectArea/PreProject/CreatePreProject', [
            'preproject' => Preproject::with('project', 'customer', 'contacts')->find($preproject_id),
            'customers' => Customer::with('customer_contacts')->where('id', '!=', 1)->get(),
            'titles' => Title::all(),
            'stages' => ReportStage::select('id', 'name')->get(),
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
        DB::beginTransaction();
        try {
            $data['code'] = $this->getCode($data['date'], $data['code']);
            $preproject = Preproject::create($data);
            if (isset($data['reportStages'])) {
                foreach ($data['reportStages'] as $report) {
                    $dataCode = TitleCode::where('title_id', $report['title_id'])->get();
                    $preprojectTitle = PreprojectTitle::create([
                        'type' => $report['name'],
                        'preproject_id' => $preproject->id,
                    ]);
                    foreach ($dataCode as $codes) {
                        PreprojectCode::create([
                            'preproject_title_id' => $preprojectTitle->id,
                            'code_id' => $codes->code_id
                        ]);
                    }
                }
            }
            $preproject->contacts()->sync($data['contacts']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['errorMessage' => $e->getMessage()]);
        }
    }

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
        $preproject->load('quote');
        if ($preproject->quote) {
            $preproject_quote_services = PreprojectQuoteService::whereHas('resource_entry')
                ->where('state', null)
                ->where('preproject_quote_id', $preproject->quote->id)->get();
            foreach ($preproject_quote_services as $item) {
                ResourceEntry::find($item->resource_entry_id)->update(['condition' => 'Disponible']);
            }
        }
        $preproject->delete();
        return redirect()->back();
    }


    public function quote($preproject_id)
    {
        $allPurchasingRequest = Purchasing_request::with([
            'purchase_quotes' => function ($query) {
                $query->where('preproject_state', true);
            },
            'purchase_quotes.purchase_quote_products.purchase_product'
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
                    if ($pqp_item->quantity !== 0) {
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

    public function load_resource_entries($service_id)
    {
        $service = Service::find($service_id);
        $resources_entries = ResourceEntry::with('purchase_product')
            ->where('state', true)
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
                    if (count($item["resource_entries"]) !== 0) {
                        foreach ($item["resource_entries"] as $subItem) {
                            $service_item_with_sub = new PreprojectQuoteService([
                                'preproject_quote_id' => $preproject_quote->id,
                                'service_id' => $item["service_info"]["id"],
                                'resource_entry_id' => $subItem["id"],
                                'days' => $item["days"],
                                'profit_margin' => $item["profit_margin"],
                                'rent_price' => $item["service_info"]["rent_price"],
                            ]);
                            $preproject_quote->preproject_quote_services()->save($service_item_with_sub);
                            ResourceEntry::find($subItem["id"])->update([
                                'condition' => "Reservado"
                            ]);
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
            "preproject_quote_id" => 'required|numeric',
            "service_info" => 'required',
            "resource_entries" => 'array|nullable',
            "days" => 'required',
            "profit_margin" => 'required',
        ]);

        $ids = [];

        if ($data["resource_entries"]) {
            foreach ($data["resource_entries"] as $item) {
                $res = PreprojectQuoteService::create([
                    'preproject_quote_id' => $data["preproject_quote_id"],
                    'service_id' => $data['service_info']['id'],
                    'resource_entry_id' => $item['id'],
                    'days' => $data['days'],
                    'profit_margin' => $data['profit_margin'],
                    'rent_price' => $data['service_info']['rent_price'],
                ]);
                array_push($ids, $res->id);
            }
        } else {
            $res = PreprojectQuoteService::create([
                'preproject_quote_id' => $data["preproject_quote_id"],
                'service_id' => $data['service_info']['id'],
                'days' => $data['days'],
                'profit_margin' => $data['profit_margin'],
                'rent_price' => $data['service_info']['rent_price'],
            ]);
            array_push($ids, $res->id);
        }
        return response()->json(['ids' => $ids]);
    }


    public function quote_item_delete(Request $request)
    {
        $ids = $request->input('ids');
        foreach ($ids as $id) {
            PreprojectQuoteService::find($id)->delete();
        }
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


    public function preproject_quote_rejected(Request $request)
    {
        $quote = PreProjectQuote::where('preproject_id', $request->preproject_id)->first();
        if (!$quote || $quote->state) {
            abort(403, 'Recarga la página');
        }
        PreprojectEntry::where('preproject_id', $request->preproject_id)->delete();
        $preServEnt = PreprojectQuoteService::where('preproject_quote_id', $quote->id)->get();
        foreach ($preServEnt as $item) {
            ResourceEntry::find($item->resource_entry_id)?->update(["condition" => "Disponible"]);
        }
        $quote->delete();
    }



    public function preproject_quote_canceled(Request $request)
    {
        $quote = PreProjectQuote::where('preproject_id', $request->preproject_id)->first();
        $preproject = Preproject::with('project')->find($request->preproject_id);
        if ($preproject->project || !$quote || $quote->state) {
            abort(403, 'Recarga la página');
        }
        PreprojectEntry::where('preproject_id', $request->preproject_id)->delete();
        $preServEnt = PreprojectQuoteService::where('preproject_quote_id', $quote->id)->get();
        foreach ($preServEnt as $item) {
            ResourceEntry::find($item->resource_entry_id)?->update(["condition" => "Disponible"]);
        }
        $preproject->update(['status' => false]);
        $quote->delete();
    }




    public function getPDF(Preproject $preproject)
    {
        $preproject = $preproject->load(
            'quote.preproject_quote_services.service',
            'quote.products.purchase_product',
            'preproject_entries.entry.inventory.purchase_product'
        );

        $productsFromQuotes = $preproject->quote->products;
        $productsFromWarehouses = $preproject->preproject_entries;
        $servicesResources = $preproject->quote->preproject_quote_services;

        $finalProducts = [];

        foreach ($productsFromQuotes as $pfq) {
            array_push($finalProducts, [
                "purchase_product_id" => $pfq->purchase_product_id,
                "name" => $pfq->purchase_product->name,
                "unit" => $pfq->purchase_product->unit,
                "quantity" => $pfq->quantity,
                "unitary_price" => $pfq->unitary_price_with_margin,
            ]);
        }

        foreach ($productsFromWarehouses as $pfw) {
            $id = $pfw->entry->inventory->purchase_product_id;
            $key = array_search($id, array_column($finalProducts, 'purchase_product_id'));
            if ($key !== false) {
                $newQuantity = $finalProducts[$key]["quantity"] + $pfw->quantity;
                $newUnitaryPrice = (
                    ($finalProducts[$key]["quantity"] * $finalProducts[$key]["unitary_price"]) +
                    ($pfw->quantity * $pfw->unitary_price_with_margin)
                ) / $newQuantity;
                $finalProducts[$key]["quantity"] = $newQuantity;
                $finalProducts[$key]["unitary_price"] = $newUnitaryPrice;
            } else {
                array_push($finalProducts, [
                    "purchase_product_id" => $pfw->entry->inventory->purchase_product_id,
                    "name" => $pfw->entry->inventory->purchase_product->name,
                    "unit" => $pfw->entry->inventory->purchase_product->unit,
                    "quantity" => $pfw->quantity,
                    "unitary_price" => $pfw->unitary_price_with_margin,
                ]);
            }
        }

        $finalServices = [];

        foreach ($servicesResources as $sr) {
            $id = $sr->service_id;
            $key = array_search($id, array_column($finalServices, 'service_id'));
            if ($key !== false) {
                $finalServices[$key]["resources_quantity"] += 1;
            } else {
                array_push($finalServices, [
                    "service_id" => $sr->service_id,
                    "name" => $sr->service->name,
                    "days" => $sr->days,
                    "resources_quantity" => 1,
                    "rent_price" => $sr->rent_price_with_margin,
                ]);
            }
        }

        $pdf = Pdf::loadView('pdf.CotizationPDF', compact('preproject', 'finalProducts', 'finalServices'));
        return $pdf->stream();
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

    public function index_image($preproject_id)
    {
        $preprojectImages = PreprojectTitle::with('preprojectCodes.code', 'preprojectCodes.imagecodepreprojet',)->where('preproject_id', $preproject_id)->get();
        $imagesCode = Imagespreproject::all();
        $imagesCode->each(function ($url) {
            $url->image = url('/image/imagereportpreproject/' . $url->image);
        });
        $preprojectImages->each(function ($preprojectTitle) {
            $preprojectTitle->preprojectCodes->each(function ($preprojectCode) {
                $preprojectCode->imagecodepreprojet->each(function ($imagecodepreprojet) {
                    $imagecodepreprojet->image = url('image/imagereportpreproject/' . $imagecodepreprojet->image);
                });
            });
        });
        return Inertia::render('ProjectArea/PreProject/ImageReport/index', [
            'preprojectImages' => $preprojectImages,
            'imagesCode' => $imagesCode,
            'preproject' => Preproject::select('id')->find($preproject_id),
        ]);
    }

    // public function filterCodePhoto($id)
    // {
    //     $code = PreprojectCode::with('code')->where("id", $id)->get();
    //     $codesWithStatus = [];
    //     foreach ($code as $preprojectCode) {
    //         $code = $preprojectCode->code;
    //         $codesWithStatus[] = [
    //             'id' => $preprojectCode->id,
    //             'code' => $code->code,
    //             'status' => $preprojectCode->status,
    //             'description' => $code->description,
    //         ];
    //     }
    //     $data = Imagespreproject::where("preproject_code_id", $id)->get();
    //     $data->each(function ($url) {
    //         $url->image = url('/image/imagereportpreproject/' . $url->image);
    //     });
    //     return response()->json(['images' => $data, 'codes' => $codesWithStatus]);
    // }

    public function approve_reject_image(Request $request, $id)
    {
        $data = $request->validate([
            'state' => 'required|boolean',
            'observation' => 'nullable|string'
        ]);
        $image = Imagespreproject::find($id);
        $image->update([
            'observation' => $data['observation'],
            'state' => $data['state']
        ]);
    }

    public function approve_code($id)
    {
        $code = PreprojectCode::with('imagecodepreprojet')->find($id);
        $code->imagecodepreprojet->each(function ($image) {
            if ($image->state !== 1) {
                $filePath = "image/imagereportpreproject/{$image->image}";
                $path = public_path($filePath);
                if (file_exists($path)) {
                    unlink($path);
                } else {
                    abort(404, 'Imagen no encontrada');
                }
                $image->delete();
            }
        });
        $code->update([
            'status' => 'Aprobado',
        ]);
    }

    public function approve_title($id)
    {
        $preproject_title_first = PreprojectTitle::select('id', 'state')->find($id);
        if ($preproject_title_first->state) {
            $preproject_title_first->update([
                'state' => null,
            ]);
        } else {
            $preproject_title_first->update([
                'state' => 1,
            ]);
        }
    }

    public function download_image($id)
    {
        $image = Imagespreproject::find($id);
        $fileName = $image->image;
        $filePath = "image/imagereportpreproject/{$fileName}";
        $path = public_path($filePath);
        if (file_exists($path)) {
            $url = url($filePath);
            return response()->json(['url' => $url]);
        }
        abort(404, 'Imagen no encontrado');
    }

    public function download_report($preproject_title_id)
    {
        $preprojectImages = PreprojectTitle::with('preprojectCodes.code', 'preprojectCodes.imagecodepreprojet')->find($preproject_title_id);
        // dd($preprojectImages);
        $preproject = Preproject::find($preprojectImages->preproject_id);
        $customer = Customers_contact::find($preproject->subcustomer_id);
        $pdf = Pdf::loadView('pdf.ReportPreProject', compact('preprojectImages', 'customer'));
        return $pdf->stream();
    }

    public function delete_image($id)
    {
        $image = Imagespreproject::find($id);
        if ($image?->image != null) {
            $filePath = "image/imagereportpreproject/{$image->image}";
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
            } else {
                abort(404, 'Imagen no encontrada');
            }
        }
        Imagespreproject::destroy($id);
    }

    public function show_image(Imagespreproject $image)
    {

        $fileName = $image->image;
        $filePath = 'image/imagereportpreproject/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            $url = url($filePath);
            return response()->json(['url' => $url]);
        }
        abort(404, 'Imagen no encontrada');
    }

    public function downloadKmz($preproject_title_id)
    {   
        $coordinates = PreprojectCode::with('imagecodepreprojet')->where('preproject_title_id',$preproject_title_id)->get();
        $kmlPlacemarks = '';
        foreach ($coordinates->imagecodepreprojet as $coord) {
            $longitude = $coord['longitude'];
            $latitude = $coord['latitude'];
            $name = $coord['name'];
            $kmlPlacemarks .= <<<KML
        <Placemark>
            <name>$name</name>
            <Point>
                <coordinates>$longitude,$latitude,0</coordinates>
            </Point>
        </Placemark>
        KML;
        }

        $kmlContent = <<<KML
        <kml xmlns="http://www.opengis.net/kml/2.2">
            <Document>
                $kmlPlacemarks
            </Document>
        </kml>
        KML;
        // Crear un archivo temporal para el KML
        $kmlFile = tempnam(sys_get_temp_dir(), 'kml');
        file_put_contents($kmlFile, $kmlContent);

        // Comprimir el archivo KML en un archivo KMZ
        $zip = new ZipArchive();
        $kmzFile = tempnam(sys_get_temp_dir(), 'kmz') . '.kmz';
        if ($zip->open($kmzFile, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($kmlFile, 'doc.kml');
            $zip->close();
        }

        // Eliminar el archivo KML temporal
        unlink($kmlFile);

        // Preparar la descarga del archivo KMZ
        header('Content-Type: application/vnd.google-earth.kmz');
        header('Content-Disposition: attachment; filename="locations.kmz"');
        readfile($kmzFile);

        // Eliminar el archivo KMZ temporal después de la descarga
        unlink($kmzFile);
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
        $warehouses = Warehouse::whereIn('id', [3, 4])->get();
        return Inertia::render('ProjectArea/PreProject/PreProjectProducts', [
            'assigned_products' => $assigned_products,
            'warehouses' => $warehouses,
            'preproject' => $preproject->load('project')
        ]);
    }

    public function preproject_warehouse_products($warehouse_id)
    {
        $products = Inventory::with('entry', 'purchase_product')->where('warehouse_id', $warehouse_id)->get();
        return response()->json(['products' => $products]);
    }

    public function preproject_inventory_products($inventory_id)
    {
        $inventory = Entry::with('normal_entry', 'purchase_entry', 'inventory.purchase_product', 'retrieval_entry')
            ->where('inventory_id', $inventory_id)
            ->get()
            ->filter(function ($item) {
                return $item->quantity_available > 0;
            })
            ->toArray();
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

    public function preproject_users(Request $request)
    {
        $request->validate([
            'preproject_id' => 'required',
            'user_id_array' => 'required'
        ]);

        $preproject = Preproject::find($request->preproject_id);

        $preproject->users()->sync($request->user_id_array, ['timestamps' => true]);
    }

    //codes

    public function showCodes()
    {
        return Inertia::render('ProjectArea/PreProject/Codes', [
            'codes' => Code::paginate(20)
        ]);
    }

    public function postCode(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        Code::create([
            'code' => $request->code,
            'description' => $request->description
        ]);
    }

    public function putCode(Request $request, Code $code)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $code->update([
            'code' => $request->code,
            'description' => $request->description
        ]);
    }

    public function deleteCode(Code $code)
    {
        $code->delete();
        return redirect()->back();
    }

    //titles

    public function showTitles()
    {
        return Inertia::render('ProjectArea/PreProject/Titles', [
            'titles' => Title::with('codes')->paginate(10),
            'codes' => Code::all(),
            'stages' => ReportStage::select('id', 'name')->get(),
        ]);
    }

    public function postTitle(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'code_id_array' => 'required|array'
        ]);

        $title = Title::create($data);

        $title->codes()->attach($data['code_id_array']);
    }

    public function putTitle(Request $request, $title_id)
    {
        $data = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'code_id_array' => 'required|array'
        ]);

        $title = Title::find($title_id);
        $title->update($data);

        $title->codes()->sync($data['code_id_array'], ['timestamps' => true]);
    }

    public function deleteTitle(Title $title)
    {
        $title->delete();
        return redirect()->back();
    }
}
