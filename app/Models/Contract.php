<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_line',
        'state_travel_expenses',
        'type_contract',
        'amount_travel_expenses',

        'basic_salary',
        'life_ley',
        'discount_remuneration',
        'discount_sctr',
        'state',
        'days_taken',
        'hire_date',
        'fired_date',
        'employee_id',
        'pension_type'
    ];

    //RELATIONS

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    //CALCULATED


    

    public function hideAllAppends()
    {
        // Obtén todos los atributos 'appends'
        $appends = $this->getAppends();
        // Oculta todos los atributos 'appends'
        return $this->makeHidden($appends);
    }
}
