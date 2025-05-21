<?php

namespace App\Services\Huawei;

use App\Constants\HuaweiConstants;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CostService
{

    public function filter(Request $request, $query)
    {
        if ($request->search) {
            $searchTerms = $request->input('search');
            $query->where(function ($q) use ($searchTerms) {
                $q->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_number', 'like', "%$searchTerms%")
                ->orWhere('op_number', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }

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

        if (count($request->selectedZones) < PintConstants::countAcZones()) {
            $query->whereIn('zone', $request->selectedZones);
        }

        if (count($request->selectedExpenseTypes) < PintConstants::countAcExpenseTypes()) {
            $query->whereIn('expense_type', $request->selectedExpenseTypes);
        }

        if (count($request->selectedDocTypes) < PintConstants::countAcDocTypes()) {
            $query->whereIn('type_doc', $request->selectedDocTypes);
        }

        $result = $query->orderBy('doc_date')->get();

        $result->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });

        if ($request->state !== false && count($request->selectedStateTypes) < count(PintConstants::acStatesPenAccep())) {
            $result = $result->filter(function ($item) use ($request) {
                return in_array($item->real_state, $request->selectedStateTypes);
            })->values()->all();
        }

        return $result;
    }


}
