<?php

namespace App\Models;

use App\Constants\PintConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'priority',
        'description',
        'status',
        'preproject_id',
        'cost_center_id',
        'cost_line_id',
        'initial_budget',
        'position',
        'year',
        'is_accepted',
    ];

    protected $appends = [
        'preproject_quote',
        'total_percentage_tasks',
        'total_percentage_tasks_completed',
        'remaining_budget',
        'preproject_quote_no_margin',
        'total_employee_costs',
        'name',
        'code',
        'days',
        'start_date',
        'end_date',
        'total_products_cost',
        'total_services_cost',
        'current_budget',
        'total_sum_task',
        'is_liquidable',
        'total_products_cost_claro_cicsa',
        'serialized_code',
        'static_costs_total',
        'additional_costs_total',
    ];

    // CALCULATED

    public function getTotalSumTaskAttribute()
    {
        return $this->tasks()->get()->sum('percentage');
    }

    public function getPreprojectQuoteAttribute()
    {
        return $this->preproject?->quote?->total_amount;
    }

    public function getPreprojectQuoteNoMarginAttribute()
    {
        return $this->preproject?->quote->total_amount_no_margin;
    }

    public function getNameAttribute()
    {
        return $this->preproject()->first()?->quote?->name;
    }

    public function getCodeAttribute()
    {
        return $this->preproject()->first()?->code;
    }

    public function getStartDateAttribute()
    {
        $startDate = Carbon::parse($this->preproject()->first()?->quote->date);
        return $startDate->format('d/m/Y');
    }

    public function getEndDateAttribute()
    {
        $startDate = Carbon::parse($this->preproject()->first()?->quote->date);
        $daysFromQuote = optional($this->preproject()->first()?->quote)->deliverable_time;
        return $startDate->addDays($daysFromQuote - 1)->format('d/m/Y');
    }

    public function getTotalPercentageTasksAttribute()
    {
        return $this->tasks()->get()->sum(function ($item) {
            return $item->percentage;
        });
    }

    public function getTotalPercentageTasksCompletedAttribute()
    {
        $completedTasks = $this->tasks()->where('status', 'completado')->get();

        $totalPercentageCompleted = $completedTasks->sum(function ($task) {
            return $task->percentage;
        });

        return $totalPercentageCompleted;
    }

    public function getRemainingBudgetAttribute()
    {
        if ($this->initial_budget === 0.00) {
            return 0.00;
        }
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        $additionalCosts = $this->getAdditionalCostsTotalAttribute();
        $staticCosts = $this->getStaticCostsTotalAttribute();
        $currentBudget = $currentBudget
            - $this->getTotalProductsCostAttribute()
            - $additionalCosts
            - $staticCosts;
        foreach ($this->getTotalEmployeeCostsAttribute() as $value) {
            $currentBudget -= $value['total_payroll'];
            $currentBudget -= $value['essalud'];
        }
        return $currentBudget;
    }

    public function getAdditionalCostsTotalAttribute () {
        return $this->additionalCosts()
        ->where('is_accepted', 1)
        ->whereNotIn('expense_type', PintConstants::acExpensesThatDontCount())
        ->get()
        ->sum('real_amount');
    }
    public function getStaticCostsTotalAttribute () {
        return $this->staticCosts()
        ->whereNotIn('expense_type', PintConstants::scExpensesThatDontCount())
        ->get()
        ->sum('real_amount');
    }


    public function getCurrentBudgetAttribute()
    {
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        return $currentBudget;
    }

    public function getTotalProductsCostAttribute()
    {
        return $this->project_entries()->where('state', true)->get()->sum(function ($item) {
            return $item->total_price;
        });
    }

    public function getTotalProductsCostClaroCicsaAttribute()
    {
        $preproject = $this->preproject()->first();
        if ($preproject?->customer_id !== 1) {
            return 0;
        } else {
            return null;
        }
    }



    public function getIsLiquidableAttribute()
    {
        $project_entries = $this->project_entries()->get();
        foreach ($project_entries as $item) {
            if ($item->liquidation_state === false) {
                return false;
            }
        }
        $preproject = $this->preproject()
            ->with('quote.preproject_quote_services')
            ->first();
        if ($preproject) {
            foreach ($preproject->quote->preproject_quote_services as $item) {
                if ($item->liquidation_state === false) {
                    return false;
                }
            }
        }
        $tasks = $this->tasks()->get();
        foreach ($tasks as $item) {
            if ($item->status !== 'completado') {
                return false;
            }
        }
        return true;
    }


    // --------------------------------  Services Costs ---------------------------------//

    public function getTotalServicesCostAttribute()
    {
        return 0;
        // return $this->preproject()->first()->total_services_cost;
    }

    // --------------------------------  Employee Costs ---------------------------------//


    public function getTotalEmployeeCostsAttribute()
    {
        return [
            [
                'type' => 'Administrativo',
                'total_payroll' => $this->employeeChargeCosts('Administrativo'),
                'essalud' => $this->employeeChargeCosts('Administrativo') * 0.09
            ],
            [
                'type' => 'MOD - Mano de Obra Directa',
                'total_payroll' => $this->employeeChargeCosts('MOD - Mano de Obra Directa'),
                'essalud' => $this->employeeChargeCosts('MOD - Mano de Obra Directa') * 0.09
            ],
            [
                'type' => 'MOI - Mano de Obra Indirecta',
                'total_payroll' => $this->employeeChargeCosts('MOI - Mano de Obra Indirecta'),
                'essalud' => $this->employeeChargeCosts('MOI - Mano de Obra Indirecta') * 0.09
            ],
        ];
    }




    public function employeeChargeCosts($type)
    {
        $days = $this->getDaysAttribute();
        $totalMonthSalary = $this->project_employee()->where('charge', $type)->get()->sum(function ($item) use ($days) {
            return $item->salary_per_day * $days;
        });
        return $totalMonthSalary;
    }


    public function getDaysAttribute()
    {
        // return optional($this->preproject()->first()->quote)->deliverable_time;
        return $this->preproject?->quote?->deliverable_time ?? null;
    }

    public function getSerializedCodeAttribute()
    {
        $prev = '';
        if ($this->cost_line_id == 2) {
            $prev = 'CCIP-PEXT-';
        }
        if ($this->cost_line_id == 1) {
            $prev = 'CCIP-PINT-';
        }
        return $prev . $this->year . '-' . str_pad($this->position, 4, '0', STR_PAD_LEFT);
    }


    //RELATIONS
    public function preproject()
    {
        return $this->belongsTo(Preproject::class, 'preproject_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'project_employee')->withPivot('charge', 'id');
    }

    public function project_employee()
    {
        return $this->hasMany(ProjectEmployee::class, 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }

    public function additionalCosts()
    {
        return $this->hasMany(AdditionalCost::class);
    }

    public function staticCosts()
    {
        return $this->hasMany(StaticCost::class);
    }

    public function purchasing_request()
    {
        return $this->hasMany(Purchasing_request::class);
    }

    public function budget_updates()
    {
        return $this->hasMany(BudgetUpdate::class);
    }

    public function project_entries()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function project_image()
    {
        return $this->hasMany(Projectimage::class);
    }

    public function cicsa_assignation()
    {
        return $this->hasOne(CicsaAssignation::class);
    }

    public function cost_line()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function pext_project_expenses()
    {
        return $this->hasMany(PextProjectExpense::class);
    }

    public function project_quote()
    {
        return $this->hasOne(ProjectQuote::class);
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            $currentYear = date('Y');
            $listCost = [3, 6, 7, 8, 9];
            $lastProject = Project::select('id', 'position', 'cost_center_id')
                ->where('year', $currentYear)
                ->whereIn('cost_center_id', $listCost)
                ->orderBy('position','desc')
                ->first();

            $project->position = $lastProject ? $lastProject->position + 1 : 1;
            $project->year = $currentYear; 
        });
    }
}
