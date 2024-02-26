<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ResourceDescription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Resource;

class ResourceManagementController extends Controller
{
    public function index()
    {
        $resources = Resource::paginate();
        return Inertia::render('Inventory/ResourceManagement/index', [
            'resources' => $resources,
        ]);
    }

    public function new()
    {
        $descriptions = ResourceDescription::orderBy('created_at', 'desc')->get();
        return Inertia::render('Inventory/ResourceManagement/storeAndUpdate', [
            'title' => 'Nuevo Activo',
            'descriptions'=> $descriptions,
        ]);
    }

    public function details($id)
    {
        return Inertia::render('Inventory/ResourceManagement/details', [
            'details' => Resource::find($id),
        ]);
    }
    
    public function create(Request $request)
    {
        $validatedData = $request->validate(Resource::rules());
        Resource::create($validatedData);
        
    }

    public function edit($resourceId)
    {
        $descriptions = ResourceDescription::orderBy('created_at', 'desc')->get();
        $resource = Resource::find($resourceId);
        return Inertia::render('Inventory/ResourceManagement/storeAndUpdate', [
            'title' => 'Editar Activo',
            'resource' => $resource,
            'descriptions'=> $descriptions,
        ]);
    }
    public function update(Request $request, $resourceId)
    {
        $request->validate(Resource::updateRules());
        $resource = Resource::find($resourceId);
        $resource->update($request->all());
        return redirect()->route('resources.index');
    }
    public function destroy($resourceId)
    {
        $resource = Resource::find($resourceId);
        $resource->delete();
        return back();
    }



    public function resource_description_store (Request $request){
        $data = $request->validate([
            'name'=>'required'
        ]);
        ResourceDescription::create($data);
        return redirect()->back();
    }
}
