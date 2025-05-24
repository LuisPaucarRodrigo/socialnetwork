<?php

namespace App\Http\Controllers\HumanResource;

use App\Exports\Payroll\PayrollExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDetailWorkScheduleRequest;
use App\Http\Requests\HumanResource\StorePayrollRequest;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollDetailExpense;
use App\Models\PayrollDetailWorkSchedule;
use App\Models\Pension;
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

    public function __construct(PayrollServices $payrollServices)
    {
        $this->payrollServices = $payrollServices;
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
                $this->payrollServices->createPayrollDetailExpenses($payrollDetail);
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
            $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id)->get();
            $total = $this->payrollServices->calculateTotal($spreadsheet);

            return Inertia::render('HumanResource/Payroll/Spreadsheets/Index/Index', [
                'spreadsheet' => $spreadsheet,
                'payroll' => $payroll,
                'total' => $total,
            ]);
        } elseif ($request->isMethod('post')) {
            // Buscar detalles con filtro de búsqueda
            $searchQuery = $request->searchQuery;
            $spreadsheet = $this->payrollServices->getPayrollDetails($payroll_id, $searchQuery)->get();
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
}
