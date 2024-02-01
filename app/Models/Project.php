<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'code',
        'start_date',
        'end_date',
        'priority',
        'description',
        'initial_budget'
    ];

    protected $appends = ['remaining_budget','materials_costs'];

    public function employees()
    {
        return $this->belongsToMany(Employee::class,'project_employee')->withPivot('charge', 'id');
    }

    public function tasks(){
        return $this->hasMany(Tasks::class);
    }

    public function purchasing_request(){
        return $this->hasMany(Purchasing_request::class);
    }

    public function resources(){
        return $this->belongsToMany(Resource::class, 'project_resource')->withPivot('id','quantity', 'observation');
    }

    public function resource_historials(){
        return $this->hasMany(ResourceHistorial::class, 'project_id');
    }

    public function network_equipments(){
        return $this->belongsToMany(NetworkEquipment::class, 'project_network_equipment')->withPivot('id','observation');
    }

    public function components_or_materials(){
        return $this->belongsToMany(ComponentOrMaterial::class, 'project_componentormaterial')->withPivot('id','quantity','observation');
    }
    
    public function getMaterialsCostsAttribute()
    {
        return $this->components_or_materials()->get()->sum(function ($component) {
            return $component->pivot->quantity * $component->price;
        });
    }

    public function budget_updates(){
        return $this->hasMany(BudgetUpdate::class);
    }
    
    public function getRemainingBudgetAttribute()
    {
        $lastUpdate = $this->budget_updates()->latest()->first(); // Obtén la última actualización del presupuesto

        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;

        $totalExpenses = $this->purchasing_request()
            ->with([
                'purchase_quotes' => function ($query) {
                    $query->whereHas('purchase_order', function ($subQuery) {
                        $subQuery->where('state', 'Completada');
                    })->with('purchase_order');
                }
            ])
            ->where([
                ['state', 'Aceptado'],
            ])
            ->whereHas('purchase_quotes.purchase_order', function ($query) {
                $query->where('state', 'Completada');
            })
            ->get()
            ->sum(function ($expense) {
                return $expense->purchase_quotes[0]['amount'] ?? 0;
            });

        return $currentBudget - $totalExpenses - $this->materials_costs;
    }
}
