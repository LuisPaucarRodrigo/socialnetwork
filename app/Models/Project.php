<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class  Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'priority',
        'description',
        'status',
        'preproject_id',
        'initial_budget'
    ];

    protected $appends = [
        'total_percentage_tasks',
        'total_percentage_tasks_completed',
        'remaining_budget',
        'total_assigned_product_costs',
        'total_refund_product_costs_no_different_price',
        'total_product_costs_with_liquidation',
        'preproject_quote',
        'preproject_quote_no_margin',
        'total_employee_costs',
        'name',
        'code',
        'start_date',
        'end_date',
    ];

    // CALCULATED
    public function getPreprojectQuoteAttribute()
    {
        return $this->preproject?->quote?->total_amount;
    }

    public function getPreprojectQuoteNoMarginAttribute()
    {
        return $this->preproject->quote->total_amount_no_margin;
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
        $daysFromQuote = optional($this->preproject()->first()->quote)->deliverable_time;
        return $startDate->addDays($daysFromQuote)->format('d/m/Y');
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
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        $additionalCosts = $this->additionalCosts->sum('amount');
        return $currentBudget
            // - $this->getTotalResourcesCostsWithLiquidationAttribute()
            - $this->getTotalProductCostsWithLiquidationAttribute()
            - $this->getTotalEmployeeCostsAttribute()
            - $additionalCosts;
    }

    // --------------------------------  Product Costs ---------------------------------//

    public function getTotalAssignedProductCostsAttribute()
    {
        $totalCost = $this->projectProducts()->with('product')->get()->sum(function ($item) {
            if ($item->product->has_different_price) {
                return $item->unit_price * $item->quantity;
            } else {
                return $item->product->unit_price * $item->quantity;
            }
        });
        return $totalCost;
    }


    public function getTotalRefundProductCostsNoDifferentPriceAttribute()
    {
        $totalCost = $this->projectProducts()->with('product')->get()->sum(function ($item) {
            if (!$item->product->has_different_price) {
                return $item->total_refund_quantity * $item->product->unit_price;
            }
        });
        return $totalCost;
    }


    public function getTotalUsedProductCostsDifferentPriceAttribute()
    {
        $totalCost = $this->projectProducts()->with('product')->get()->sum(function ($item) {
            if ($item->product->has_different_price) {
                return ($item->total_used_quantity * $item->product->unit_price) - ($item->total_used_quantity * $item->unit_price);
            }
        });
        return $totalCost;
    }


    public function getTotalProductCostsWithLiquidationAttribute()
    {
        return $this->getTotalAssignedProductCostsAttribute() - $this->getTotalRefundProductCostsNoDifferentPriceAttribute() +
            $this->getTotalUsedProductCostsDifferentPriceAttribute();
    }

    // --------------------------------  Employee Costs ---------------------------------//


    public function getTotalEmployeeCostsAttribute()
    {

        $days = optional($this->preproject()->first()->quote)->deliverable_time;
        return $this->employees()->get()->sum(function ($item) use ($days) {
            return $item->getSalaryPerDayAttribute() * $days;
        });
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

    public function projectProducts()
    {
        return $this->hasMany(ProjectProduct::class);
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }

    public function additionalCosts()
    {
        return $this->hasMany(AdditionalCost::class);
    }

    public function purchasing_request()
    {
        return $this->hasMany(Purchasing_request::class);
    }

    public function budget_updates()
    {
        return $this->hasMany(BudgetUpdate::class);
    }

}
