<?php

namespace App\Http\Controllers\HumanResource;

use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Pension;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetsController extends Controller
{
    public function index()
    {
        $spreadsheet = Employee::with('contract', 'contract.pension')->get();
        return Inertia::render('HumanResource/Payroll/Spreadsheets', ['spreadsheets' => $spreadsheet]);
    }

    public function edit()
    {
        $pensions = Pension::all();
        return Inertia::render('HumanResource/Payroll/PensionSystem', ['pensions' => $pensions]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required'
        ]);
        $pension_system = Pension::find($id);
        $pension_system->update([
            'values' => $request->value
        ]);
        return to_route('pension_system.edit');
    }

    public function update_seg(Request $request, $id)
    {
        $request->validate([
            'value' => 'required'
        ]);
        $pension_system = Pension::find($id);
        $pension_system->update([
            'values_seg' => $request->value
        ]);
        return to_route('pension_system.edit');
    }

    public function export()
    {
        return Excel::download(new PayrollExport, 'Payroll.xlsx');
    }
}
