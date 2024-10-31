<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AccountStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_date',
        'operation_number',
        'description',
        'charge',
        'payment',
    ];

    public function additional_costs () {
        return $this->hasMany(AdditionalCost::class, 'account_statement_id');
    }

    public function static_costs () {
        return $this->hasMany(StaticCost::class, 'account_statement_id');
    }

    public function pext_project_expenses () {
        return $this->hasMany(PextProjectExpense::class, 'account_statement_id');
    }

    public function getStateAttribute () {
        if ($this->payment) {
            return 'No aplica';
        }
        if ($this->charge) {
            $totalAC = $this->additional_costs()->get()->sum('amount');
            $totalSC = $this->static_costs()->get()->sum('amount');
            $totalPE = $this->pext_project_expenses()->get()->sum('amount');
            $total = $totalAC + $totalSC + $totalPE;
            if ($total >= $this->charge){
                return 'Validado';
            } else {
                return 'No validado';
            }
        }
    }

}
