<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest\FormationProgramRequest;
use App\Models\FormationProgram;
use App\Models\Training;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormationDevelopment extends Controller
{
    public function index() {
        return Inertia::render('HumanResource/FormationDevelopments/Formation_Development');
    }

    public function formation_programs_index()  {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/Index', [
            'formationPrograms' => FormationProgram::with(['trainings', 'employees'])->paginate(),
            'employees'=> Employee::with('assignated_programs.formation_program')->get()
        ]);
    }

    public function formation_programs_create()
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/Create', [
            'trainings' => Training::all(),
        ]);
    }
    public function formation_programs_view($id)
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/View', [
            'formation_program' => FormationProgram::with(['employees', 'trainings'])->find($id),
        ]);
    }

    public function formation_programs_store(FormationProgramRequest $request)
    {
        $data = $request->validated();
        $formationProgram = FormationProgram::create($data);
        $formationProgram->trainings()->sync($request->trainings);
        return redirect()->route('management.employees.formation_development.formation_programs');
    }

    public function formation_programs_destroy($id)
    {
        $formationProgram = FormationProgram::find($id);
        $formationProgram->delete();
        return Inertia::location(route('management.employees.formation_development.formation_programs'));
    }
    public function formation_programs_destroy_employee(Request $request)
    {
        $request->validate([
            'formation_program_id' => 'required|exists:formation_programs,id',
            'employee_id' => 'required|exists:employees,id',
        ]);
        $formationProgram = FormationProgram::find($request->formation_program_id);
        $employee = Employee::find($request->employee_id);
        if ($formationProgram && $employee) {
            $formationProgram->employees()->detach($employee);
            return redirect()->route('management.employees.formation_development.view', ['id' => $formationProgram->id]);
        }

        return response()->json(['message' => 'Error al eliminar la relación'], 404);
    }


    public function trainings_index()
    {
        return Inertia::render('HumanResource/FormationDevelopments/Trainings/Index', [
            'trainings' => Training::paginate(),
        ]);
    }

    public function trainings_create($id = null)
    {
        if ($id) {
            return Inertia::render('HumanResource/FormationDevelopments/Trainings/Create', [
                'training' => Training::find($id),
            ]);
        } else {
            return Inertia::render('HumanResource/FormationDevelopments/Trainings/Create');
        }
    }

    public function trainings_store(Request $request, $id = null)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',

        ]);
        Training::updateOrCreate(['id' => $id], $data);
        if ($id = null) {
            return redirect()->route('management.employees.formation_development.trainings');
        }
    }

    public function trainings_destroy($id)
    {
        $training = Training::find($id);
        $training->delete();
        return Inertia::location(route('management.employees.formation_development.trainings'));
    }

    public function assignate_employee_fprogram(Request $request)
    {   
        $data= $request->validate([
            'employees'=>  'required|array',
            'formation_programs'=>  'required|array',
            'start_date'=> 'required',
            'end_date'=> 'required'
        ]);
        $employees = $data['employees'];
        $formationPrograms = $data['formation_programs'];
        foreach ($employees as $emp) {
            $employee = Employee::find($emp);
            $employee->formation_programs()->sync($formationPrograms, [
                'start_date'=>$data['start_date'],
                'end_date'=>$data['end_date']
            ]);
        }
        return redirect()->back();
    }

    public function assignate_create()
    {
        return Inertia::render('HumanResource/FormationDevelopments/AssignationCreate', [
            'employees' => Employee::with('contract')->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            }),
            'formation_programs' => FormationProgram::all(),
        ]);
    }

    public function assignate_store(Request $request){
        $data= $request->validate([
            'employees'=>  'required|array',
            'formation_programs'=>  'required|array',
            'start_date'=> 'required',
            'end_date'=> 'required'
        ]);
        $employees = $data['employees'];
        $formationPrograms = $data['formation_programs'];
        foreach ($employees as $emp) {
            $employee = Employee::find($emp);
            $employee->formation_programs()->attach($formationPrograms, [
                'start_date'=>$data['start_date'],
                'end_date'=>$data['end_date']
            ]);
        }
        return redirect()->back();
    }



    public function employees_in_programs () {
        return Inertia::render('HumanResource/FormationDevelopments/EmployeesInPrograms/Index',[
            'employees' => Employee::has('assignated_programs')
                ->with('assignated_programs.formation_program')
                ->orderBy('created_at','desc')
                ->paginate(),
            ]
        );
    }

}
