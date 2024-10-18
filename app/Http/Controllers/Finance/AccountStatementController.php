<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStatement\AccountStatementRequest;
use App\Models\AccountStatement;
use App\Models\AdditionalCost;
use App\Models\StaticCost;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountStatementController extends Controller
{
    public function index()
    {
        $accountStatements = AccountStatement::paginate(40);
        return Inertia::render(
            'Finance/AccountStatement/AccountStatement',
            [
                'accountStatements' => $accountStatements
            ]
        );
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
            ->where('operation_number', $on)
            ->get();
        $acData->transform(function ($item) {
            $item->project->setAppends(['code']);
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
            ->where('operation_number', $on)
            ->get();
        $scData->transform(function ($item) {
            $item->project->setAppends(['code']);
            $item->setAppends([]);
            return $item;
        });

        return response()->json(['acData' => $acData, 'scData' => $scData]);
    }


    public function store(AccountStatementRequest $request, $as_id = null)
    {
        $data = $request->validated();
        $scIds = $data["scData"];
        $acIds = $data["acData"];
        unset($data["scData"]);
        unset($data["acData"]);
        $rg = AccountStatement::updateOrCreate(['id' => $as_id], $data);
        $this->syncOneToMany($rg, 'AdditionalCost', $acIds, 'account_statement_id');
        $this->syncOneToMany($rg, 'StaticCost', $scIds, 'account_statement_id');
        return response()->json($rg, 200);
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
