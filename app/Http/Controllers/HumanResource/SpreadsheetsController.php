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
        $employees = Employee::with('contract', 'contract.pension')->get();
        $spreadsheet = [];
        foreach ($employees as $employee) {
            // dd(floor(Carbon::parse($employee->contract->fired_date)->diffInYears(Carbon::parse($employee->contract->hire_date))));
            $state = $employee->contract->state;
            $truncated_vacations = floor($employee->contract->fired_date !== null && $employee->contract->state === 'Inactive'
                ? (Carbon::parse($employee->contract->fired_date)->floatDiffInYears(Carbon::parse($employee->contract->hire_date)) * 15) * ($employee->contract->basic_salary / 30)
                : 0);
            $total_income = $employee->contract->basic_salary + $truncated_vacations;
            $snp = ($employee->contract->pension->type == 'ONP') ? 100 * ($employee->contract->pension->values) : 0;
            $snp_onp = ($employee->contract->pension->type == 'ONP') ? $total_income * $employee->contract->pension->values : 0;
            $commission = ($employee->contract->pension->type == 'ONP') ? 0 : 100 * ($employee->contract->pension->values);
            $commission_on_ra = ($employee->contract->pension->type == 'ONP') ? 0 : $total_income * $employee->contract->pension->values;
            $seg = ($employee->contract->pension->type == 'ONP') ? 0 : number_format(100 * $employee->contract->pension->values_seg, 2);
            $insurance_premium = ($employee->contract->pension->type == 'ONP') ? 0 : $total_income * $employee->contract->pension->values_seg;
            $mandatory_contribution = ($employee->contract->pension->type == 'ONP') ? 0 : 100 * 0.1;
            $mandatory_contribution_amount = ($employee->contract->pension->type == 'ONP') ? 0 : $total_income * 0.1;
            $total_discount = $snp_onp + $commission_on_ra + $insurance_premium + $mandatory_contribution_amount;
            $net_pay = $total_income - $total_discount;
            $health = $total_income * 0.09;
            $life_ley = 0;
            $total_contribution = $health + $life_ley;

            $spreadsheet[] = [
                'state' => $state,
                'dni' => $employee->dni,
                'name' => $employee->name,
                'pension_reg' => $employee->contract->pension->type,
                'salary' => 'S/ ' . $employee->contract->basic_salary,
                'hire_date' => $employee->contract->hire_date ? Carbon::parse($employee->contract->hire_date)->format('d/m/Y') : null,
                'truncated_vacations' => 'S/ ' . number_format($truncated_vacations, 2),
                'total_income' => 'S/ ' . number_format($total_income, 2),
                'total_pension_base' => 'S/ ' . number_format($total_income, 2),
                'snp' => '% ' . number_format($snp, 2),
                'snp_onp' => 'S/ ' . number_format($snp_onp, 2),
                'commission' => '% ' . number_format($commission, 2),
                'commission_on_ra' => 'S/ ' . number_format($commission_on_ra, 2),
                'seg' => '% ' . number_format($seg, 2),
                'insurance_premium' => 'S/ ' . number_format($insurance_premium, 2),
                'mandatory_contribution' => '% ' . $mandatory_contribution,
                'mandatory_contribution_amount' => 'S/ ' . number_format($mandatory_contribution_amount, 2),
                'total_discount' => 'S/ ' . number_format($total_discount, 2),
                'net_pay' => 'S/ ' . number_format($net_pay, 2),
                'health' => 'S/ ' . number_format($health, 2),
                'life_ley' => 'S/ ' . number_format($life_ley, 2),
                'total_contribution' => 'S/ ' . number_format($total_contribution, 2)
            ];
        }
        return Inertia::render('HumanResource/Payroll/Spreadsheets', ['spreadsheets' => $spreadsheet]);
        // $spreadsheet = Employee::with('contract', 'contract.pension')->get();
        // return Inertia::render('HumanResource/Payroll/Spreadsheets', ['spreadsheets' => $spreadsheet]);
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
