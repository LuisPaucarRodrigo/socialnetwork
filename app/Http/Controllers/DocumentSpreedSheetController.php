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
        $employees = Employee::with('document_registers')
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
        $e_employees = ExternalEmployee::with('document_registers')->get();
        $sections = DocumentSection::with('subdivisions')->get();


        // $e_employees->document_registers = $ $e_employees
        //     ->document_registers
        //     ->map(function($item): array{
        //         return [
        //             'subdivision_id'=> $item->subdivision_id,
        //             'document_id'=> $item->document_id,
        //             'employee_id'=> $item->employee_id,
        //             'e_employee_id'=> $item->e_employee_id,
        //             'exp_date'=> $item->exp_date,
        //             'state'=> $item->state,
        //             'observations'=> $item->observations,
        //         ];
        // });
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
