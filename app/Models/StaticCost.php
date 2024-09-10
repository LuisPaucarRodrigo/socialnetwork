<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticCost extends Model
{
    use HasFactory;

    protected $table = 'static_costs';
    protected $fillable = [
        'expense_type',
        'ruc',
        'type_doc',
        'zone',
        'doc_number',
        'doc_date',
        'description',
        'amount',
        'project_id',
        'provider_id',
        'igv',
        'photo'
    ];

    protected $appends = [
        'real_amount'
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function provider () {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function getRealAmountAttribute() {
        return $this->amount/(1+$this->igv/100);
    }
}
