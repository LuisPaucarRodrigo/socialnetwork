<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use App\Models\User;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class VacationController extends Controller
{

    public function index()
    {
        $vacations = Vacation::with('employee')->paginate();
        return Inertia::render('HumanResource/ManagementVacation/Vacation', [
            'vacations' => $vacations,
        ]);
    }

    public function create(){
        return Inertia::render('HumanResource/ManagementVacation/CreateAndUpdate', [ 
            'employees' => Employee::all()
        ]);
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'employee_id' => 'required|numeric',
            'type' => 'required|in:Vacaciones,Permisos',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'start_permissions' => 'nullable|date_format:H:i',
            'end_permissions' => 'nullable|date_format:H:i|after:start_permissions',
            'reason' => 'required|string',
        ]);

        Vacation::create($validateData);
        return to_route('management.vacation');
    }

    public function edit($vacation)
    {   
        return Inertia::render('HumanResource/ManagementVacation/CreateAndUpdate', [
            'vacation' => Vacation::with('employee')->find($vacation),
        ]);
    }

    public function update(Request $request,$id)
    {   
        $vacation = Vacation::find($id);
        $validateData = $request->validate([
            'type' => 'required|in:Vacaciones,Permisos',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'start_permissions' => 'nullable',
            'end_permissions' => 'nullable|after:start_permissions',
            'start_date_accepted' => 'nullable|date',
            'end_date_accepted' => 'nullable|date|after:start_date_accepted',
            'reason' => 'required|string',
        ]);
        $vacation->update($validateData);
        return to_route('management.vacation');
    }

    public function review(Vacation $vacation)
    {
        
        return Inertia::render('HumanResource/ManagementVacation/Details',[
            'vacation' => $vacation,
            'details' => Vacation::with('employee')->find($vacation -> id),
        ]);
    }

    public function details ($vacation)
    {   
        $details = Vacation::with('employee')->find($vacation);
        return Inertia::render('HumanResource/ManagementVacation/Details',[
            'details' => $details
        ]);
    }

    public function reviewed(Request $request, $vacation)
    {   
        $request->validate([
            'start_date_accepted' => 'nullable|date',
            'end_date_accepted' => 'nullable|date|after:start_date_accepted'
        ]);

        $reviewed = Vacation::find($vacation);
        $reviewed -> update([
            'start_date_accepted' => $request->start_date_accepted,
            'end_date_accepted' => $request->end_date_accepted,
            'status' => 'Aceptado'
        ]);
        return to_route('management.vacation');
    }

    public function decline($id)
    {   
        $vacation = Vacation::find($id);
        $vacation->update([
            'status' => 'Rechazado'
        ]);
        return to_route('management.vacation');
    }

    public function destroy(Vacation $vacation)
    {
        $vacation->delete();
        return to_route('management.vacation');
    }
}
