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
    public function index () {
        return Inertia::render('Finance/AccountStatement/AccountStatement');
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

        return response()->json(['acData'=>$acData, 'scData'=>$scData]);
    }


    public function store (AccountStatementRequest $request, $as_id = null) {
        $data = $request->validated();
        // $
        // if ($as_id) {
        //     $rs = 
        //     AccountStatement::
        // } else {
        //     $rs = AccountStatement::create($data);
        // }
        // $id
        
        // $id AccountStatement::
        return Inertia::render('Finance/AccountStatement/AccountStatement');
    }
}
