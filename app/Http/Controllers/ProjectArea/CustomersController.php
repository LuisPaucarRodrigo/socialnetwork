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

        $data = $request->validate([
            'ruc' => 'required',
            'business_name' => 'required',
            'address' => 'required',
            'contact.name' => 'required',
            'contact.phone' => 'required',
            'contact.additional_information' => 'required'
        ]);
        $data_contact = $data['contact'];
        unset($data['contact']);
        $data['category'] = 'Normal';
        $customer = Customer::create($data);
        $data_contact['customer_id'] = $customer->id;
        Customers_contact::create($data_contact);
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
            'address' => $request->address
        ]);
    }

    public function destroy(Customer $customer)
    {
        if($customer->category === 'Especial') return abort(404);
        $customer->delete();
        return redirect()->back();
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

        Customers_contact::create([
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
