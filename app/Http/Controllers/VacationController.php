<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use App\Models\User;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vacations = Vacation::with('employee')->paginate();
        return Inertia::render('HumanResource/ManagementVacation/Vacation', [
            'vacations' => $vacations,
        ]);
    }

    public function index_info_additional(){
        $vacations = Vacation::with('employee')->paginate();
        return Inertia::render('HumanResource/ManagementVacation/VacationInformation', [
            'vacations' => $vacations,
            'employees' => Employee::all()
        ]);
    }

    public function edit_info_additional(Vacation $vacation)
    {
        // Lógica para cargar la vista de información adicional para la edición
        // Puedes pasar $vacation a la vista si es necesario
        //dd($vacation);
        return Inertia::render('HumanResource/ManagementVacation/VacationInformation', [
            'vacation' => $vacation,
            'employees' => Employee::all()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        $request->validate([
            'employee_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);

        $vacation = Vacation::create([
            'employee_id' => $request->employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_date_accepted' => $request->start_date_accepted,
            'end_date_accepted' => $request->end_date_accepted,
            'reason' => $request->reason,
            'status' => $request->status,          
        ]);

        return to_route('management.vacation');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vacation = new Vacation;
        $vacation->employee_id = $request->employee_id;
        $vacation->start_date = $request->start_date;
        $vacation->end_date = $request->end_date;
        $vacation->reason = $request->reason;
        $vacation->save();
        $data = [
            'message' => 'Vacation stores succesfully',
            'vacation' => $vacation
        ];
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacation $vacation)
    {
        return response()->json($vacation->load('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacation $vacacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacation $vacation)
    {
        $request->validate([
            'employee_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ]);

        $vacation->update([
            'employee_id' => $request->employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_date_accepted' => $request->start_date,
            'end_date_accepted' => $request->end_date,
            'reason' => $request->reason,
            'status' => $request->status,          
        ]);

        return to_route('management.vacation');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacation $vacation)
    {
        $vacation->delete();
        return to_route('management.vacation');
    }
}
