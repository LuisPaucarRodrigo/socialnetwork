<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\CreateProviderRequest;
use App\Http\Requests\Provider\UpdateProviderRequest;
use App\Models\Provider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProviderController extends Controller
{
    public function index()
    {
        return Inertia::render('ShoppingArea/ProviderManagement/Provider', ['providers' => Provider::paginate()]);
    }

    public function create()
    {
        return Inertia::render('ShoppingArea/ProviderManagement/ProviderCreate');
    }

    public function store(CreateProviderRequest $request)
    {
        $validatedData = $request->validated();
        Provider::create($validatedData);
        return to_route('providersmanagement.index');
    }

    public function edit($id)
    {
        return Inertia::render('ShoppingArea/ProviderManagement/ProviderEdit', ['providers' => Provider::find($id)]);
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
}
