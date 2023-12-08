<?php

namespace App\Http\Controllers\ShoppingArea;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone1' => 'required|numeric|digits:9',
            'phone2' => 'nullable|numeric|digits:9',
            'email' => 'required|email|max:255',
            'category' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
        ]);

        Provider::create([
            'company_name' => $request->company_name,
            'contact_name' => $request->contact_name,
            'address' => $request->address,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'email' => $request->email,
            'category' => $request->category,
            'segment' => $request->segment,
        ]);

        return to_route('providersmanagement.index');
    }

    public function edit($id)
    {
        return Inertia::render('ShoppingArea/ProviderManagement/ProviderEdit', ['providers' => Provider::find($id)]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone1' => 'required|numeric|digits:9',
            'phone2' => 'nullable|numeric|digits:9',
            'email' => 'required|email|max:255',
            'category' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
        ]);

        $provider = Provider::findOrFail($id);
        $provider->update([
            'company_name' => $request->company_name,
            'contact_name' => $request->contact_name,
            'address' => $request->address,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'email' => $request->email,
            'category' => $request->category,
            'segment' => $request->segment,
            'payment_conditions' => $request->payment_conditions,
        ]);

        return to_route('providersmanagement.index');
    }

    public function destroy($id)
    {
        Provider::destroy($id);
    }
}
