<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStatement\AccountStatementRequest;
use App\Models\AccountStatement;
use App\Models\AdditionalCost;
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


    public function searchStatements(Request $request)
    {
        
        return response()->json([]);
    }


    public function store(AccountStatementRequest $request, $as_id = null)
    {
        $data = $request->validated();
        $scIds = $data["scData"];
        $acIds = $data["acData"];
        $rg = AccountStatement::updateOrCreate(['id' => $as_id], $data);
        $this->syncOneToMany($rg, 'AdditionalCost', $acIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'StaticCost', $scIds, 'account_statement_id');

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
