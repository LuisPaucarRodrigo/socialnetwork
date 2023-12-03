<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Pension;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpreadsheetsController extends Controller
{
    public function index()
    {
        $employees = Employee::with('contract', 'contract.pension')->get();
        $spreadsheet = [];
        foreach ($employees as $employee) {
            $total_income = $employee->contract->basic_salary + 0 + 0;
            $snp = ($employee->contract->pension->type == 'ONP') ? 100 * ($employee->contract->pension->values) : 0;
            $snp_onp = ($employee->contract->pension->type == 'ONP') ? $total_income * $employee->contract->pension->values : 0;
            $commission = ($employee->contract->pension->type == 'ONP') ? 0 : 100 * ($employee->contract->pension->values);
            $commission_on_ra = ($employee->contract->pension->type == 'ONP') ? 0 : $total_income * $employee->contract->pension->values;
            $seg = ($employee->contract->pension->type == 'ONP') ? 0 : number_format(100 * 0.0184,2);
            $insurance_premium = ($employee->contract->pension->type == 'ONP') ? 0 : $total_income * 0.0184;
            $mandatory_contribution = ($employee->contract->pension->type == 'ONP') ? 0 : 100 * 0.1;
            $mandatory_contribution_amount = ($employee->contract->pension->type == 'ONP') ? 0 : $total_income * 0.1;
            $total_discount = $snp_onp + $commission_on_ra + $insurance_premium + $mandatory_contribution_amount;
            $net_pay = $total_income - $total_discount;
            $health = $total_income * 0.09;
            $life_ley = 0;
            $total_contribution = $health + $life_ley;

            $spreadsheet[] = [
                'dni' => $employee->dni,
                'name' => $employee->name,
                'pension_reg' => $employee->contract->pension->type,
                'salary' => $employee->contract->basic_salary,
                'hire_date' => $employee->contract->hire_date,
                'truncated_vacations' => 0,
                'maternity_subsidy' => 0,
                'total_income' => $total_income,
                'total_pension_base' => $total_income,
                'snp' => $snp,
                'snp_onp' => $snp_onp,
                'commission' => $commission,
                'commission_on_ra' => $commission_on_ra,
                'seg' => $seg,
                'insurance_premium' => $insurance_premium,
                'mandatory_contribution' => $mandatory_contribution,
                'mandatory_contribution_amount' => $mandatory_contribution_amount,
                'total_discount' => $total_discount,
                'net_pay' => $net_pay,
                'health' => $health,
                'life_ley' => $life_ley,
                'total_contribution' => $total_contribution

                // Add other results here
            ];
        }
        //dd($spreadsheet);
        //dd($employees);
        return Inertia::render('HumanResource/Payroll/Spreadsheets', ['spreadsheets' => $spreadsheet]);
    }
}
