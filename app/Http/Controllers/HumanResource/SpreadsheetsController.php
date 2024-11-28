<?php

namespace App\Http\Controllers\HumanResource;

use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\Pension;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetsController extends Controller
{
    public function index()
    {
        $payroll = Payroll::orderBy('month', 'desc')->paginate();
        return Inertia::render('HumanResource/Payroll/Index', [
            'payroll' => $payroll
        ]);
    }

    public function store_payroll(Request $request)
    {
        $validateData = $request->validate([
            'month' => 'required|unique:payrolls,month',
            'state' => 'required',
            'pension_system.*.type' => 'required',
            'pension_system.*.values' => 'required',
            'pension_system.*.values_seg' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $payroll = Payroll::create([
                'month' => $validateData['month'],
                'state' => $validateData['state'],
            ]);
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
                PayrollDetail::create([
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
            DB::commit();
            return response()->json($payroll, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function index_payroll(Request $request, $payroll_id)
    {
        // if ($reentry == false) {
        //     $spreadsheet = Payroll::with('pension', 'employee')->where('state', 'Active');
        // } else {
        //     $spreadsheet = Payroll::with('pension', 'employee')->where('state', 'Inactive');
        // }
        // $searchTerm = strtolower($request->query('searchTerm'));
        // if ($searchTerm !== '') {
        //     $spreadsheet = $spreadsheet->where(function ($query) use ($searchTerm) {
        //         $query->whereHas('pension', function ($subQuery) use ($searchTerm) {
        //             $subQuery->where('type', 'like', '%' . $searchTerm . '%');
        //         })
        //             ->orWhereHas('employee', function ($subQuery) use ($searchTerm) {
        //                 $subQuery->where('name', 'like', '%' . $searchTerm . '%')
        //                     ->orWhere('lastname', 'like', '%' . $searchTerm . '%');
        //             });
        //     })->get();
        // } else {
        //     $spreadsheet = $spreadsheet->get();
        // }
        $payroll = Payroll::find($payroll_id);
        $spreadsheet = PayrollDetail::with('payroll', 'employee', 'pension')->where('payroll_id', $payroll_id)->get();
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
            'sum_sctr_p' => $spreadsheet->sum('sctr_p'),
            'sum_sctr_s' => $spreadsheet->sum('sctr_s'),
            'sum_total_contribution' => $spreadsheet->sum('total_contribution'),
        ];
        // dd($spreadsheet);
        // $data = json_decode(File::get(config_path('custom.json')), true);
        return Inertia::render('HumanResource/Payroll/Spreadsheets', [
            'spreadsheets' => $spreadsheet,
            'payroll' => $payroll,
            // 'boolean' => boolval($reentry),
            'total' => $total,
            // 'number_people' => $data['number_people'],
        ]);
    }

    public function edit()
    {
        $pensions = Pension::all();
        $data = json_decode(File::get(config_path('custom.json')), true);
        $sctrs = ['sctr_p' => $data['sctr_p'], 'sctr_s' => $data['sctr_s']];
        return Inertia::render('HumanResource/Payroll/PensionSystem', [
            'pensions' => $pensions,
            'sctrs' => [$sctrs]
        ]);
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

    public function update_number_people(Request $request)
    {
        $data = json_decode(File::get(config_path('custom.json')), true);
        $data['number_people'] = $request->number_people;
        File::put(config_path('custom.json'), json_encode($data));
    }

    public function export($payroll_id)
    {
        return Excel::download(new PayrollExport($payroll_id), 'Planilla ' . date('m-Y') . '.xlsx');
    }

    public function update_sctr_p(Request $request)
    {
        $data = json_decode(File::get(config_path('custom.json')), true);
        $data['sctr_p'] = $request->value;
        File::put(config_path('custom.json'), json_encode($data));
        return to_route('pension_system.edit');
    }

    public function update_sctr_s(Request $request)
    {
        $data = json_decode(File::get(config_path('custom.json')), true);
        $data['sctr_s'] = $request->value;
        File::put(config_path('custom.json'), json_encode($data));
        return to_route('pension_system.edit');
    }
}
