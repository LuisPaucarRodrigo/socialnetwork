<?php

namespace App\Http\Controllers\Contractors;

use App\Http\Controllers\Controller;
use App\Models\Contractors\ContractorFormationProgram;
use App\Models\Contractors\ContractorTraining;
use App\Models\Contractors\ContractorEmployee;
use Inertia\Inertia;
use Carbon\Carbon;

class ContractorFormationDevelopment extends Controller
{
    public function index() {
        return Inertia::render('HumanResource/FormationDevelopments/Formation_Development');
    }

    public function formation_programs_index()  {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/Index', [
            'formationPrograms' => ContractorFormationProgram::with(['trainings', 'employees'])->paginate(),
            'employees'=> ContractorEmployee::with('assignated_programs.formation_program')->get()
        ]);
    }

    public function formation_programs_view($id)
    {
        return Inertia::render('HumanResource/FormationDevelopments/FormationPrograms/View', [
            'formation_program' => ContractorFormationProgram::with(['employees', 'trainings'])->find($id),
        ]);
    }


    public function trainings_index()
    {
        return Inertia::render('HumanResource/FormationDevelopments/Trainings/Index', [
            'trainings' => ContractorTraining::paginate(),
        ]);
    }

    public function employees_in_programs () {
        return Inertia::render('HumanResource/FormationDevelopments/EmployeesInPrograms/Index',[
            'employees' => ContractorEmployee::has('assignated_programs')
                ->with('assignated_programs.formation_program')
                ->orderBy('created_at','desc')
                ->paginate(),
            ]
        );
    }

    public function employees_in_programs_alarms () {
        $today = Carbon::now();
        $alarm3d = ContractorEmployee::with('assignated_programs.formation_program')
            ->whereHas('assignated_programs', function($query) use ($today) {
                $query->where('end_date', '<=', $today->copy()->addDays(3))
                      ->where('state', null);
            })
            ->get();
        $alarm7d = ContractorEmployee::with('assignated_programs.formation_program')
            ->whereHas('assignated_programs', function($query) use ($today) {
                $query->where('end_date', '>', $today->copy()->addDays(3))
                      ->where('end_date', '<=', $today->copy()->addDays(7))
                      ->where('state', null);

            })
            ->whereNotIn('id', $alarm3d->pluck('id'))
            ->get();
        return response()->json([
            'alarm3d' => $alarm3d,
            'alarm7d' => $alarm7d,
        ]);
    }


    public function employees_in_programs_details($employee_id) {
        $employee = ContractorEmployee::with(['assignated_programs' => function ($query) {
                            $query->with('formation_program')->where('state', null);
                        }]
                        )
                        ->find($employee_id);
        return Inertia::render('HumanResource/FormationDevelopments/Detail',[
            'employee'=>$employee
        ]);
    }

}
