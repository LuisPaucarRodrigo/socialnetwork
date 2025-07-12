<?php

namespace App\Http\Controllers\HumanResource;

use App\Constants\PayrollConstants;
use App\Constants\PintConstants;
use App\Constants\ProjectConstants;
use App\Exports\Payroll\PayrollBankExport;
use App\Exports\Payroll\PayrollDetailsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\Payroll\StoreMasivePayrollDetailExpensesRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailExpenseRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailMonetaryDiscountRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailMonetaryIncomeRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailTaxAndContributionRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailWorkScheduleRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollExternalDetailRequest;
use App\Http\Requests\HumanResource\StorePayrollRequest;
use App\Http\Requests\UpdateMasiveOpNuDateRequest;
use App\Models\Employee;
use App\Models\ExternalEmployee;
use App\Models\GeneralExpense;
use App\Models\IncomeParam;
use App\Models\DiscountParam;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollDetailExpense;
use App\Models\PayrollDetailMonetaryIncome;
use App\Models\PayrollDetailMonetaryDiscount;
use App\Models\PayrollDetailTaxAndContribution;
use App\Models\PayrollDetailWorkSchedule;
use App\Models\PayrollExternalDetail;
use App\Models\TaxAndContributionParam;
use App\Services\HumanResource\PayrollDetailExpensesServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AccountStatement;
use App\Services\HumanResource\PayrollServices;
use Illuminate\Support\Arr;

class SpreadsheetsController extends Controller
{
    protected $payrollServices;
    protected $payrollDetailExpensesServices;

    public function __construct(PayrollServices $payrollServices, PayrollDetailExpensesServices $payrollDetailExpensesServices)
    {
        $this->payrollServices = $payrollServices;
        $this->payrollDetailExpensesServices = $payrollDetailExpensesServices;
    }


    public function index()
    {
        $payroll = $this->payrollServices->payrollBase()->paginate();
        $payroll = $this->payrollServices->addedCalculated($payroll);
        return Inertia::render('HumanResource/Payroll/Index/Index', [
            'payroll' => $payroll
        ]);
    }

    public function update_payroll_state($payroll_id)
    {
        $payroll = $this->payrollServices->findPayroll($payroll_id);
        $payroll->update([
            'state' => true
        ]);
        return response()->json($payroll, 200);
    }

    public function store_payroll(StorePayrollRequest $request)
    {
        $validateData = $request->validated();
        DB::beginTransaction();
        try {
            //Create Payroll
            $data = collect($validateData)->only(['month', 'state'])->toArray();
            $payroll = $this->payrollServices->createPayroll($data);
            $payroll->setAppends(['total_amount']);
            //Create associated pensions
            $this->payrollServices->forCreatePension($validateData, $payroll);
            //Cargar pensiones
            $payroll->load('pension');
            $listPension = $payroll;
            //Obtener empleados activos
            $employees = $this->payrollServices->getActiveEmployees($payroll['month'])->get();
            //Crear detalles por empleado
            foreach ($employees as $employee) {
                $this->payrollServices->createPayrollDetailForEmployee($employee, $payroll, $listPension);
                // $this->payrollServices->createPayrollDetailExpenses($payrollDetail);
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
            // Obtener nómina y detalles con el servicio
            $payroll = Payroll::find($payroll_id);
            $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id)->get()->each->append('new_totals');
            $total = $this->payrollServices->calculateTotal($spreadsheet);
            $pensionTypes = PayrollConstants::payrollPensionTypes();
            return Inertia::render('HumanResource/Payroll/Spreadsheets/Index/Index', [
                'spreadsheet' => $spreadsheet,
                'payroll' => $payroll,
                'total' => $total,
                'pensionTypes' => $pensionTypes
            ]);
        } elseif ($request->isMethod('post')) {
            // Buscar detalles con filtro de búsqueda
            $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id, $request)->get()->each->append('new_totals');
            $total = $this->payrollServices->calculateTotal($spreadsheet);

            return response()->json(
                [
                    'spreadsheet' => $spreadsheet,
                    'total' => $total,
                ],
                200
            );
        }
    }

    public function generate_payroll_bill($payroll_detail_id)
    {
        $payroll_detail = PayrollDetail::with('payroll_detail_work_schedule')->find($payroll_detail_id);
        // $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id)->first();
        if (!$payroll_detail) {
            return response()->json(['message' => 'Payroll not found'], 404);
        }
        $days = $this->sum_schedule_days($payroll_detail->payroll_detail_work_schedule);
        $hours = $this->sum_schedule_hours($payroll_detail->payroll_detail_work_schedule);

        $payroll_data = [
            'month' => $payroll_detail->payroll->month,
            'dni' => $payroll_detail->employee->dni,
            'name' => $payroll_detail->employee_name,
            'hire_date' => $payroll_detail->employee->contract->hire_date,
            'pension_type' => $payroll_detail->pension->type,
            'cuspp' => $payroll_detail->employee->contract->cuspp ?? 'Sin Cuspp',

            'days_worked' => $days['result'],
            'days_not_worked' => $days['countNotSub'],
            'days_subsided' => $days['countSub'],
            'subsidized_days' => $days['result'],

            'total_ordinary_hours' => $hours['regular_hours']['hours'] ?? 0,
            'total_ordinary_minutes' => $hours['regular_hours']['minutes'] ?? 0,
            'total_overtime_hours' => $hours['overtime_hours']['hours'] ?? 0,
            'total_overtime_minutes' => $hours['overtime_hours']['minutes'] ?? 0,

            'basic_salary' => $payroll_detail->basic_salary,
            'cts' => $payroll_detail->cts,
            'rnp_amount' => $payroll_detail->rnp_amount,
            'cp_amount' => $payroll_detail->cp_amount,
            'rq_amount' => $payroll_detail->rq_amount,
            'ps_amount' => $payroll_detail->ps_amount,
            'ap_amount' => $payroll_detail->ap_amount,
            'net_pay' => $payroll_detail->new_totals['net_pay'],
            'eSalud' => $payroll_detail->basic_salary * 0.1,
        ];
        $pdf = Pdf::loadView('pdf.PayrollBill', [
            'spreadsheet' => $payroll_data
        ]);
        return $pdf->stream('Planilla.pdf');
    }

    public function sum_schedule_days($schedule): array
    {
        $countSub = 0;
        $countNotSub = 0;
        if ($schedule != null) {
            foreach ($schedule->subsidized_days as $item) {
                $countSub += $item['quantity'];
            }
            foreach ($schedule->non_subsidized_days as $item) {
                $countNotSub += $item['quantity'];
            }
        }
        $result = 31 - $countSub - $countNotSub;
        return ['result' => $result, 'countSub' => $countSub, 'countNotSub' => $countNotSub];
    }

    public function sum_schedule_hours($schedule): array
    {
        $hours = [];
        if ($schedule != null) {
            [$hoursO, $minutesO] = explode(':', $schedule->regular_hours);
            [$hoursS, $minutesS] = explode(':', $schedule->overtime_hours);

            $hours = [
                'regular_hours' => ['hours' => $hoursO, 'minutes' => $minutesO],
                'overtime_hours' => ['hours' => $hoursS, 'minutes' => $minutesS],
            ];
        }

        return $hours;
    }

    public function export_excel_payroll_detail($payroll_id)
    {
        $payroll = Payroll::find($payroll_id);
        $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id)->get()->each->append('new_totals');
        $total = $this->payrollServices->calculateTotal($spreadsheet);
        $data = compact('payroll', 'spreadsheet', 'total');
        return Excel::download(new PayrollDetailsExport($data, 'general'), 'Nómina - Datos generales.xlsx');
    }

    public function export_detail_excel_payroll_detail(Request $request, $payroll_id)
    {

        $registers = collect($request->registerSelected);
        $appends = [];
        $dataMap = [
            'Ingresos' => [
                'variable' => 'incomes',
                'query' => fn() => IncomeParam::all(),
                'append' => 'monetary_incomes_by_ids',
            ],
            'Descuentos' => [
                'variable' => 'discounts',
                'query' => fn() => DiscountParam::all(),
                'append' => 'monetary_discounts_by_ids',
            ],
            'Tributos' => [
                'variable' => 'tacEmployee',
                'query' => fn() => TaxAndContributionParam::where('type', 'employee')->get(),
                'append' => 'tax_contribution_employee_by_ids',
            ],
            'Aportes' => [
                'variable' => 'tacEmployer',
                'query' => fn() => TaxAndContributionParam::where('type', 'employer')->get(),
                'append' => 'tax_contribution_employer_by_ids',
            ],
        ];
        $data = [
            'incomes' => null,
            'discounts' => null,
            'tacEmployee' => null,
            'tacEmployer' => null,
        ];
        foreach ($dataMap as $key => $config) {
            if ($registers->contains($key)) {
                $data[$config['variable']] = $config['query']();
                $appends[] = $config['append'];
            }
        }
        $data['payroll'] = Payroll::findOrFail($payroll_id);
        $data['spreadsheet'] = $this->payrollServices
            ->getPayrollDetailsAllValues($payroll_id)
            ->get()
            ->each
            ->append($appends);

        return Excel::download(new PayrollDetailsExport($data, 'detail'), 'Nómina - Datos detallados.xlsx');
    }


    public function index_payroll_detail($payroll_details_id, $employee_id)
    {
        return Inertia::render("HumanResource/Payroll/Spreadsheets/Detail/Index", [
            'payroll_detail' => PayrollDetail::find($payroll_details_id)
                ->setAppends([])
                ->setAppends(['mod_days']),
            'employee_id' => $employee_id,
        ]);
    }

    public function index_worder_data($employee_id)
    {
        $response = Employee::with(['contract:employee_id,hire_date,fired_date,pension_type,id'])->find($employee_id);
        return response()->json($response, 200);
    }

    ///////////////////////////

    public function show_payroll_detail_work_schedule($payroll_detail_id)
    {
        $data = PayrollDetailWorkSchedule::where('payroll_detail_id', $payroll_detail_id)->first();
        return response()->json($data);
    }

    public function store_payroll_detail_work_schedule(StorePayrollDetailWorkScheduleRequest $request)
    {
        $data = $request->validated();
        $data['regular_hours'] =  $data['regular_hours_0'] . ':' . $data['regular_hours_1'];
        $data['overtime_hours'] = $data['overtime_hours_0'] . ':' . $data['overtime_hours_1'];
        PayrollDetailWorkSchedule::updateOrCreate(['id' => $data['id']], $data);
        return response()->json();
    }

    public function show_payroll_detail_monetary_income($payroll_detail_id)
    {
        $income_params = IncomeParam::all();
        $monetary_incomes = PayrollDetailMonetaryIncome::where('payroll_detail_id', $payroll_detail_id)->get()
            ->keyBy('income_param_id')->toArray();
        return response()->json(
            [
                'income_params' => $income_params,
                'monetary_incomes' => (object)$monetary_incomes
            ]
        );
    }

    public function store_payroll_monetary_income(StorePayrollDetailMonetaryIncomeRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollDetailMonetaryIncome::updateOrCreate(['id' => $data['id']], $data);
        $res = PayrollDetailMonetaryIncome::find($rg->id);
        return response()->json($res);
    }


    public function show_payroll_detail_monetary_discount($payroll_detail_id)
    {
        $discount_params = DiscountParam::all();
        $monetary_discounts = PayrollDetailMonetaryDiscount::where('payroll_detail_id', $payroll_detail_id)->get()
            ->keyBy('discount_param_id')->toArray();
        return response()->json([
            'discount_params' => $discount_params,
            'monetary_discounts' => (object)$monetary_discounts
        ]);
    }

    public function store_payroll_monetary_discount(StorePayrollDetailMonetaryDiscountRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollDetailMonetaryDiscount::updateOrCreate(['id' => $data['id']], $data);
        $res = PayrollDetailMonetaryDiscount::find($rg->id);
        return response()->json($res);
    }

    public function show_payroll_detail_tax_and_contribution($payroll_detail_id)
    {
        $tax_and_contribution_params = TaxAndContributionParam::all();
        $taxes_contributions = PayrollDetailTaxAndContribution::where('payroll_detail_id', $payroll_detail_id)->get()
            ->keyBy('t_a_c_param_id')->toArray();
        return response()->json([
            'tax_and_contribution_params' => $tax_and_contribution_params,
            'taxes_contributions' => (object)$taxes_contributions
        ]);
    }

    public function store_payroll_tax_and_contribution(StorePayrollDetailTaxAndContributionRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollDetailTaxAndContribution::updateOrCreate(['id' => $data['id']], $data);
        $res = PayrollDetailTaxAndContribution::find($rg->id);
        return response()->json($res);
    }


    //for 4ta category -- external employees
    public function index_payroll_external_detail($payroll_id)
    {
        $data = PayrollExternalDetail::with('external_employee')->where('payroll_id', $payroll_id)->get();
        $external_employees = ExternalEmployee::all();
        return Inertia::render('HumanResource/Payroll/Spreadsheets/ExternalDetail/Index', [
            'payroll_external_details' => $data,
            'external_employees' => $external_employees,
            'payroll_id' => (int)$payroll_id
        ]);
    }

    public function store_payroll_external_detail(StorePayrollExternalDetailRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollExternalDetail::updateOrCreate(['id' => $data['id']], $data);
        $res = PayrollExternalDetail::with('external_employee')->find($rg->id);
        return response()->json($res);
    }
    public function destroy_payroll_external_detail($payroll_detail_id)
    {
        $rg = PayrollExternalDetail::findOrFail($payroll_detail_id);
        $rg->delete();
        return response()->json();
    }


    //paying spreadsheets
    public function store_pay_spreedsheets(StoreMasivePayrollDetailExpensesRequest $request)
    {
        $data = $request->validated();
        foreach ($data['payroll_detail_expenses'] as $i => $item) {
            $as = self::findAccountStatement($item);
            $item['account_statement_id'] = $as?->id;
            $item['type'] = ProjectConstants::EXP_TYPE_PAYROLL;
            $ge = GeneralExpense::create($item);
            $item['general_expense_id'] = $ge->id;
            PayrollDetailExpense::create($item);
        }
        return response()->json();
    }

    public function show_payroll_detail_expense_constants()
    {
        return response()->json([
            'expenseTypes' => PayrollConstants::payrollExpenseTypes(),
            'docTypes' => PayrollConstants::payrollDocTypes(),
        ]);
    }

    public function index_payroll_detail_expense($payroll_id)
    {
        $data = PayrollDetailExpense::with('general_expense')->whereHas(
            'payroll_detail',
            function ($query) use ($payroll_id) {
                $query->where('payroll_id', $payroll_id);
            }
        )->paginate(20);
        $data->getCollection()->each->append('real_state');
        return Inertia::render('HumanResource/Payroll/Spreadsheets/Expense/Index', [
            'expenses' => $data,
            'expenseTypes' => PayrollConstants::payrollExpenseTypes(),
            'docTypes' => PayrollConstants::payrollDocTypes(),
            'stateTypes' => PintConstants::scStatesTypes(),
            'payroll' => Payroll::findOrFail($payroll_id),
        ]);
    }

    public function show_payroll_detail_expenses($payroll_detail_id)
    {
        $data = PayrollDetailExpense::with('general_expense')->where('payroll_detail_id', $payroll_detail_id)->get();
        return response()->json($data);
    }

    public function store_payroll_detail_expense(StorePayrollDetailExpenseRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $as = self::findAccountStatement($data['general_expense']);
            $data['general_expense']['account_statement_id'] = $as?->id;
            $rg = PayrollDetailExpense::find($data['id']);
            if ($request->hasFile('photo')) {
                $filename = $rg?->photo;
                if ($filename) {
                    $this->file_delete($filename, 'documents/payrollexpenses/');
                }
                $data['photo'] = $this->file_store($request->file('photo'), 'documents/payrollexpenses/');
            }
            if ($data['photo_status'] === 'stable') {
                $filename = $rg?->photo;
                if ($filename) {
                    unset($data["photo"]);
                }
            }
            if ($data['photo_status'] === 'delete') {
                $filename = $rg?->photo;
                if ($filename) {
                    $this->file_delete($filename, 'documents/payrollexpenses/');
                }
            }
            $ge = GeneralExpense::updateOrCreate(['id' => $data['general_expense']['id']], $data['general_expense']);
            $data['general_expense_id'] = $ge->id;
            $rg = PayrollDetailExpense::updateOrCreate(['id' => $data['id']], $data);
            $rg->load('general_expense');
            $rg->append('real_state');
            DB::commit();
            return response()->json($rg, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function destroy_payroll_detail_expense($payroll_detail_expense_id)
    {
        DB::beginTransaction();
        try {
            $rg = PayrollDetailExpense::findOrFail($payroll_detail_expense_id);
            $rg->photo && $this->file_delete($rg->photo, 'documents/payrollexpenses/');
            $rg->delete();
            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => 'Server Error'], 500);
        }
    }

    public function masive_update_payroll_detail_expense(UpdateMasiveOpNuDateRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $as =  self::findAccountStatement($data);
            $data['account_statement_id'] = $as?->id;
            $ids = Arr::pull($data, 'ids');
            $costs = PayrollDetailExpense::whereIn('id', $ids)->get();
            foreach ($costs as $cost) {
                $cost->general_expense->update($data);
            }
            $updatedCosts = PayrollDetailExpense::whereIn('id', $ids)
                ->with(['general_expense'])->get()->each->append('real_state');
            DB::commit();
            return response()->json($updatedCosts, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => 'Server Error'], 500);
        }
    }


    public function search_payroll_detail_expenses(Request $request, $payroll_id)
    {
        $query = PayrollDetailExpense::with('general_expense')
            ->whereHas('payroll_detail', function ($q) use ($payroll_id) {
                $q->where('payroll_id', $payroll_id);
            });
        $result = $this->payrollDetailExpensesServices->filter($request, $query);
        return response()->json($result, 200);
    }








    protected static function findAccountStatement($item)
    {
        if (isset($item['operation_number']) && isset($item['operation_date'])) {
            $on = substr($item['operation_number'], -6);
            return AccountStatement::where('operation_date', $item['operation_date'])
                ->where('operation_number', $on)->first();
        }
        return null;
    }

    public function file_store($file, $path)
    {
        $name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $name);
        return $name;
    }

    public function file_delete($filename, $path)
    {
        $file_path = $path . $filename;
        $path = public_path($file_path);
        if (file_exists($path))
            unlink($path);
    }
}
