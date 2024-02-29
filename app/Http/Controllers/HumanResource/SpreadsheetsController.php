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
    public function index($reentry = false)
    {
        if ($reentry == false) {
            $employees   = Employee::with('contract', 'contract.pension')->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })->paginate();
        } else {
            $employees   = Employee::with('contract', 'contract.pension')->whereHas('contract', function ($query) {
                $query->where('state', 'Inactive');
            })->paginate();
        }
        $spreadsheet = [];
        foreach ($employees as $employee) {
            $state = $employee->contract->state;
            $truncated_month = Carbon::parse($employee->contract->fired_date)->diffInMonths(Carbon::parse($employee->contract->hire_date));
            $truncated_days = Carbon::parse($employee->contract->fired_date)->diffInDays(Carbon::parse($employee->contract->hire_date)) - ($truncated_month * 30) + 1;
            $basic_salary = $employee->contract->basic_salary / 2;
            $truncated_vacations = $employee->contract->fired_date !== null && $employee->contract->state === 'Inactive'
                ? ($basic_salary/ 12 * $truncated_month  +
                    ($basic_salary / (30 * 12) * $truncated_days)) -
                (($employee->contract->basic_salary / 30) * $employee->contract->days_taken)
                : 0;
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
                'salary' => $employee->contract->basic_salary,
                'hire_date' => $employee->contract->hire_date ? Carbon::parse($employee->contract->hire_date)->format('d/m/Y') : null,
                'truncated_vacations' => number_format($truncated_vacations, 2),
                'total_income' => $total_income,
                'total_pension_base' => number_format($total_income, 2),
                'snp' => number_format($snp, 2),
                'snp_onp' => number_format($snp_onp, 2),
                'commission' => number_format($commission, 2),
                'commission_on_ra' => number_format($commission_on_ra, 2),
                'seg' => number_format($seg, 2),
                'insurance_premium' => number_format($insurance_premium, 2),
                'mandatory_contribution' => $mandatory_contribution,
                'mandatory_contribution_amount' => number_format($mandatory_contribution_amount, 2),
                'total_discount' => number_format($total_discount, 2),
                'net_pay' => $net_pay,
                'health' => number_format($health, 2),
                'life_ley' => number_format($life_ley, 2),
                'total_contribution' => number_format($total_contribution, 2)
            ];
        }

        $total = [
            'sum_salary' => array_sum(array_column($spreadsheet, 'salary')),
            'sum_truncated_vacations' => array_sum(array_column($spreadsheet, 'truncated_vacations')),
            'sum_total_income' => array_sum(array_column($spreadsheet, 'total_income')),
            'sum_snp' => array_sum(array_column($spreadsheet, 'snp')),
            'sum_snp_onp' => array_sum(array_column($spreadsheet, 'snp_onp')),
            'sum_commission' => array_sum(array_column($spreadsheet, 'commission')),
            'sum_commission_on_ra' => array_sum(array_column($spreadsheet, 'commission_on_ra')),
            'sum_insurance_premium' => array_sum(array_column($spreadsheet, 'insurance_premium')),
            'sum_mandatory_contribution_amount' => array_sum(array_column($spreadsheet, 'mandatory_contribution_amount')),
            'sum_total_discount' => array_sum(array_column($spreadsheet, 'total_discount')),
            'sum_net_pay' => array_sum(array_column($spreadsheet, 'net_pay')),
            'sum_health' => array_sum(array_column($spreadsheet, 'health')),
            'sum_life_ley' => array_sum(array_column($spreadsheet, 'life_ley')),
            'sum_total_contribution' => array_sum(array_column($spreadsheet, 'total_contribution')),
        ];


        return Inertia::render('HumanResource/Payroll/Spreadsheets', ['spreadsheets' => $spreadsheet, 'boolean' => boolval($reentry), 'total' => $total,]);
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
        return Excel::download(new PayrollExport, 'Planilla ' . date('m-Y') . '.xlsx');
    }
}
