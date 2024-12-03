<?php

namespace App\Http\Controllers\HumanResource;

use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollDetailExpense;
use App\Models\Pension;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetsController extends Controller
{
    public function index()
    {
        $payroll = Payroll::orderBy('month', 'desc')->paginate();
        foreach ($payroll as $item) {
            $item->setAppends(['total_amount']);
        }
        return Inertia::render('HumanResource/Payroll/Index', [
            'payroll' => $payroll
        ]);
    }

    public function update_payroll_state($payroll_id)
    {
        $payroll = Payroll::find($payroll_id);
        $payroll->update([
            'state' => true
        ]);
        return response()->json($payroll, 200);
    }

    public function store_payroll(Request $request)
    {
        $validateData = $request->validate([
            'month' => 'required|unique:payrolls,month',
            'state' => 'required',
            'sctr_p' => 'required|numeric',
            'sctr_s' => 'required|numeric',
            'pension_system.*.type' => 'required',
            'pension_system.*.values' => 'required',
            'pension_system.*.values_seg' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $payroll = Payroll::create([
                'month' => $validateData['month'],
                'state' => $validateData['state'],
                'sctr_p' => $validateData['sctr_p'],
                'sctr_s' => $validateData['sctr_s'],
            ]);
            $payroll->setAppends(['total_amount']);
            foreach ($validateData['pension_system'] as $pension) {
                Pension::create([
                    'type' => $pension['type'],
                    'values' => $pension['values'],
                    'values_seg' => $pension['values_seg'],
                    'payroll_id' => $payroll->id
                ]);
            }

            $listPension = $payroll->load('pension');
            $employees = Employee::select('id')->with('contract')->get();
            foreach ($employees as $employee) {
                $payrollDetail = PayrollDetail::create([
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
                    'pension_id' => $listPension->pension->firstWhere('type', $employee->contract->pension_type)->id
                ]);
            }
            $listType = ['Salary', 'Travel'];
            foreach ($listType as $item) {
                PayrollDetailExpense::create([
                    'payroll_detail_id' => $payrollDetail->id,
                    'type' => $item
                ]);
            }
            DB::commit();
            return response()->json($payroll, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function index_payroll(Request $request, $payroll_id)
    {
        if ($request->isMethod('get')) {
            $payroll = Payroll::find($payroll_id);
            $spreadsheet = PayrollDetail::with('payroll', 'payroll_detail_expense', 'employee', 'pension')->where('payroll_id', $payroll_id)->get();
            $total = $this->calculateTotal($spreadsheet);
            return Inertia::render('HumanResource/Payroll/Spreadsheets', [
                'spreadsheet' => $spreadsheet,
                'payroll' => $payroll,
                'total' => $total,
            ]);
        } elseif ($request->isMethod('post')) {
            $searchQuery = $request->searchQuery;
            $spreadsheet = PayrollDetail::with('payroll', 'payroll_detail_expense', 'employee', 'pension')
                ->where('payroll_id', $request->payroll_id)
                ->whereHas('employee', function ($query) use ($searchQuery) {
                    $query->where('name', 'like', "%$searchQuery%")
                        ->orWhere('lastname', 'like', "%$searchQuery%");
                })
                ->get();
            $total = $this->calculateTotal($spreadsheet);
            return response()->json(
                [
                    'spreadsheet' => $spreadsheet,
                    'total' => $total,
                ],
                200
            );
        }
    }

    private function calculateTotal($spreadsheet)
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

    public function update_payroll_salary(Request $request, $payroll_details_id)
    {
        $validateData = $request->validate([
            'operation_date' => 'required',
            'operation_number' => 'required',
        ]);
        $payrollDetailExpense = PayrollDetailExpense::where('payroll_detail_id', $payroll_details_id)
            ->where('type', 'Salary')
            ->first();
        $payrollDetailExpense->update([
            'operation_date' => $validateData['operation_date'],
            'operation_number' => $validateData['operation_number'],
        ]);
        $payrollExpense = PayrollDetailExpense::where('payroll_detail_id', $payroll_details_id)->get();
        return response()->json($payrollExpense, 200);
    }

    public function update_payroll_travelExpense(Request $request, $payroll_details_id)
    {
        $validateData = $request->validate([
            'operation_date' => 'required',
            'operation_number' => 'required',
        ]);
        $payrollDetailExpense = PayrollDetailExpense::where('payroll_detail_id', $payroll_details_id)
            ->where('type', 'Travel')
            ->first();
        $payrollDetailExpense->update([
            'operation_date' => $validateData['operation_date'],
            'operation_number' => $validateData['operation_number'],
        ]);
        $payrollExpense = PayrollDetailExpense::where('payroll_detail_id', $payroll_details_id)->get();
        return response()->json($payrollExpense, 200);
    }

    public function export($payroll_id)
    {
        return Excel::download(new PayrollExport($payroll_id), 'Planilla ' . date('m-Y') . '.xlsx');
    }

    public function discount_employee(Request $request, $employee_id)
    {
        $validateData = $request->validate([
            'discount' => 'required|numeric'
        ]);
        try {
            $validateData['discount'] = floatval($validateData['discount']);
            $payrollDetail = PayrollDetail::where('employee_id', $employee_id)
                ->first();
            $payrollDetail->update([
                'discount' => $validateData['discount']
            ]);
            $payrollDetail->load('employee', 'payroll_detail_expense');
            return response()->json($payrollDetail, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
