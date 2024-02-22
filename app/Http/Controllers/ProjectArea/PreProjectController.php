<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Models\PhotoReport;
use App\Models\Preproject;
use App\Models\Customervisit;
use App\Models\Imagespreproject;
use App\Models\PreprojectProvidersQuote;
use App\Models\PreProjectQuote;
use App\Models\PreProjectQuoteItem;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PreProjectController extends Controller
{
    public function index(){
        return Inertia::render('ProjectArea/ProjectManagement/PreProjects', [
            'preprojects' => Preproject::with('project')->paginate(10),
        ]);
    }


    public function getCode($date, $code)
    {
        $year = date('Y', strtotime($date));
        $totalYearProjects = Preproject::whereYear('date', $year)->count() + 1;
        $formattedTotal = str_pad($totalYearProjects, 3, '0', STR_PAD_LEFT);
        return $year . '-' . $formattedTotal . '-' . strtoupper($code);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'customer' => 'required|string',
            'phone' => 'required',
            'description' => 'required|string',
            'address' => 'required|string',
            'date' => 'required|date',
            'code' => 'required',
            'observation' => 'required|string',
            'facade' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);
        $facade = $request->file('facade');
        $facadeName = time() . '_' . $facade->getClientOriginalName();
        $facade->move(public_path('image/facades/'), $facadeName);
        $data['facade'] = $facadeName;
        $data['code'] = $this->getCode($data['date'], $data['code']);
        Preproject::create($data);
    }


    public function showPreprojectFacade(Preproject $preproject)
    {
        $facadeName = $preproject->facade;
        $facadePath = '/image/facades/' . $facadeName;
        $path = public_path($facadePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }


    public function update(Request $request, Preproject $preproject)
    {
        $data = $request->validate([
            'customer' => 'required',
            'phone' => 'required',
            'description' => 'required',
            'address' => 'required',
            'date' => 'required',
            'code' => 'required',
            'observation' => 'required',
            'facade' => 'nullable|sometimes|file|mimes:pdf,jpeg,png,jpg',
        ]);
        $preprojectYear = date('Y', strtotime($preproject->date));
        $requestYear = date('Y', strtotime($request->date));
        if ($preprojectYear == $requestYear) {
            $data['code'] = substr($preproject->code, 0, 9) . strtoupper($data['code']);
        } else {
            $data['code'] = $this->getCode($data['date'], $data['code']);
        }
        if ($request->hasFile('facade')) {
            $facadeName = $preproject->facade;
            $facadePath = "image/facades/$facadeName";
            $path = public_path($facadePath);
            if (file_exists($path)) {
                unlink($path);
            } else {
                dd("El archivo no existe en la ruta: $facadePath");
            }
            $facade = $request->file('facade');
            $facadeName = time() . '_' . $facade->getClientOriginalName();
            $facade->move(public_path('image/facades/'), $facadeName);
            $data['facade'] = $facadeName;
        } else {
            unset($data['facade']);
        }
        $preproject->update($data);
        return redirect()->back();
    }


    public function destroy(Preproject $preproject)
    {
        $facadeName = $preproject->facade;
        $preproject->delete();
        $facadePath = "image/facades/$facadeName";
        $path = public_path($facadePath);
        if (file_exists($path)) {
            unlink($path);
            $preproject->delete();
        } else {
            dd("El archivo no existe en la ruta: $facadePath");
        }
        return to_route('preprojects.index');
    }


    public function quote($preproject_id)
    {
        return Inertia::render('ProjectArea/ProjectManagement/PreProjectQuote', [
            'preproject' => Preproject::with('quote.items')->find($preproject_id),
        ]);
    }


    public function quote_store(Request $request, $quote_id = null)
    {
        $data = $request->validate([
            "name" => 'required',
            "date" => 'required',
            "supervisor" => 'required',
            "boss" => 'required',
            "rev" => 'required',
            "deliverable_time" => 'required',
            "validity_time" => 'required',
            "observations" => 'required',
            'preproject_id' => 'required'
        ]);
        if ($quote_id) {
            $quote = PreProjectQuote::find($quote_id);
            $quote->update($data);
        } else {
            $preproject_quote = PreProjectQuote::create($data);
            if ($request->has("items")) {
                foreach ($request->get("items") as $item) {
                    $quoteItem = new PreProjectQuoteItem($item);
                    $preproject_quote->items()->save($quoteItem);
                }
            }
        }
        return redirect()->back();
    }


    public function quote_item_store(Request $request)
    {
        $data = $request->validate([
            "description" => 'required',
            "unit" => 'required',
            "days" => 'required',
            "quantity" => 'required',
            "unit_price" => 'required',
            "preproject_quote_id" => 'required',
        ]);
        PreProjectQuoteItem::create($data);
        return redirect()->back();
    }


    public function quote_item_delete(PreProjectQuoteItem $quote_item_id)
    {
        $quote_item_id->delete();
        return redirect()->back();
    }

    public function getPDF(Preproject $preproject)
    {
        $preproject = $preproject->load('quote.items');
        $pdf = Pdf::loadView('pdf.CotizationPDF', compact('preproject'));
        return $pdf->stream();
        //return view('pdf.CotizationPDF', compact('preproject'));
    }

    // ------------------------------- PHOTO REPORT -------------------------------

    public function photoreport_index($preproject_id)
    {
        return Inertia::render('ProjectArea/ProjectManagement/PhotoReport', [
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
            return response()->download($filePath, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }


    public function showPR($report_name)
    {
        $fileName = $report_name;
        $filePath = 'documents/photoreports/' . $fileName;
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }



    // ------------------------------- PROVIDER QUOTE -------------------------------

    public function preproject_providersquotes_index($preproject_id)
    {
        return Inertia::render('ProjectArea/ProjectManagement/PreprojectProvidersQuotes', [
            'providersquotes' => PreprojectProvidersQuote::where('preproject_id', $preproject_id)->paginate(12),
            'preproject' => Preproject::find($preproject_id),
        ]);
    }


    public function preproject_providersquotes_store(Request $request)
    {
        $data = $request->validate([
            'provider_quote' => 'required|file|mimes:pdf,jpeg,png,jpg',
            'preproject_id' => 'required',
        ]);
        if ($request->hasFile('provider_quote')) {
            $filename = $this->file_store($request->file('provider_quote'), 'documents/providers_quote/');
            $data['provider_quote'] = $filename;
        }
        PreprojectProvidersQuote::create($data);
        return redirect()->back();
    }


    public function preproject_providersquotes_delete(PreprojectProvidersQuote $providerquote_id)
    {
        $this->file_delete($providerquote_id->provider_quote, 'documents/providers_quote/');
        $providerquote_id->delete();
        return redirect()->back();
    }


    public function preproject_providersquotes_show(PreprojectProvidersQuote $providerquote_id)
    {
        $name = $providerquote_id->provider_quote;
        $path = 'documents/providers_quote/' . $name;
        $path = public_path($path);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }


    public function preproject_providersquotes_download(PreprojectProvidersQuote $providerquote_id)
    {
        $fileName = $providerquote_id->provider_quote;
        $filePath = "documents/providers_quote/$fileName";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($filePath, $fileName);
        }
        abort(404, 'Documento no encontrado');
    }



    public function generarPDF()
    {
        $data = [];
        $pdf = PDF::loadView('pdf_export', $data);
        return $pdf->download('archivo-pdf.pdf');
    }

    public function index_image($preproject_id)
    {
        $images = Imagespreproject::where('preproject_id', $preproject_id)->get();
        return Inertia::render('ProjectArea/ProjectManagement/ImageReport/index', ['images' => $images]);
    }

    public function download_image(Imagespreproject $id)
    {
        $fileName = $id->image;
        $filePath = "/image/imagereportpreproject/1708397485.png";
        $path = public_path($filePath);
        if (file_exists($path)) {
            return response()->download($filePath, $fileName);
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
            "state"=>'required',
        ]);

        $quote = PreProjectQuote::find($quote_id);
        $quote->update($data);
        
        return redirect()->back();
    }
}
