<?php

namespace App\Services\Huawei;

use App\Constants\HuaweiConstants;
use App\Constants\PintConstants;
use Illuminate\Http\Request;

class CostService
{
    private static array $data;

    public function __construct()
    {
        self::$data = [
            'employees' => HuaweiConstants::getEmployees(),
            'static_expense_types' => HuaweiConstants::getStaticExpenseTypes(),
            'variable_expense_types' => HuaweiConstants::getVariableExpenseTypes(),
            'cdp_types' => HuaweiConstants::getCDPTypes(),
        ];
    }
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
            $query->whereNull('expense_date');
        }
        if ($request->docStartDate) {
            $query->where('expense_date', '>=', $request->docStartDate);
        }
        if ($request->docEndDate) {
            $query->where('expense_date', '<=', $request->docEndDate);
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
        
        $totalExpenseTypes = count(self::$data['static_expense_types']) + count(self::$data['variable_expense_types']);
        if (count($request->selectedExpenseTypes) < $totalExpenseTypes) {
            $query->whereIn('expense_type', $request->selectedExpenseTypes);
        }

        if (count($request->selectedCDPTypes) < count($data['cdp_types'])) {
            $query->whereIn('cdp_type', $request->selectedCDPTypes);
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
