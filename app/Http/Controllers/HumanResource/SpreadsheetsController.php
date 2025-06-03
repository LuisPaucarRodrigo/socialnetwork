<?php

namespace App\Http\Controllers\HumanResource;

use App\Constants\PayrollConstants;
use App\Constants\PintConstants;
use App\Constants\ProjectConstants;
use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\Payroll\StoreMasivePayrollDetailExpensesRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailMonetaryDiscountRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailMonetaryIncomeRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailTaxAndContributionRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailWorkScheduleRequest;
use App\Http\Requests\HumanResource\Payroll\StorePayrollExternalDetailRequest;
use App\Http\Requests\HumanResource\StorePayrollRequest;
use App\Models\Contract;
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
use App\Models\Pension;
use App\Models\TaxAndContributionParam;
use App\Services\HumanResource\PayrollDetailExpensesServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AccountStatement;
use App\Services\HumanResource\PayrollServices;

class SpreadsheetsController extends Controller
{
    protected $payrollServices;
    protected $payrollDetailExpensesServices;

    public function __construct(PayrollServices $payrollServices, PayrollDetailExpensesServices $payrollDetailExpensesServices) {
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
            $data = collect($validateData)->only(['month', 'state', 'sctr_p', 'sctr_s'])->toArray();
            $payroll = $this->payrollServices->createPayroll($data);
            $payroll->setAppends(['total_amount']);
            //Create associated pensions
            $this->payrollServices->forCreatePension($validateData, $payroll);
            //Cargar pensiones
            $listPension = $payroll->load('pension');
            //Obtener empleados activos
            $employees = $this->payrollServices->getActiveEmployees()->get();
            //Crear detalles por empleado
            foreach ($employees as $employee) {
                $payrollDetail = $this->payrollServices->createPayrollDetailForEmployee($employee, $payroll, $listPension);
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

            return Inertia::render('HumanResource/Payroll/Spreadsheets/Index/Index', [
                'spreadsheet' => $spreadsheet,
                'payroll' => $payroll,
                'total' => $total,
            ]);
        } elseif ($request->isMethod('post')) {
            // Buscar detalles con filtro de búsqueda
            $searchQuery = $request->searchQuery;
            $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id, $searchQuery)->get()->each->append('new_totals');
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

    public function index_payroll_detail($payroll_details_id, $employee_id)
    {
        return Inertia::render("HumanResource/Payroll/Spreadsheets/Detail/Index", [
            'payroll_detail' => PayrollDetail::find($payroll_details_id)
                ->setAppends([])
                ->setAppends(['mod_days']),
            'employee_id' => $employee_id,
        ]);
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
        $data = [
            'operation_date' => $validateData['operation_date'],
            'operation_number' => $validateData['operation_number'],
        ];
        $payrollDetailExpense->update($data);
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
        $data = [
            'operation_date' => $validateData['operation_date'],
            'operation_number' => $validateData['operation_number'],
        ];
        $payrollDetailExpense->update($data);
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

    public function index_worder_data($employee_id)
    {
        $response = Employee::with(['contract:employee_id,hire_date,fired_date,pension_type,id'])->find($employee_id);
        return response()->json($response, 200);
    }

    ///////////////////////////

    public function show_payroll_detail_work_schedule ($payroll_detail_id) {
        $data = PayrollDetailWorkSchedule::where('payroll_detail_id', $payroll_detail_id)->first();
        return response()->json($data);
    }

    public function store_payroll_detail_work_schedule(StorePayrollDetailWorkScheduleRequest $request)
    {
        $data = $request->validated();
        $data['regular_hours'] =  $data['regular_hours_0'] . ':' . $data['regular_hours_1'];
        $data['overtime_hours'] = $data['overtime_hours_0'] . ':' . $data['overtime_hours_1'];
        PayrollDetailWorkSchedule::updateOrCreate(['id'=>$data['id']], $data);
        return response()->json();
    }

    public function show_payroll_detail_monetary_income ($payroll_detail_id) {
        $income_params = IncomeParam::all();
        $monetary_incomes = PayrollDetailMonetaryIncome::where('payroll_detail_id', $payroll_detail_id)->get()
        ->keyBy('income_param_id')->toArray();
        return response()->json( [
            'income_params' => $income_params, 
            'monetary_incomes' => (object)$monetary_incomes
        ]
    );
    }

    public function store_payroll_monetary_income (StorePayrollDetailMonetaryIncomeRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollDetailMonetaryIncome::updateOrCreate(['id'=>$data['id']], $data);
        $res = PayrollDetailMonetaryIncome::find($rg->id);
        return response()->json($res);
    }


    public function show_payroll_detail_monetary_discount ($payroll_detail_id) {
        $discount_params = DiscountParam::all();
        $monetary_discounts = PayrollDetailMonetaryDiscount::where('payroll_detail_id', $payroll_detail_id)->get()
        ->keyBy('discount_param_id')->toArray();
        return response()->json( [
            'discount_params' => $discount_params, 
            'monetary_discounts' => (object)$monetary_discounts
        ]);
    }

    public function store_payroll_monetary_discount (StorePayrollDetailMonetaryDiscountRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollDetailMonetaryDiscount::updateOrCreate(['id'=>$data['id']], $data);
        $res = PayrollDetailMonetaryDiscount::find($rg->id);
        return response()->json($res);
    }

    public function show_payroll_detail_tax_and_contribution ($payroll_detail_id) {
        $tax_and_contribution_params = TaxAndContributionParam::all();
        $taxes_contributions = PayrollDetailTaxAndContribution::where('payroll_detail_id', $payroll_detail_id)->get()
        ->keyBy('t_a_c_param_id')->toArray();
        return response()->json( [
            'tax_and_contribution_params' => $tax_and_contribution_params, 
            'taxes_contributions' => (object)$taxes_contributions
        ]);
    }

    public function store_payroll_tax_and_contribution (StorePayrollDetailTaxAndContributionRequest $request)
    {
        $data = $request->validated();
        $rg = PayrollDetailTaxAndContribution::updateOrCreate(['id'=>$data['id']], $data);
        $res = PayrollDetailTaxAndContribution::find($rg->id);
        return response()->json($res);
    }


    //for 4ta category -- external employees
    public function index_payroll_external_detail ($payroll_id) {
        $data = PayrollExternalDetail::with('external_employee')->where('payroll_id', $payroll_id)->get();
        $external_employees = ExternalEmployee::all();
        return Inertia::render('HumanResource/Payroll/Spreadsheets/ExternalDetail/Index', [
            'payroll_external_details' => $data,
            'external_employees' => $external_employees,
            'payroll_id' => (Integer)$payroll_id
        ]);
    }

    public function store_payroll_external_detail (StorePayrollExternalDetailRequest $request) {
        $data = $request->validated();
        $rg = PayrollExternalDetail::updateOrCreate(['id'=>$data['id']], $data);
        $res = PayrollExternalDetail::with('external_employee')->find($rg->id);
        return response()->json($res);
    }
    public function destroy_payroll_external_detail ($payroll_detail_id) {
        $rg = PayrollExternalDetail::findOrFail($payroll_detail_id);
        $rg->delete();
        return response()->json();
    }


    //paying spreadsheets
    public function store_pay_spreedsheets(StoreMasivePayrollDetailExpensesRequest $request) {
        $data = $request->validated();
        foreach($data['payroll_detail_expenses'] as $i=>$item){
            $as = self::findAccountStatement($item);
            $item['account_statement_id'] = $as?->id;
            $item['type'] = ProjectConstants::EXP_TYPE_PAYROLL;
            $ge = GeneralExpense::create($item);
            $item['general_expense_id'] = $ge->id;
            PayrollDetailExpense::create($item);
        }
        return response()->json();
    }

    public function show_payroll_detail_expense_constants() {
        return response()->json([
            'expenseTypes' => PayrollConstants::payrollExpenseTypes(),
            'docTypes' => PayrollConstants::payrollDocTypes(),
        ]);
    }


    public function index_payroll_detail_expense($payroll_id){
        $data = PayrollDetailExpense::with('general_expense')->whereHas('payroll_detail',
             function($query) use ($payroll_id) {
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

    public function search_payroll_detail_expenses(Request $request, $payroll_id)
    {
        $query = PayrollDetailExpense::with('general_expense')
            ->whereHas('payroll_detail', function($q) use ($payroll_id) {
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








}
