<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectEarning extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_earnings';

    protected $fillable = [
        'code',
        'description',
        'huawei_pa_id',
        'unit_price',
        'unit',
        'quantity',
        'observation',
        'evidence',
        'goal',
    ];

    protected $appends = [
        'amount'
    ];

    public function huawei_assignation ()
    {
        return $this->belongsTo(HuaweiProjectAssignation::class, 'huawei_pa_id');
    }

    public function getAmountAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
