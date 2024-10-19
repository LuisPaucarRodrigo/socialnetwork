<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStatement\AccountStatementRequest;
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
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $accountStatements = AccountStatement::whereMonth('operation_date', $currentMonth)
            ->whereYear('operation_date', $currentYear)
            ->orderBy('operation_date', 'asc')
            ->get();
        return Inertia::render(
            'Finance/AccountStatement/AccountStatement',
            [
                'accountStatements' => $accountStatements
            ]
        );
    }


    public function searchCosts(Request $request){
        $od = $request->operation_date;
        $on = $request->operation_number;

        $acData = AdditionalCost::select('id', 'expense_type','zone','amount', 'project_id')
            ->with(['project' => function ($query) {
                $query->select('id', 'preproject_id');
            }])
            ->where('operation_date', $od)
            ->where('operation_number', $on)
            ->get();
        $acData->transform(function($item){
            $item->project->setAppends(['code']);
            $item->setAppends([]);
            return $item;
        });
        
        $scData = StaticCost::select('id', 'expense_type','zone','amount', 'project_id')
            ->with(['project' => function ($query) {
                $query->select('id', 'preproject_id');
            }])
            ->where('operation_date', $od)
            ->where('operation_number', $on)
            ->get();
        $scData->transform(function($item){
            $item->project->setAppends(['code']);
            $item->setAppends([]);
            return $item;
        });

        $peData = PextProjectExpense::select('id', 'expense_type','zone','amount', 'cicsa_assignation_id')
            ->with(['cicsa_assignation' => function ($query) {
                $query->select('id', 'project_name');
            }])
            ->where('operation_date', $od)
            ->where('operation_number', $on)
            ->get();
        $peData->transform(function($item){
            $item->cicsa_assignation->setAppends([]);
            $item->setAppends([]);
            return $item;
        });

        return response()->json([
            'acData'=>$acData, 
            'scData'=>$scData,
            'peData'=>$peData,
        ]);
    }


    public function store(AccountStatementRequest $request, $as_id = null)
    {
        $data = $request->validated();
        $scIds = $data["scData"];
        $acIds = $data["acData"];
        $peIds = $data["peData"];
        $rg = AccountStatement::updateOrCreate(['id' => $as_id], $data);
        $this->syncOneToMany($rg, 'AdditionalCost', $acIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'StaticCost', $scIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'PextProjectExpense', $peIds, 'account_statement_id');
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $accountStatements = AccountStatement::whereMonth('operation_date', $currentMonth)
            ->whereYear('operation_date', $currentYear)
            ->orderBy('operation_date', 'asc')
            ->get();
        return response()->json([
            'accountStatements' => $accountStatements
        ], 200);
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
}
