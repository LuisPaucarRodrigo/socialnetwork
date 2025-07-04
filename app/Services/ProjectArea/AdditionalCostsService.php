<?php

namespace App\Services\ProjectArea;

use App\Constants\PintConstants;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdditionalCostsService
{

    public function filter(Request $request, $query)
    {
        if ($request->search) {
            $searchTerms = $request->input('search');
            $query->where(function ($q) use ($searchTerms) {
                $q->where('ruc', 'like', "%$searchTerms%")
                ->orWhere('doc_number', 'like', "%$searchTerms%")
                ->orWhere('operation_number', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('amount', 'like', "%$searchTerms%");
            });
        }
        
        if ($request->state === false) {
            $query->where('is_accepted', 0);
        } else {
            $query->where(function ($q) {
                $q->where('is_accepted', 1)
                ->orWhereNull('is_accepted');
            });
        }
        if (filter_var($request->docNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('doc_date');
        }
        if ($request->docStartDate) {
            $query->where('doc_date', '>=', $request->docStartDate);
        }
        if ($request->docEndDate) {
            $query->where('doc_date', '<=', $request->docEndDate);
        }

        if (filter_var($request->opNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('operation_date');
        }
        if ($request->opStartDate) {
            $query->where('operation_date', '>=', $request->opStartDate);
        }
        if ($request->opEndDate) {
            $query->where('operation_date', '<=', $request->opEndDate);
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
            $item->setAppends(['real_amount', 'real_state', 'admin_state']);
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
