<?php

namespace App\Http\Controllers\Finance;

use App\Exports\AccountStatementExport;
use App\Exports\Payroll\PayrollBankExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStatement\AccountStatementImportRequest;
use App\Http\Requests\AccountStatement\AccountStatementRequest;
use App\Imports\AccountStatementImport;
use App\Models\GeneralExpense;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AccountStatement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AccountStatementController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Finance/AccountStatement/AccountStatement',
            $this->getAccountVariables()
        );
    }


    public function store(AccountStatementRequest $request, $as_id = null)
    {
        $data = $request->validated();
        $geIds = $data["geData"];
        $rg = AccountStatement::updateOrCreate(['id' => $as_id], $data);
        $this->syncOneToMany($rg, 'GeneralExpense', $geIds, 'account_statement_id');
        $operationDate = Carbon::parse($data['operation_date']);
        $month = $operationDate->format('Y-m');
        $data = $this->getAccountVariables($request->month, $request->endMonth, $request->all);
        return response()->json(['dataToRender' => $data, 'month' => $month], 200);
    }


    public function destroy(Request $request, $as_id)
    {
        $as = AccountStatement::findOrFail($as_id);
        $as->delete();
        $data = $this->getAccountVariables($request->month, $request->endMonth, $request->all);
        return response()->json(['dataToRender' => $data], 200);
    }


    public function searchStatements(Request $request)
    {
        $data = $this->getAccountVariables($request->month, $request->endMonth, $request->all);
        return response()->json($data, 200);
    }

    public function dowloadDataStructure()
    {
        $filePath = 'DataStructures/Plantilla_Estado_de_Cuenta.xlsx';
        ob_end_clean();
        return response()->download($filePath);
    }

    public function importExcel(AccountStatementImportRequest $request)
    {
        $data = $request->validated();
        try {
            Excel::import(new AccountStatementImport, $data['excel_file']);
            return response()->json(['msg' => 'Datos Importados'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al importar los datos',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function masiveDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required | array | min:1',
            'ids.*' => 'integer'
        ]);
        AccountStatement::whereIn('id', $data['ids'])->delete();
        $data = $this->getAccountVariables($request->month, $request->endMonth, $request->all);
        return response()->json(['dataToRender' => $data], 200);
    }

    public function excel_export(Request $request)
    {
        return Excel::download(new AccountStatementExport($request), 'Estado_de_cuenta.xlsx');
    }





    public function searchCosts(Request $request)
    {
        $od = $request->operation_date;
        $on = $request->operation_number;
        $geData =  GeneralExpense::select('id', 'zone', 'expense_type', 'location', 'amount', 'account_statement_id')
            ->where('operation_date', $od)
            ->whereRaw("RIGHT(operation_number, 6) = ?", [$on])
            ->get();
        return response()->json(['geData' => $geData]);
    }


    public function searchStatementsCosts($as_id)
    {
        $geData = GeneralExpense::select('id', 'zone', 'expense_type', 'location', 'amount', 'account_statement_id')->where('account_statement_id', $as_id)->get();
        return response()->json(['geData' => $geData]);
    }



    public function syncOneToMany($parentModel, $childModelClass, array $childIds, $foreignKey)
    {
        $childModelClass = "App\Models\\" . $childModelClass;
        $childModelClass::where($foreignKey, $parentModel->id)
            ->whereNotIn('id', $childIds)
            ->update([$foreignKey => null]);
        $childModelClass::whereIn('id', $childIds)
            ->update([$foreignKey => $parentModel->id]);
    }



    private function previousBalance($month = null, $all = false)
    {
        if ($all)
            return 0;
        if ($month) {
            $inputMonth = Carbon::parse($month)->month;
            $inputYear = Carbon::parse($month)->year;
        } else {
            $inputMonth = Carbon::now()->month;
            $inputYear = Carbon::now()->year;
        }
        $previousBalance = AccountStatement::where(function ($query) use ($inputMonth, $inputYear) {
            $query->whereYear('operation_date', '<', $inputYear)
                ->orWhere(function ($subQuery) use ($inputMonth, $inputYear) {
                    $subQuery->whereYear('operation_date', $inputYear)
                        ->whereMonth('operation_date', '<', $inputMonth);
                });
        })
            ->get()
            ->reduce(function ($carry, $statement) {
                return $carry + ($statement->payment - $statement->charge);
            }, 0);
        return $previousBalance;
    }



    private function getAccountVariables($month = null, $endMonth = null, $all = false)
    {
        if ($month) {
            $currentMonth = Carbon::parse($month)->month;
            $currentYear = Carbon::parse($month)->year;
        } else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
        }
        if ($endMonth) {
            $inputEndMonth = Carbon::parse($endMonth)->month;
            $inputEndYear = Carbon::parse($endMonth)->year;
        } else {
            $inputEndMonth = Carbon::now()->month;
            $inputEndYear = Carbon::now()->year;
        }

        $previousBalance = $this->previousBalance($month, $all);
        $currentBalance = $previousBalance;
        $totalCharge = 0;
        $totalPayment = 0;
        $balanceMedia = 0;
        $totalITFM = 0;
        $accountStatements = AccountStatement::select(
            'id',
            'operation_date',
            'operation_number',
            'description',
            'charge',
            'payment',

        );

        if (!$all) {
            if ($endMonth) {
                $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::create($inputEndYear, $inputEndMonth, 1)->endOfMonth()->format('Y-m-d');
            } else {
                $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth()->format('Y-m-d');
            }
            $accountStatements = $accountStatements->whereBetween('operation_date', [$startDate, $endDate]);
        }


        $accountStatements = $accountStatements->orderBy('operation_date', 'asc')->get();
        $accountStatements->transform(function ($item) {
            $item->setAppends(['state']);
            return $item;
        });
        $accountStatements = $accountStatements->map(function ($statement) use (&$currentBalance, &$totalCharge, &$totalPayment, &$balanceMedia, &$totalITFM) {
            $totalCharge += $statement->charge;
            $totalPayment += $statement->payment;
            $currentBalance += $statement->payment - $statement->charge;
            $statement->balance = $currentBalance;
            $balanceMedia += $statement->balance;
            if ($statement->operation_number === null) {
                $totalITFM += $statement->charge;
            }
            return $statement;
        });
        $balanceMedia = $balanceMedia / ($accountStatements->count() ?: 1);
        return [
            'accountStatements' => $accountStatements,
            'currentBalance' => $currentBalance,
            'previousBalance' => $previousBalance,
            'totalCharge' => $totalCharge,
            'totalPayment' => $totalPayment,
            'balanceMedia' => $balanceMedia,
            'totalITFM' => $totalITFM,
        ];
    }

    public function export_bank_table($date)
    {
        $fecha = Carbon::createFromFormat('Y-m', $date);

        $month = $fecha->month;
        $year = $fecha->year;
        $accountStatements = AccountStatement::with(['general_expenses'])
            ->whereMonth('operation_date', $month)
            ->whereYear('operation_date', $year)
            ->orderBy('operation_date', 'asc')
            ->get();

        $exportData = [];
        foreach ($accountStatements as $statement) {
            $facturas = [];
            $boletas = [];
            $rrhh = [];
            $efectivoTotal = 0;
            foreach ($statement->general_expenses as $expense) {
                $type = strtolower($expense->type_doc);

                if ($type === 'factura' && $expense->doc_number) {
                    $facturas[] = $expense->doc_number . ';';
                } elseif (($type === 'boleta') && $expense->doc_number) {
                    $boletas[] = $expense->doc_number . ';';
                } elseif (($type === 'recibo por honorarios') && $expense->doc_number) {
                    $rrhh[] = $expense->doc_number . ';';
                } elseif ($type === 'sin comprobante' || $type === 'efectivo') {
                    $efectivoTotal += floatval($expense->amount);
                }
            }
            $exportData[] = [
                'operation_date' => $statement->operation_date,
                'operation_number' => $statement->operation_number,
                'description' => $statement->description,
                'bills' => $facturas,
                'sales_receipt' => $boletas,
                'rr_hh' => $rrhh,
                'cash' => $efectivoTotal,
                'expenses' => $statement->charge ? 'S/ ' . number_format($statement->charge, 2, '.', '') : '',
                'income' => $statement->payment ? 'S/ ' . number_format($statement->payment, 2, '.', '') : '',
            ];
        }
        $fecha = Carbon::createFromDate($year, $month, 1)->locale('es');
        $formatDate = ucfirst($fecha->translatedFormat('F Y'));
        return Excel::download(new PayrollBankExport($exportData), 'Tabla de Banco - ' . $formatDate . '.xlsx');
    }
}
