<?php

namespace App\Services\ProjectArea;

use App\Constants\PintConstants;
use Illuminate\Http\Request;

class StaticCostsService
{
    public function filter(Request $request, $query)
    {
        if ($request->search) {
            $searchTerms = $request->input('search');
            $query = $query->where(function ($q) use ($searchTerms) {
                $q->where('ruc', 'like', "%$searchTerms%")
                    ->orWhere('doc_number', 'like', "%$searchTerms%")
                    ->orWhere('operation_number', 'like', "%$searchTerms%")
                    ->orWhere('description', 'like', "%$searchTerms%")
                    ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }
        if (filter_var($request->docNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->where('doc_date', null);
        }
        if ($request->docStartDate) {
            $query->where('doc_date', '>=', $request->docStartDate);
        }
        if ($request->docEndDate) {
            $query->where('doc_date', '<=', $request->docEndDate);
        }
        if (filter_var($request->opNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->where('operation_date', null);
        }
        if ($request->opStartDate) {
            $query->where('operation_date', '>=', $request->opStartDate);
        }
        if ($request->opEndDate) {
            $query->where('operation_date', '<=', $request->opEndDate);
        }
        if (count($request->selectedZones) < PintConstants::countScZones()) {
            $query = $query->whereIn('zone', $request->selectedZones);
        }
        if (count($request->selectedExpenseTypes) < PintConstants::countScExpenseTypes()) {
            $query = $query->whereIn('expense_type', $request->selectedExpenseTypes);
        }
        if (count($request->selectedDocTypes) < PintConstants::countScDocTypes()) {
            $query = $query->whereIn('type_doc', $request->selectedDocTypes);
        }
        $result = $query->orderBy('doc_date')->get();
        $result->transform(function ($item) {
            $item->project->setAppends([]);
            $item->setAppends(['real_amount', 'real_state']);
            return $item;
        });
        if (count($request->selectedStateTypes) < PintConstants::countScStatesTypes()) {
            $result = $result->filter(function ($item) use ($request) {
                return in_array($item->real_state, $request->selectedStateTypes);
            })->values()->all();
        }


        return $result;
    }

}