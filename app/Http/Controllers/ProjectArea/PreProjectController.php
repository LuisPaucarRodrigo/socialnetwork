<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest\CreateProjectRequest;
use App\Models\Preproject;
use App\Models\Customervisit;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Project;

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

    public function showVisitFacade(Customervisit $customer_visit)
    {
        $facadeName = $customer_visit->facade;
        $facadePath = '/image/facades/' . $facadeName;
        $path = public_path($facadePath);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Documento no encontrado');
    }

    public function index()
    {
        return Inertia::render('ProjectArea/ProjectManagement/PreProjects', [
            'preprojects' => Preproject::with('project')->paginate(10),
            'visits' => Customervisit::with('preproject')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'unit_type' => 'required',
            'cost' => 'required|numeric',
            'customervisit_id' => 'required'
        ]);

        $prepropject = Preproject::create([
            'name' => $request->name,
            'description' => $request->description,
            'unit_type' => $request->unit_type,
            'cost' => $request->cost,
            'customervisit_id' => $request->customervisit_id,
        ]);
    }

    public function update(Request $request, Preproject $preproject)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'unit_type' => 'required',
            'cost' => 'required|numeric',
            'customervisit_id' => 'required'
        ]);

        $preproject->update([
            'name' => $request->name,
            'description' => $request->description,
            'unit_type' => $request->unit_type,
            'cost' => $request->cost,
            'customervisit_id' => $request->customervisit_id,
        ]);
    }

    public function destroy(Preproject $preproject)
    {
        $preproject->delete();
        return to_route('preprojects.index');    
    }
}
