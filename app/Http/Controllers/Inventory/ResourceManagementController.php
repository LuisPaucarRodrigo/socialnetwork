<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Resource;

class ResourceManagementController extends Controller
{
    public function index()
    {
        $resources = Resource::paginate();
        return Inertia::render('Inventory/ResourceManagement/index',[
            'resources'=> $resources,
        ]);
    }
    //Nuevo Recurso
    public function new(Request $request)
    {
        return Inertia::render('Inventory/ResourceManagement/information',[
            'title'=>'Nuevo Recurso',
        ]);
    }
    public function create(Request $request)
    {
        $validatedData = $request->validate(Resource::rules());
        $resource = Resource::create($validatedData);
        return redirect()->route('resources.index');

    }
    //Editar Recurso
    public function edit(Request $request,$resourceId)
    {
        $resource = Resource::find($resourceId);
        return Inertia::render('Inventory/ResourceManagement/information',[
            'title'=>'Editar Recurso',
            'resource'=> $resource,
        ]);
    }
    public function update(Request $request, $resourceId)
    {
        //dd($resourceId,$request);
        $request->validate(Resource::updateRules());
        $resource = Resource::find($resourceId);
        $resource->update($request->all());
        return redirect()->route('resources.index');
    }
    public function destroy(Request $request, $resourceId)
    {
        //dd($resourceId,$request);
        $resource = Resource::find($resourceId);
        $resource->delete();
        return back();
    }
}
