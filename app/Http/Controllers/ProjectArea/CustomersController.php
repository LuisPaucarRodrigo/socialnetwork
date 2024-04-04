<?php

namespace App\Http\Controllers\ProjectArea;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Customers_contact;

class CustomersController extends Controller
{
    //customers
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $customers = Customer::paginate();
            return Inertia::render('ProjectArea/Customers/Customers', [
                'customers' => $customers,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->input('searchQuery');
            $customers = Customer::where('ruc', 'like', "%$searchQuery%")
                ->orWhere('business_name', 'like', "%$searchQuery%")
                ->orWhere('category', 'like', "%$searchQuery%")
                ->paginate();

            return response()->json([
                'customers' => $customers
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'required',
            'business_name' => 'required',
            'address' => 'required'
        ]);

        $customer = Customer::create([
            'ruc' => $request->ruc,
            'business_name' => $request->business_name,
            'category' => 'Normal',
            'address' => $request->address
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'ruc' => 'required',
            'business_name' => 'required',
            'address' => 'required'
        ]);
        $customer->update([
            'ruc' => $request->ruc,
            'business_name' => $request->business_name,
            'category' => $request->category,
            'category' => 'Normal',
            'address' => $request->address
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return to_route('customers.index');
    }

    //contacts

    public function show_contacts(Customer $customer)
    {
        $contacts = Customers_contact::where('customer_id', $customer->id)->with('customer')->paginate(10);
        return Inertia::render('ProjectArea/Customers/Contacts', [
            'contacts' => $contacts,
            'customer_id' => $customer->id
        ]);
    }

    public function store_contact(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'additional_information' => 'required',
        ]);

        $contact = Customers_contact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'additional_information' => $request->additional_information,
            'customer_id' => $customer->id
        ]);
    }

    public function update_contact(Request $request, Customer $customer, Customers_contact $customer_contact)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'additional_information' => 'required',
        ]);

        $customer_contact->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'additional_information' => $request->additional_information,
        ]);
    }

    public function destroy_contact(Customer $customer, Customers_contact $customer_contact)
    {
        $customer_contact->delete();
        return to_route('customers.contacts.index', ['customer' => $customer->id]);
    }
}
