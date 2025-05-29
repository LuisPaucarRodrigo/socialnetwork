<?php

namespace App\Services\Huawei;

use App\Constants\HuaweiConstants;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CostService
{

    public function filter(Request $request, $query, $mode)
    {
        if ($request->search) {
            $searchTerm = $request->input('search');
            $query->whereRaw('LOWER(expense_type) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(zone) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(employee) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(cdp_type) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(doc_number) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(op_number) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(ruc) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('LOWER(ec_op_number) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereHas('huawei_project', function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(assigned_diu) LIKE ?', ["%{$searchTerm}%"]);
                });
        };


        if ($request->selectedStates === false) {
            $query->where('is_accepted', 0);
        } else {
            $query->where(function ($q) {
                $q->where('is_accepted', 1)
                    ->orWhereNull('is_accepted');
            });
        }
        if (filter_var($request->exNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('expense_date');
        }
        if ($request->exStartDate) {
            $query->where('expense_date', '>=', $request->exStartDate);
        }
        if ($request->exEndDate) {
            $query->where('expense_date', '<=', $request->exEndDate);
        }

        if (filter_var($request->opNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('ec_expense_date');
        }
        if ($request->opStartDate) {
            $query->where('ec_expense_date', '>=', $request->opStartDate);
        }
        if ($request->opEndDate) {
            $query->where('ec_expense_date', '<=', $request->opEndDate);
        }

        if (count($request->selectedZones) < count(HuaweiConstants::getZones())) {
            $query->whereIn('zone', $request->selectedZones);
        }

        if ($mode){
            if (count($request->selectedExpenseTypes) < count(HuaweiConstants::getVariableExpenseTypes())) {
                $query->whereIn('expense_type', $request->selectedExpenseTypes);
            }
        }else{
            if (count($request->selectedExpenseTypes) < count(HuaweiConstants::getStaticExpenseTypes())) {
                $query->whereIn('expense_type', $request->selectedExpenseTypes);
            }
        }

        if (count($request->selectedCDPTypes) < count(HuaweiConstants::getCDPTypes())) {
            $query->whereIn('cdp_type', $request->selectedCDPTypes);
        }

        $result = $query->orderBy('expense_date')->get();

        $result->transform(function ($item) {
            $item->setAppends(['real_state']);
            return $item;
        });

        if (count($request->selectedStates) < 4) {
            $selectedStates = $request->selectedStates;

            $filteredStates = array_filter($selectedStates, function ($state) {
                return in_array($state, ["Aceptado", "Rechazado", "Pendiente", "Aceptado-Validado"]);
            });

            $result = $result->filter(function ($expense) use ($filteredStates) {
                return in_array($expense->real_state, $filteredStates);
            });
        }

        return $result;
    }
}
