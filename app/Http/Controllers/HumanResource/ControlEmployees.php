<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EntryDocument;
use App\Models\ExternalEmployee;
use App\Models\FixedDocumentation;
use App\Models\IssuanceDocumentation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ControlEmployees extends Controller
{
    public function control_employees_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $employees = Employee::select('name', 'dni')->get();
            $externalEmployees = ExternalEmployee::select('name', 'dni')->get();
            $controlEmployee = FixedDocumentation::with('entry_document', 'issuance_documentation')
                ->paginate();
            return Inertia::render('HumanResource/ControlEmployees/Index', [
                'controlEmployee' => $controlEmployee,
                'employee' => $employees->concat($externalEmployees),
            ]);
        } else {
            $controlEmployee = FixedDocumentation::with('entry_document', 'issuance_documentation')
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
            $fixedDocumentation = FixedDocumentation::paginate();
            return Inertia::render('HumanResource/ControlEmployees/FixedDocumentation', [
                'fixedDocumentation' => $fixedDocumentation
            ]);
        } else {
            $fixedDocumentation = FixedDocumentation::orWhere('employees', 'like', "%$request->searchQuery%")
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'fixedDocumentation' => $fixedDocumentation
            ]);
        }
    }

    public function fixed_documentation_storeOrUpdate(Request $request, $fixed_documentation_id)
    {
        try {
            $data = $request->validated();
            FixedDocumentation::updateOrCreate(
                ['id' => $fixed_documentation_id],
                $data
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function entry_document_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $entryDocument = FixedDocumentation::select('id', 'employees', 'dni')
                ->with('entry_document')
                ->paginate();

            return Inertia::render('HumanResource/ControlEmployees/EntryDocumentation', [
                'entryDocument' => $entryDocument
            ]);
        } else {
            $entryDocument = FixedDocumentation::select('id', 'employees', 'dni')
                ->with('entry_document')
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'entryDocument' => $entryDocument
            ]);
        }
    }

    public function entry_document_storeOrUpdate(Request $request, $fixed_documentation_id = null)
    {
        try {
            $data = $request->validated();
            EntryDocument::updateOrCreate(
                ['fixed_documentation_id', $fixed_documentation_id],
                $data
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function issuance_documentation_index(Request $request)
    {
        if ($request->isMethod('get')) {
            $issuanceDocumentation = FixedDocumentation::select('id', 'employees', 'dni')
                ->with('issuance_documentation')
                ->paginate();

            return Inertia::render('HumanResource/ControlEmployees/IssuanceDocumentation', [
                'issuanceDocumentation' => $issuanceDocumentation
            ]);
        } else {
            $issuanceDocumentation = FixedDocumentation::select('id', 'employees', 'dni')
                ->with('issuance_documentation')
                ->orWhere('dni', 'like', "%$request->searchQuery%")
                ->get();
            return response()->json([
                'issuanceDocumentation' => $issuanceDocumentation
            ]);
        }
    }

    public function issuance_documentation_storeOrUpdate(Request $request, $fixed_documentation_id = null)
    {
        try {
            $data = $request->validated();
            IssuanceDocumentation::updateOrCreate(
                ['fixed_documentation_id', $fixed_documentation_id],
                $data
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
