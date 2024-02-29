<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequest\UpdateProviderRequest;
use App\Http\Requests\ProviderRequest\CreateProviderRequest;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Providers\GlobalFunctionsServiceProvider;

class ProviderController extends Controller
{
    public function index()
    {
        $hasAllPermissions = GlobalFunctionsServiceProvider::hasAllPermissions();
        return Inertia::render('ShoppingArea/ProviderManagement/Provider',
            ['providers' => Provider::paginate(),
             'admin' => $hasAllPermissions
            ]
        );
    }

    public function create()
    {
        return Inertia::render('ShoppingArea/ProviderManagement/ProviderCreate');
    }

    public function store(CreateProviderRequest $request)
    {
        $validatedData = $request->validated();
        Provider::create($validatedData);
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
