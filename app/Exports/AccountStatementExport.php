<?php

namespace App\Exports;


use App\Models\AccountStatement;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\AdditionalCost;


class AccountStatementExport implements FromView
{
    protected $values;

    public function __construct($values)
    {
        $this->values = $values;
    }


    public function view():View
    {
        $values = $this->values;
        $query = AccountStatement::query();
        if ($values->monthInput) {
            [$year, $month] = explode('-', $values->monthInput); 
            $query->where(function ($q) use ($year, $month) {
                $q->whereYear('operation_date', $year)
                ->whereMonth('operation_date', $month);
            });
        }
        if($values->yearInput){
            $query->whereYear('operation_date', $values->yearInput);
        }
        if ($values->search) {
            $searchTerms = $values->search;
            $query->where(function ($q) use ($searchTerms) {
                $q->where('operation_number', 'like', "%$searchTerms%")
                ->orWhere('description', 'like', "%$searchTerms%")
                ->orWhere('charge', 'like', "%$searchTerms%")
                ->orWhere('payment', 'like', "%$searchTerms%");
            });
        }
        if (filter_var($values->opNoDate, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('operation_date');
        }
        if ($values->opStartDate) {
            $query->where('operation_date', '>=', $values->opStartDate);
        }
        if ($values->opEndDate) {
            $query->where('operation_date', '<=', $values->opEndDate);
        }

        $result = $query->get();
        $result->transform(function ($item) {
            $item->setAppends(['state']);
            return $item;
        });

        if (count($values->stateOptions) < 5) {
            $result = $result->filter(function ($item) use ($values) {
                return in_array($item->state, $values->stateOptions);
            })->values()->all();
        }

        return view('Export/AccountStatementExport', [
            'data' => $result
        ]);
    }
}
