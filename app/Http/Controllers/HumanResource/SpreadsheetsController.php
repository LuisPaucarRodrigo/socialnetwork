<?php

namespace App\Http\Controllers\HumanResource;

use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\Pension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetsController extends Controller
{
    public function index()
    {
        $payroll = Payroll::all();
        return Inertia::render('HumanResource/Payroll/Spreadsheets', [
            'payroll' => $payroll
        ]);
    }

    public function store_payroll(Request $request)
    {
        $validateDate = $request->validate();
        $payroll = Payroll::create($validateDate);
        $pension_system = Pension::create
        $employees = Employee::select('id')->get();
        foreach ($employees as $employee) {
            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'employee_id' => $employee->id,
                'pension_id' => 1
            ]);
        }
    }

    public function index_payroll(Request $request, $reentry = false)
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
            'sum_sctr_p' => $spreadsheet->sum('sctr_p'),
            'sum_sctr_s' => $spreadsheet->sum('sctr_s'),
            'sum_total_contribution' => $spreadsheet->sum('total_contribution'),
        ];
        $data = json_decode(File::get(config_path('custom.json')), true);
        return Inertia::render('HumanResource/Payroll/Spreadsheets', [
            'spreadsheets' => $spreadsheet,
            'search' => $searchTerm,
            'boolean' => boolval($reentry),
            'total' => $total,
            'number_people' => $data['number_people'],
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

    public function export()
    {
        return Excel::download(new PayrollExport, 'Planilla ' . date('m-Y') . '.xlsx');
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
