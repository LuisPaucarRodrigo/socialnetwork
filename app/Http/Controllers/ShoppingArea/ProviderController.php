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
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $provider = Provider::with('segments', 'category')->paginate();
            return Inertia::render(
                'ShoppingArea/ProviderManagement/Provider',
                [
                    'provider' => $provider,
                    'category' => Category::all(),
                ]
            );
        } elseif ($request->isMethod('post')) {
            $provider = Provider::with('segments', 'category')
                ->where('company_name', 'like', "%$request->searchQuery%")
                ->orWhere('contact_name', 'like', "%$request->searchQuery%")
                ->orWhere('ruc', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json($provider, 200);
        }
    }

    public function store(CreateProviderRequest $request)
    {
        $validatedData = $request->validated();
        $provider = Provider::create($validatedData);
        $provider->segments()->attach($validatedData['segments']);
        $provider->load('segments', 'category');
        return response()->json($provider, 200);
    }

    public function update(UpdateProviderRequest $request, $id)
    {
        $validatedData = $request->validated();
        $provider = Provider::findOrFail($id);
        $provider->update($validatedData);
        $provider->segments()->sync($validatedData['segments']);
        $provider->load('segments', 'category');
        return response()->json($provider, 200);
    }

    public function destroy($id)
    {
        Provider::destroy($id);
        return response()->noContent();
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

    public function segment_list($category_id = null)
    {
        $segments = Segment::where('category_id', $category_id)->get();
        return response()->json($segments, 200);
    }
}
