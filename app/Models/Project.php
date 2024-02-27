<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'priority',
        'description',
        'status',
        'preproject_id'
    ];

    protected $appends = ['total_assigned_resources_costs',  'total_used_resources_costs','remaining_budget', 'total_assigned_product_costs','total_refund_product_costs_no_different_price', 'total_product_costs_with_liquidation','initial_budget', 'total_resources_costs_with_liquidation', 'total_employee_costs','name','code', 'start_date', 'end_date',];


    public function getInitialBudgetAttribute(){
        $preproject = $this->preproject()->first();
        $quoteItems = $preproject ? $preproject->quote->items : [];
        $initialBudget = 0;
        foreach ($quoteItems as $item) {
            $initialBudget += $item->unit_price * $item->quantity;
        }
        $totalInitialBudget = $initialBudget + ($initialBudget * 18/100);
        return $totalInitialBudget;
    }

    public function getNameAttribute() {
        return $this->preproject()->first()?->quote->name;
    }

    
    public function getCodeAttribute() {
        return $this->preproject()->first()?->code;
    }
    
    public function getStartDateAttribute() {
        $startDate = Carbon::parse($this->preproject()->first()?->quote->date);
        return $startDate->format('d/m/Y');
    }
    
    public function getEndDateAttribute() {
        $startDate = Carbon::parse($this->preproject()->first()?->quote->date);
        $daysFromQuote = optional($this->preproject()->first()->quote)->deliverable_time;
        return $startDate->addDays($daysFromQuote)->format('d/m/Y');
    }


    public function employees(){
        return $this->belongsToMany(Employee::class, 'project_employee')->withPivot('charge', 'id');
    }

    public function projectProducts(){
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

    public function resources(){
        return $this->belongsToMany(Resource::class, 'project_resource')->withPivot('id', 'quantity', 'observation');
    }
    
    public function project_resources(){
        return $this->hasMany(ProjectResource::class,'resource_id');
    }

    public function resource_historials()
    {
        return $this->hasMany(ResourceHistorial::class, 'project_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'project_product');
    }


    public function budget_updates()
    {
        return $this->hasMany(BudgetUpdate::class);
    }

    public function getRemainingBudgetAttribute() {
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        $additionalCosts = $this->additionalCosts->sum('amount');
        return $currentBudget 
            - $this->getTotalResourcesCostsWithLiquidationAttribute()
            - $this->getTotalProductCostsWithLiquidationAttribute()
            - $this->getTotalEmployeeCostsAttribute()
            - $additionalCosts ;
    }

    // --------------------------------  Resources Costs ---------------------------------//

    public function getTotalAssignedResourcesCostsAttribute(){
        return $this->project_resources()->get()->sum(function($item){
            return $item->quantity * $item->unit_price;
        });
    }


    public function getTotalUsedResourcesCostsAttribute(){
        return $this->project_resources()->get()->sum(function($item){
            $used_quantity = 0;
            if ($item->project_resource_liquidate){
                $used_quantity = $item->project_resource_liquidate->liquidated_quantity - 
                $item->project_resource_liquidate->refund_quantity;
            }
            return $item->project_resource_liquidate && $used_quantity > 0 ? 
                    ($used_quantity)* $item->resource->unit_price -
                    ($used_quantity) * $item->unit_price 
                    :0;
        });
    }


    public function getTotalResourcesCostsWithLiquidationAttribute(){
        return $this->getTotalAssignedResourcesCostsAttribute() + $this->getTotalUsedResourcesCostsAttribute();
    }


    // --------------------------------  Product Costs ---------------------------------//

    public function getTotalAssignedProductCostsAttribute(){
        $totalCost = $this->projectProducts()->with('product')->get()->sum(function($item) {
            if ($item->product->has_different_price) {
                return $item->unit_price * $item->quantity;
            } else {
                return $item->product->unit_price * $item->quantity;
            }
        });
        return $totalCost; 
    }


    public function preproject() {
        return $this->belongsTo(Preproject::class, 'preproject_id');
    }
    

    public function getTotalRefundProductCostsNoDifferentPriceAttribute(){
        $totalCost = $this->projectProducts()->with('product')->get()->sum(function($item) {
            if (!$item->product->has_different_price) {
                return $item->total_refund_quantity * $item->product->unit_price;
            }
        });
        return $totalCost; 
    }


    public function getTotalUsedProductCostsDifferentPriceAttribute(){
        $totalCost = $this->projectProducts()->with('product')->get()->sum(function($item) {
            if ($item->product->has_different_price) {
                return ($item->total_used_quantity * $item->product->unit_price) - ($item->total_used_quantity * $item->unit_price);
            }
        });
        return $totalCost; 
    }

    
    public function getTotalProductCostsWithLiquidationAttribute(){
        return $this->getTotalAssignedProductCostsAttribute() - $this->getTotalRefundProductCostsNoDifferentPriceAttribute() +
                $this->getTotalUsedProductCostsDifferentPriceAttribute(); 
    }

    // --------------------------------  Employee Costs ---------------------------------//


    public function getTotalEmployeeCostsAttribute(){   

        $days = optional($this->preproject()->first()->quote)->deliverable_time;
        return $this->employees()->get()->sum(function($item) use ($days){
            return $item->getSalaryPerDayAttribute() * $days;
        });
    }

}
