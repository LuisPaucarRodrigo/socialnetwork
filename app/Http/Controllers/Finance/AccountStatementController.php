<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStatement\AccountStatementImportRequest;
use App\Http\Requests\AccountStatement\AccountStatementRequest;
use App\Imports\AccountStatementImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\PayrollDetailExpense;
use App\Models\AccountStatement;
use App\Models\AdditionalCost;
use App\Models\PextProjectExpense;
use App\Models\StaticCost;
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
        $scIds = $data["scData"];
        $acIds = $data["acData"];
        $peIds = $data["peData"];
        $spIds = $data["spData"];
        $rg = AccountStatement::updateOrCreate(['id' => $as_id], $data);
        $this->syncOneToMany($rg, 'AdditionalCost', $acIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'StaticCost', $scIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'PextProjectExpense', $peIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'PayrollDetailExpense', $spIds, 'account_statement_id');
        $operationDate = Carbon::parse($data['operation_date']);
        $month = $operationDate->format('Y-m');
        $data = $this->getAccountVariables($request->month, $request->all);
        return response()->json(['dataToRender'=>$data, 'month'=>$month], 200);
    }

    public function destroy(Request $request, $as_id)
    {
        $as = AccountStatement::findOrFail($as_id);
        $as->delete();
        $data = $this->getAccountVariables($request->month, $request->all);
        return response()->json(['dataToRender'=>$data], 200);
    }

    
    public function searchStatements(Request $request)
    {
        $data = $this->getAccountVariables($request->month, $request->all);
        return response()->json($data, 200);
    }


    public function importExcel (AccountStatementImportRequest $request){
        $data = $request->validated();
        try{
            Excel::import(new AccountStatementImport, $data['excel_file']);
            return response()->json(['msg'=>'Datos Importados'], 200);
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
        $data = $this->getAccountVariables($request->month, $request->all);
        return response()->json(['dataToRender'=>$data], 200);
    }





    public function searchCosts(Request $request)
    {
        $od = $request->operation_date;
        $on = $request->operation_number;

        $acData = AdditionalCost::select('id', 'expense_type', 'zone', 'amount', 'project_id')
            ->with([
                'project' => function ($query) {
                    $query->select('id', 'preproject_id');
                }
            ])
            ->where('operation_date', $od)
            ->whereRaw("RIGHT(operation_number, 6) = ?", [$on])
            ->get();
        $acData->transform(function ($item) {
            $item->project->setAppends(['name']);
            $item->setAppends([]);
            return $item;
        });

        $scData = StaticCost::select('id', 'expense_type', 'zone', 'amount', 'project_id')
            ->with([
                'project' => function ($query) {
                    $query->select('id', 'preproject_id');
                }
            ])
            ->where('operation_date', $od)
            ->whereRaw("RIGHT(operation_number, 6) = ?", [$on])
            ->get();
        $scData->transform(function ($item) {
            $item->project->setAppends(['name']);
            $item->setAppends([]);
            return $item;
        });


        $peData = PextProjectExpense::select('id', 'expense_type', 'zone', 'amount', 'cicsa_assignation_id')
            ->with([
                'cicsa_assignation' => function ($query) {
                    $query->select('id', 'project_name');
                }
            ])
            ->where('operation_date', $od)
            ->whereRaw("RIGHT(operation_number, 6) = ?", [$on])
            ->get();
        $peData->transform(function ($item) {
            $item->cicsa_assignation->setAppends([]);
            $item->setAppends([]);
            return $item;
        });
        
        $spData = PayrollDetailExpense::select('id', 'type', 'payroll_detail_id')
            ->with('payroll_detail')
            ->where('operation_date', $od)
            ->whereRaw("RIGHT(operation_number, 6) = ?", [$on])
            ->get();
        // TO DO xd
        // $spData->transform(function ($item) {
        //     $item->cicsa_assignation->setAppends([]);
        //     $item->setAppends([]);
        //     return $item;
        // });

        return response()->json([
            'acData' => $acData,
            'scData' => $scData,
            'peData' => $peData,
            'spData' => $spData,
        ]);
    }

    public function searchStatementsCosts($as_id)
    {
        $acData = AdditionalCost::select('id', 'expense_type', 'zone', 'amount', 'project_id', 'account_statement_id')
            ->with([
                'project' => function ($query) {
                    $query->select('id', 'preproject_id');
                }
            ])
            ->where('account_statement_id', $as_id)
            ->get();
        $acData->transform(function ($item) {
            $item->project->setAppends(['name']);
            $item->setAppends([]);
            return $item;
        });

        $scData = StaticCost::select('id', 'expense_type', 'zone', 'amount', 'project_id', 'account_statement_id')
            ->with([
                'project' => function ($query) {
                    $query->select('id', 'preproject_id');
                }
            ])
            ->where('account_statement_id', $as_id)
            ->get();
        $scData->transform(function ($item) {
            $item->project->setAppends(['name']);
            $item->setAppends([]);
            return $item;
        });

        $peData = PextProjectExpense::select('id', 'expense_type', 'zone', 'amount', 'cicsa_assignation_id', 'account_statement_id')
            ->with([
                'cicsa_assignation' => function ($query) {
                    $query->select('id', 'project_name');
                }
            ])
            ->where('account_statement_id', $as_id)
            ->get();
        $peData->transform(function ($item) {
            $item->cicsa_assignation->setAppends([]);
            $item->setAppends([]);
            return $item;
        });

        $spData = PayrollDetailExpense::select('id', 'type', 'payroll_detail_id', 'account_statement_id')
            ->with('payroll_detail')
            ->where('account_statement_id', $as_id)
            ->get();

        return response()->json([
            'acData' => $acData,
            'scData' => $scData,
            'peData' => $peData,
            'spData' => $spData,
        ]);
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


    private function getAccountVariables($month = null, $all = false)
    {
        if ($month) {
            $currentMonth = Carbon::parse($month)->month;
            $currentYear = Carbon::parse($month)->year;
        } else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
        }
        $previousBalance = $this->previousBalance($month, $all);
        $currentBalance = $previousBalance;
        $totalCharge = 0;
        $totalPayment = 0;
        $balanceMedia = 0;
        $accountStatements = AccountStatement::select(
            'id',
            'operation_date',
            'operation_number',
            'description',
            'charge',
            'payment',
        );
        $accountStatements = $all ? $accountStatements : $accountStatements
                ->whereMonth('operation_date', $currentMonth)
                ->whereYear('operation_date', $currentYear);
        $accountStatements = $accountStatements->orderBy('operation_date', 'asc')->get();
        $accountStatements->transform(function ($item) {
            $item->setAppends(['state']);
            return $item;
        });
        $accountStatements = $accountStatements->map(function ($statement) use (&$currentBalance, &$totalCharge, &$totalPayment, &$balanceMedia) {
                $totalCharge += $statement->charge;
                $totalPayment += $statement->payment;
                $currentBalance += $statement->payment - $statement->charge;
                $statement->balance = $currentBalance;
                $balanceMedia += $statement->balance;
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
        ];
    }
}
