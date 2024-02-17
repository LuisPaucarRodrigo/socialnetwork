<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Models\Preproject;
use App\Models\Customervisit;
use App\Models\PreProjectQuote;
use App\Models\PreProjectQuoteItem;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

class PreProjectController extends Controller
{

    //Visit
    public function showVisits()
    {
        return Inertia::render('ProjectArea/ProjectManagement/Visits', [
            'visits' => Customervisit::with('preproject')->paginate(10),
        ]);
    }

    public function storeVisits(Request $request)
    {
        $request->validate([
            'customer' => 'required|string',
            'phone' => 'required',
            'description' => 'required|string',
            'address' => 'required|string',
            'date' => 'required|date',
            'observation' => 'required|string',
            'facade' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $facadeName = null;
        if ($request->hasFile('facade')) {
            $facade = $request->file('facade');
            $facadeName = time() . '_' . $facade->getClientOriginalName();
            $facade->move(public_path('image/facades/'), $facadeName);
        }

        $customer_visit = Customervisit::create([
            'customer' => $request->customer,
            'phone' => $request->phone,
            'description' => $request->description,
            'address' => $request->address,
            'date' => $request->date,
            'observation' => $request->observation,
            'facade' => $facadeName
        ]);
    }

    public function destroyVisit(Customervisit $customer_visit)
    {
        $facadeName = $customer_visit->facade;
        $facadePath = "image/facades/$facadeName";
        $path = public_path($facadePath);
        if (file_exists($path)) {
            unlink($path);
            $customer_visit->delete();
        } else {
            dd("El archivo no existe en la ruta: $facadePath");
        }
        return to_route('preprojects.visits');    
    }

    public function showVisitFacade(Customervisit $customer_visit){
        $facadeName = $customer_visit->facade;
        $facadePath = '/image/facades/' . $facadeName;
        $path = public_path($facadePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }


    public function index(){
        return Inertia::render('ProjectArea/ProjectManagement/PreProjects', [
            'preprojects' => Preproject::with('project')->paginate(10),
        ]);
    }


    public function getCode($date, $code){
        $year = date('Y', strtotime($date));
        $totalYearProjects = Preproject::whereYear('date', $year)->count()+1;
        $formattedTotal = str_pad($totalYearProjects, 3, '0', STR_PAD_LEFT);
        return $year.'-'.$formattedTotal.'-'.strtoupper($code);
    }


    public function store(Request $request){
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
        $data ['facade'] = $facadeName;
        $data ['code'] = $this->getCode($data['date'],$data['code']);
        Preproject::create($data);
    }


    public function showPreprojectFacade(Preproject $preproject){
        $facadeName = $preproject->facade;
        $facadePath = '/image/facades/' . $facadeName;
        $path = public_path($facadePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }


    public function update(Request $request, Preproject $preproject){
        $data = $request->validate([
            'customer' => 'required',
            'phone' => 'required',
            'description' => 'required',
            'address' => 'required',
            'date' => 'required',
            'code' => 'required',
            'observation' => 'required',
        ]);
        $preprojectYear = date('Y', strtotime($preproject->date));
        $requestYear = date('Y', strtotime($request->date));
        if ($preprojectYear == $requestYear) {
            $data ['code'] = substr($preproject->code,0, 9).$data['code'];
        } else {
            $data ['code'] = $this->getCode($data['date'],$data['code']);
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
        } 
        $preproject->update($data);
        return redirect()->back();
    }
    

    public function destroy(Preproject $preproject){
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


    public function quote ($preproject_id) {
        return Inertia::render('ProjectArea/ProjectManagement/PreProjectQuote', [
            'preproject'=> Preproject::with('quote.items')->find($preproject_id),
        ]);
    }


    public function quote_store (Request $request, $quote_id=null) {
        $data = $request->validate([
            "name"=>'required',
            "date"=>'required',
            "supervisor"=>'required',
            "boss"=>'required',
            "rev"=>'required',
            "deliverable_time"=>'required',
            "validity_time"=>'required',
            "observations"=>'required',
            "preproject_id" => 'required'
        ]);
        if ($quote_id){
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


    public function quote_item_store (Request $request) {
        $data = $request->validate([
            "description"=>'required',
            "unit"=>'required',
            "days"=>'required',
            "quantity"=>'required',
            "unit_price"=>'required',
            "preproject_quote_id"=>'required',
        ]);
        PreProjectQuoteItem::create($data);
        return redirect()->back();
    }


    public function quote_item_delete (PreProjectQuoteItem $quote_item_id) {
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

}
