<?php

namespace App\Http\Controllers;

use App\Models\DocumentSection;
use App\Models\Employee;
use App\Models\ExternalEmployee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentSpreedSheetController extends Controller
{
    public function index()
    {
        $employees = Employee::with([
            'document_registers',
            'contract:id,state,employee_id,hire_date,discount_sctr,pension_id',
            ])
            ->select(
                'id',
                'name',
                'lastname',
                'phone1',
                'email',
                'dni',
                'l_policy',
                'sctr_exp_date',
                'policy_exp_date',
            )
            ->orderBy('lastname')
            ->get()
            ->map(function ($emp) {
                $emp->document_registers = $emp->document_registers->map(
                    function ($dr) {
                        return [
                            $dr->id => [
                                'subdivision_id' => $dr->subdivision_id,
                                'document_id' => $dr->document_id,
                                'employee_id' => $dr->employee_id,
                                'e_employee_id' => $dr->e_employee_id,
                                'exp_date' => $dr->exp_date,
                                'state' => $dr->state,
                                'observations' => $dr->observations,

                            ]
                        ];
                    }
                );
                return $emp;
            });
        $e_employees = ExternalEmployee::with([
            'document_registers',
            ])
            ->select(
                'id',
                'name',
                'lastname',
                'phone1',
                'email',
                'dni',
                'sctr',
                'l_policy',
                'sctr_exp_date',
                'policy_exp_date',
            )
            ->get()
            ->map(function ($emp) {
                $emp->document_registers = $emp->document_registers->map(
                    function ($dr) {
                        return [
                            $dr->id => [
                                'subdivision_id' => $dr->subdivision_id,
                                'document_id' => $dr->document_id,
                                'employee_id' => $dr->employee_id,
                                'e_employee_id' => $dr->e_employee_id,
                                'exp_date' => $dr->exp_date,
                                'state' => $dr->state,
                                'observations' => $dr->observations,

                            ]
                        ];
                    }
                );
                return $emp;
            });
        $sections = DocumentSection::with('subdivisions')->get();
        return Inertia::render(
            'HumanResource/DocumentSpreedSheet/Index',
            [
                'employees' => $employees,
                'e_employees' => $e_employees,
                'sections' => $sections,
            ]
        );
    }
}
