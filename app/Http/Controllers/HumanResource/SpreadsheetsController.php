<?php

namespace App\Http\Controllers\HumanResource;

use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Pension;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetsController extends Controller
{
    public function index(Request $request, $reentry = false)
    {
        if ($reentry == false) {
            $spreadsheet = Contract::with('pension', 'employee')->where('state', 'Active');
        } else {
            $spreadsheet = Contract::with('pension', 'employee')->where('state', 'Inactive');
        }
        $searchTerm = strtolower($request->query('searchTerm'));
        if ($searchTerm !== '') {
            $spreadsheet = $spreadsheet->where(function ($query) use ($searchTerm) {
                $query->whereHas('pension', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('type', 'like', '%' . $searchTerm . '%');
                })
                    ->orWhereHas('employee', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('lastname', 'like', '%' . $searchTerm . '%');
                    });
            })->get();
        } else {
            $spreadsheet = $spreadsheet->get();
        }

        $total = [
            'sum_salary' => $spreadsheet->sum('basic_salary'),
            'sum_truncated_vacations' => $spreadsheet->sum('truncated_vacations'),
            'sum_total_income' => $spreadsheet->sum('total_income'),
            'sum_snp' => $spreadsheet->sum('snp'),
            'sum_snp_onp' => $spreadsheet->sum('snp_onp'),
            'sum_commission' => $spreadsheet->sum('commission'),
            'sum_commission_on_ra' => $spreadsheet->sum('commission_on_ra'),
            'sum_insurance_premium' => $spreadsheet->sum('insurance_premium'),
            'sum_mandatory_contribution_amount' => $spreadsheet->sum('mandatory_contribution_amount'),
            'sum_total_discount' => $spreadsheet->sum('total_discount'),
            'sum_net_pay' => $spreadsheet->sum('net_pay'),
            'sum_health' => $spreadsheet->sum('healths'),
            'sum_life_ley' => $spreadsheet->sum('life_ley'),
            'sum_total_contribution' => $spreadsheet->sum('total_contribution'),
        ];

        return Inertia::render('HumanResource/Payroll/Spreadsheets', [
            'spreadsheets' => $spreadsheet,
            'search' => $searchTerm,
            'boolean' => boolval($reentry),
            'total' => $total
        ]);
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
        return Excel::download(new PayrollExport, 'Planilla ' . date('m-Y') . '.xlsx');
    }
}
