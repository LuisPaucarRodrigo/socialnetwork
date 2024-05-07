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
        'preproject_quote',
        'total_percentage_tasks',
        'total_percentage_tasks_completed',
        'remaining_budget',
        'preproject_quote_no_margin',
        'total_employee_costs',
        'name',
        'code',
        'start_date',
        'end_date',
        'total_products_cost',
        'total_services_cost',
        'current_budget',
        'total_sum_task'
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
        if($this->initial_budget === 0.00) {
            return 0;
        }
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        $additionalCosts = $this->additionalCosts->sum('amount');
        return $currentBudget
            - $this->getTotalProductsCostAttribute()
            - $this->getTotalServicesCostAttribute()
            - $additionalCosts;
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

    // --------------------------------  Services Costs ---------------------------------//

    public function getTotalServicesCostAttribute()
    {
        return $this->preproject()->first()->total_services_cost;
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

    public function project_entries()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function project_image()
    {
        return $this->hasMany(Projectimage::class);
    }
}
