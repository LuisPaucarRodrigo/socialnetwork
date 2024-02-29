<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequest\UpdateProviderRequest;
use App\Http\Requests\ProviderRequest\CreateProviderRequest;
use App\Models\Provider;
use App\Models\ProviderCategory;
use App\Models\ProviderSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProviderController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/ProviderManagement/Provider', ['providers' => Provider::paginate()]);
    }

    public function create()
    {
        return Inertia::render(
            'ShoppingArea/ProviderManagement/ProviderCreateAndUpdate',
            [
                'category' => ProviderCategory::all(),
                'segment' => ProviderSegment::all(),
            ]
        );
    }

    public function store(CreateProviderRequest $request)
    {
        $validatedData = $request->validated();
        Provider::create($validatedData);
    }

    public function edit($id)
    {
        return Inertia::render('ShoppingArea/ProviderManagement/ProviderCreateAndUpdate', [
            'providers' => Provider::find($id),
            'category' => ProviderCategory::all(),
            'segment' => ProviderSegment::all()
        ]);
    }

    public function update(UpdateProviderRequest $request, $id)
    {
        $provider = Provider::findOrFail($id);
        $validatedData = $request->validated();
        $provider->update($validatedData);

        return to_route('providersmanagement.index');
    }

    public function destroy($id)
    {
        Provider::destroy($id);
    }

    public function category_provider(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);
        ProviderCategory::create($data);
    }

    public function segment_provider(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);
        ProviderSegment::create($data);
    }
}
