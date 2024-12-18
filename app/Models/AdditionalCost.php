<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\PintConstants;

class AdditionalCost extends Model
{
    use HasFactory;
    protected $table = 'additional_costs';
    protected $fillable = [
        'expense_type',
        'ruc',
        'type_doc',
        'zone',
        'operation_number',
        'operation_date',
        'doc_number',
        'doc_date',
        'description',
        'amount',
        'project_id',
        'provider_id',
        'photo',
        'is_accepted',
        'igv',
        'user_id',
        'account_statement_id',

    ];

    protected $casts = [
        'amount' => 'double',
    ];

    protected $appends = [
        'real_amount',
        'real_state'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRealAmountAttribute() {
        return $this->amount/(1+$this->igv/100);
    }

    public function getRealStateAttribute() {
        if ($this->is_accepted === 0) {
            return PintConstants::RECHAZADO;
        } 
        if ($this->is_accepted && $this->account_statement_id) {
            return PintConstants::ACEPTADO_VALIDADO;
        }
        if ($this->is_accepted) {
            return PintConstants::ACEPTADO;
        }
        return PintConstants::PENDIENTE;
    }
}
