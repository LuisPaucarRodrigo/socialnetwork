<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\FormationProgram;
use App\Models\Training;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormationDevelopment extends Controller
{
    public function index()
    {
        return Inertia::render('HumanResource/FormationDevelopments/Formation_Development');
    }

    public function formation_programs_index()
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/Index', [
            'formationPrograms' => FormationProgram::with(['trainings','employees'])->paginate(),
        ]);
    }

    public function formation_programs_create()
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/Create', [
            'trainings' => Training::whereNull('formation_program_id')->get()
        ]);
    }

    public function formation_programs_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'month_year' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);
        $formationProgram = FormationProgram::updateOrCreate($data);
        Training::whereIn('id', $request['trainings'])
        ->update(['formation_program_id' => $formationProgram->id]);
        return redirect()->route('management.employees.formation_development.formation_programs');
    }

    public function formation_programs_destroy($id)
    {
        $formationProgram = FormationProgram::find($id);
        $formationProgram->delete();
        return Inertia::location(route('management.employees.formation_development.formation_programs'));
    }


    public function trainings_index()
    {
        return Inertia::render('HumanResource/FormationDevelopments/Trainings/Index', [
            'trainings' => Training::paginate(),
        ]);
    }

    public function trainings_create()
    {
        return Inertia::render('HumanResource/FormationDevelopments/Trainings/Create');
    }

    public function trainings_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',

        ]);
        Training::updateOrCreate($data);
        return redirect()->route('management.employees.formation_development.trainings');
    }

    public function trainings_destroy($id)
    {
        $training = Training::find($id);
        $training->delete();
        return Inertia::location(route('management.employees.formation_development.trainings'));
    }

    public function assignate()
    {

    }

    public function assignate_employee_fprogram(Request $request)
    {
        $employees = $request->input('employees');
        $formationPrograms = $request->input('formation_programs');
        foreach ($employees as $emp) {
            $employee = Employee::find($emp);
            $employee->formation_programs()->attach($formationPrograms);
        }
        return redirect()->back();
    }

}
