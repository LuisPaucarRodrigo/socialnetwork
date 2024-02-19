<?php

namespace App\Http\Controllers\ProjectArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerVisit\CreateCustomerVisitRequest;
use App\Http\Requests\CustomerVisit\UpdateCustomerVisitRequest;
use App\Models\Customervisit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerVisitController extends Controller
{
    public function index()
    {
        return Inertia::render('ProjectArea/CustomerVisit/Index',['customers'=>Customervisit::paginate()]);
    }

    public function create()
    {
        return Inertia::render('ProjectArea/CustomerVisit/CreateAndUpdate');
    }

    public function store(CreateCustomerVisitRequest $request)
    {
        $imageUrl = null;
        if($request->hasFile('facade')){
            $customervisit = $request->file('facade');
            $imageUrl = time() . '._' . $customervisit->getClientOriginalName();
            $customervisit->move(public_path('/image/customervisit/'),$imageUrl);
        }
        Customervisit::create([
            'customer' => $request->customer,
            'phone' => $request->phone,
            'description' => $request->description,
            'address' => $request->address,
            'date' => $request->date,
            'observation' => $request->observation,
            'facade' => $imageUrl,
        ]);
    }

    public function details($id)
    {   $details = Customervisit::find($id);
        $details->facade = url('image/customervisit/'.$details->facade);
        return Inertia::render('ProjectArea/CustomerVisit/Details',['details' => $details]);
    }

    public function edit($id)
    {
        return Inertia::render('ProjectArea/CustomerVisit/CreateAndUpdate',['customer' => Customervisit::find($id)]);
    }

    public function update(UpdateCustomerVisitRequest $request, $id)
    {   
        $customer = Customervisit::find($id);
        $customer->update([
            'customer' => $request->customer,
            'phone' => $request->phone,
            'description' => $request->description,
            'address' => $request->address,
            'date' => $request->date,
            'observation' => $request->observation
        ]);

        return to_route('customervisitmanagement.index');
    }

    public function delete($id)
    {   $delete = Customervisit::find($id);
        $path = public_path('image/customervisit/'.$delete->facade);
        if(file_exists($path)){
            unlink($path);
        }else {
            abort(404, 'Imagen no encontrado');
        }
        Customervisit::destroy($id);
        return to_route('customervisitmanagement.index');
    }
}
