<?php

namespace App\Http\Controllers\Finance;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;
use App\Models\AccountingAccount;


class DepositController extends Controller
{
    

    public function deposits_index(){
        return Inertia::render("Finance/Deposits/Deposits", [
            "deposits" => Deposit::with('accounting_account')
                ->whereYear('operation_date', now()->year)
                ->whereMonth('operation_date', now()->month)
                ->orderBy('operation_date', 'desc')
                ->orderBy('id', 'desc')
                ->get(),
            'accounts'=>AccountingAccount::all()
        ]);
    }


    public function deposits_store(Request $request, $deposit_id=null) {
        $data = $request->validate([
            'operation_date'=>'required',
            'operation_code'=>'nullable|unique:deposits,operation_code',
            'operation_description'=>'nullable',
            'denomination'=>'required',
            'observation'=>'nullable',
            'comission'=>'nullable',
            'total_type'=>'required',
            'total'=>'required',
            'accounting_account_id'=>'required',
        ]);
        $deposit_id ? Deposit::find($deposit_id)->update($data)
                    : Deposit::create($data);
        return redirect()->back();
    }
    
    public function generateSummary(Request $request)
    {
        $summary_data = $request->validate([
            'code' => 'nullable',
            'start_date' => 'required',
            'end_date' => 'required',
            'columns' => 'required|array',
        ]);

        $startDate = $summary_data['start_date'];
        $endDate = $summary_data['end_date'];

        $query = Deposit::whereBetween('operation_date', [$startDate, $endDate]);

        if ($summary_data['code'] !== "") {
            $query->where('accounting_account_id', $summary_data['code']);
        }

        $selectedColumns = collect($summary_data['columns'])->map(function ($columnName) {
            switch ($columnName) {
                case 'Fecha de Operación':
                    return 'operation_date';
                case 'Cod. Operación':
                    return 'operation_code';
                case 'Descripción de la operación':
                    return 'operation_description';
                case 'Código':
                    return 'accounting_account_id';
                case 'Denominación':
                    return 'denomination';
                case 'Observación':
                    return 'observation';
                case 'Comisión':
                    return 'comission';
                default:
                    return null;
            }
        })->filter()->toArray();
    
        // Seleccionar solo las columnas mapeadas
        $query->select($selectedColumns);

        $deposits = $query->get();

        return Inertia::render('Finance/Deposits/Summary', [
            'deposits' => $deposits->load('accounting_account'),
            'columns' => $summary_data['columns']
        ]);
    }


}
