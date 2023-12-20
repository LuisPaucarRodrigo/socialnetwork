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
            'formationPrograms' => FormationProgram::with(['trainings', 'employees'])->paginate(),
        ]);
    }

    public function formation_programs_create()
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/Create', [
            'trainings' => Training::whereNull('formation_program_id')->get(),
        ]);
    }
    public function formation_programs_view($id)
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/View', [
            'formation_program' => FormationProgram::with(['employees', 'trainings_list'])->find($id),
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
        Training::updateOrCreate(['id' => $id],$data);
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

    public function assignate_create()
    {
        return Inertia::render('HumanResource/FormationDevelopments/AssignationCreate', [
            'employees' => Employee::all(),
            'formation_programs' => FormationProgram::all(),
        ]);
    }

    public function assignate_store(Request $request)
    {
        $request->validate([
            'employees' => 'required|array',
            'formation_programs' => 'required|array',
        ]);

        $employeeIds = $request->input('employees');
        $formationProgramIds = $request->input('formation_programs');

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::find($employeeId);

            if ($employee) {
                // Asigna los programas de formación al empleado
                $employee->formation_programs()->sync($formationProgramIds);
            }
        }

        return redirect()->route('management.employees.formation_development');
    }

}
