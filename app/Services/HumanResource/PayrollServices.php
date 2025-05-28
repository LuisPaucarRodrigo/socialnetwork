<?php

namespace App\Services\HumanResource;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollDetailExpense;
use App\Models\Pension;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PayrollServices
{
    public function payrollBase(): Builder
    {
        $payroll = Payroll::orderBy('month', 'desc');
        return $payroll;
    }

    public function addedCalculated($payroll)
    {
        foreach ($payroll as $item) {
            $item->setAppends(['total_amount']);
        }
        return $payroll;
    }

    public function findPayroll($payroll_id)
    {
        $payroll = Payroll::find($payroll_id);
        return $payroll;
    }

    public function createPayroll($data)
    {
        $payroll = Payroll::create($data);
        return $payroll;
    }

    public function createPension($data)
    {
        $pension = Pension::create($data);
        return $pension;
    }

    public function forCreatePension($validateData, $payroll)
    {
        foreach ($validateData['pension_system'] as $pension) {
            $data = collect($pension)->only(['type', 'values', 'values_seg'])->toArray();
            $data['payroll_id'] = $payroll->id;
            $this->createPension($data);
        }
    }

    public function createPayrollDetail($data)
    {
        $payrollDetail = PayrollDetail::create($data);
        return $payrollDetail;
    }

    public function getActiveEmployees()
    {
        return Employee::select('id')
            ->with('contract')
            ->whereHas('contract', fn($query) => $query->where('state', 'Active'));
    }

    function createPayrollDetailForEmployee($employee, $payroll, $listPension)
    {
        $data = [
            'payroll_id' => $payroll->id,
            'employee_id' => $employee->id,
            'basic_salary' => $employee->contract->basic_salary,
            'amount_travel_expenses' => $employee->contract->amount_travel_expenses,
            'life_ley' => $employee->contract->life_ley,
            'discount_remuneration' => $employee->contract->discount_remuneration,
            'discount_sctr' => $employee->contract->discount_sctr,
            'hire_date' => $employee->contract->hire_date,
            'fired_date' => $employee->contract->fired_date,
            'days_taken' => $employee->contract->days_taken,
            'pension_id' => $listPension->pension->firstWhere('type', $employee->contract->pension_type)?->id
        ];
        if($employee->id === 32) {
            Log::info($data);
            Log::info($employee->contract->pension_type);
            Log::info($listPension->pension);
        }
        return $this->createPayrollDetail($data);
    }

    function createPayrollDetailExpenses($payrollDetail)
    {
        $listType = ['Salary', 'Travel'];
        foreach ($listType as $item) {
            if ($item === 'Travel' && $payrollDetail->amount_travel_expenses === null) {
                continue;
            }

            PayrollDetailExpense::create([
                'payroll_detail_id' => $payrollDetail->id,
                'type' => $item
            ]);
        }
    }

    public function calculateTotal($spreadsheet)
    {
        return [
            'sum_salary' => $spreadsheet->sum('basic_salary'),
            'sum_payment_until_today' => $spreadsheet->sum('payment_until_today'),
            'sum_discount' => $spreadsheet->sum('discount'),
            'sum_truncated_vacations' => $spreadsheet->sum('truncated_vacations'),
            'sum_total_income' => $spreadsheet->sum('total_income'),
            'sum_snp' => $spreadsheet->sum('snp'),
            'sum_snp_onp' => $spreadsheet->sum('snp_onp'),
            'sum_commission' => $spreadsheet->sum('commission'),
            'sum_commission_on_ra' => $spreadsheet->sum('commission_on_ra'),
            'sum_insurance_premium' => $spreadsheet->sum('insurance_premium'),
            'sum_mandatory_contribution_amount' => $spreadsheet->sum('mandatory_contribution_amount'),
            'sum_total_discount' => $spreadsheet->sum('total_discount'),
            'sum_amount_travel_expenses' => $spreadsheet->sum('amount_travel_expenses'),
            'sum_net_pay' => $spreadsheet->sum('net_pay'),
            'sum_health' => $spreadsheet->sum('healths'),
            'sum_life_ley' => $spreadsheet->sum('life_ley'),
            'sum_sctr_p' => $spreadsheet->sum('sctr_p'),
            'sum_sctr_s' => $spreadsheet->sum('sctr_s'),
            'sum_total_contribution' => $spreadsheet->sum('total_contribution'),
        ];
    }

    public function getPayrollDetails($payroll_id, $searchQuery = null)
    {
        $query = PayrollDetail::with('payroll', 'payroll_detail_expense', 'employee', 'pension')
            ->where('payroll_id', $payroll_id);

        if ($searchQuery) {
            $query->whereHas('employee', function ($query) use ($searchQuery) {
                $query->where('name', 'like', "%$searchQuery%")
                    ->orWhere('lastname', 'like', "%$searchQuery%");
            });
        }

        return $query;
    }
}
