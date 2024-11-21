<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequest\UpdateProviderRequest;
use App\Http\Requests\ProviderRequest\CreateProviderRequest;
use App\Models\Provider;
use App\Models\Category;
use App\Models\Segment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProviderController extends Controller
{
    public function index()
    {   
        $provider = Provider::with('segments','category')->paginate();
        return Inertia::render(
            'ShoppingArea/ProviderManagement/Provider',
            [
                'providers' => $provider,
                'category' => Category::all(),
            ]
        );
    }

    public function create()
    {
        return Inertia::render(
            'ShoppingArea/ProviderManagement/ProviderCreateAndUpdate',
            [
                'category' => Category::all(),
                'segments' => Segment::all(),
            ]
        );
    }

    public function store(CreateProviderRequest $request)
    {
        $validatedData = $request->validated();
        $provider = Provider::create($validatedData);
        $provider->segments()->attach($validatedData['segments']);
        $provider->load('segments','category');
        return response()->json($provider,200);
    }

    public function edit($id)
    {
        return Inertia::render('ShoppingArea/ProviderManagement/ProviderCreateAndUpdate', [
            'providers' => Provider::find($id),
            'category' => Category::all(),
            'segment' => Segment::all(),
        ]);
    }

    public function update(UpdateProviderRequest $request, $id)
    {
        $validatedData = $request->validated();
        $provider = Provider::findOrFail($id);
        $provider->update($validatedData);
        $provider->segments()->sync($validatedData['segments']);
        $provider->load('segments','category');
        return response()->json($provider,200);
    }

    public function destroy($id)
    {
        Provider::destroy($id);
    }

    public function category_provider(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);
        $new = Category::create($data);
        return response()->json(['new' => $new], 200);
    }

    public function segment_provider(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'name' => 'required'
        ]);
        Segment::create($data);
        return response()->json([], 200);
    }

    public function segment_list($category_id)
    {
        $segments = Segment::where('category_id', $category_id)->get();
        return response()->json($segments, 200);
    }

    public function search($request)
    {
        $searchTerm = strtolower($request); // Convertir a minúsculas

        $providers = Provider::where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(ruc) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(company_name) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(contact_name) like ?', ['%' . $searchTerm . '%']);
        })->get();

        return Inertia::render('ShoppingArea/ProviderManagement/Provider', [
            'providers' => $providers,
            'search' => $request
        ]);
    }
}
