<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\HuaweiMonthlyExpense;

class HuaweiMonthlyExport implements FromView
{
    protected $mode;
    protected $huawei_project_id;

    public function __construct($mode = null, $huawei_project_id = null)
    {
        $this->mode = $mode;
        $this->huawei_project_id = $huawei_project_id;
    }

    public function view(): View
    {
        $expenses = HuaweiMonthlyExpense::with('huawei_project.huawei_site')
            ->where('is_accepted', 1);

        if ($this->huawei_project_id) {
            $expenses
            ->whereNotNull('huawei_project_id')
            ->where('huawei_project_id', $this->huawei_project_id);
        }

        $expenses = $expenses->get()->filter(function ($expense) {
            if ($this->mode === '1') {
                return $expense->type === 'Fijo';
            } else {
                return $expense->type === 'Variable';
            }
        });

        return view('Export/HuaweiMonthlyExport', compact('expenses'));
    }
}
