<?php

namespace App\Models\ShoppingArea;

use App\Models\CostLine;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentApproval extends Model
{
    use HasFactory;
    protected $fillable = [
        'zone',
        'description',
        'amount',
        'account_number',
        'bank',
        'ruc',
        'beneficiary',
        'document',
        'cost_line_id',
        'provider_id'
    ];

    public function getStateAttribute()
    {
        if ($this->document) {
            return 'Completado';
        }
        return 'Pendiente';
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function cost_line()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }
}
