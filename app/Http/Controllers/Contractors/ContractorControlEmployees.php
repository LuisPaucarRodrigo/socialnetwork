<?php

namespace App\Http\Controllers\Contractors;

use App\Http\Controllers\Controller;
use App\Models\Contractors\ContractorEmployee;
use App\Models\Contractors\ContractorExternalEmployee;
use App\Models\Contractors\ContractorFixedDocumentation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractorControlEmployees extends Controller
{
    public function control_employees_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $employees = ContractorEmployee::select('name', 'dni')->get();
            $externalEmployees = ContractorExternalEmployee::select('name', 'dni')->get();
            $controlEmployee = ContractorFixedDocumentation::with('entry_document', 'issuance_documentation')
                ->paginate();
            return Inertia::render('HumanResource/ControlEmployees/Index', [
                'controlEmployee' => $controlEmployee,
                'employee' => $employees->concat($externalEmployees),
            ]);
        } else {
            $controlEmployee = ContractorFixedDocumentation::with('entry_document', 'issuance_documentation')
                ->orWhere('employees', 'like', "%$request->searchQuery%")
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'controlEmployee' => $controlEmployee
            ]);
        }
    }

    public function fixed_documentation_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $fixedDocumentation = ContractorFixedDocumentation::paginate();
            return Inertia::render('HumanResource/ControlEmployees/FixedDocumentation', [
                'fixedDocumentation' => $fixedDocumentation
            ]);
        } else {
            $fixedDocumentation = ContractorFixedDocumentation::orWhere('employees', 'like', "%$request->searchQuery%")
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'fixedDocumentation' => $fixedDocumentation
            ]);
        }
    }

    public function entry_document_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $entryDocument = ContractorFixedDocumentation::select('id', 'employees', 'dni')
                ->with('entry_document')
                ->paginate();

            return Inertia::render('HumanResource/ControlEmployees/EntryDocumentation', [
                'entryDocument' => $entryDocument
            ]);
        } else {
            $entryDocument = ContractorFixedDocumentation::select('id', 'employees', 'dni')
                ->with('entry_document')
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'entryDocument' => $entryDocument
            ]);
        }
    }

    public function issuance_documentation_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $issuanceDocumentation = ContractorFixedDocumentation::select('id', 'employees', 'dni')
                ->with('issuance_documentation')
                ->paginate();

            return Inertia::render('HumanResource/ControlEmployees/IssuanceDocumentation', [
                'issuanceDocumentation' => $issuanceDocumentation
            ]);
        } else {
            $issuanceDocumentation = ContractorFixedDocumentation::select('id', 'employees', 'dni')
                ->with('issuance_documentation')
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'issuanceDocumentation' => $issuanceDocumentation
            ]);
        }
    }
}
