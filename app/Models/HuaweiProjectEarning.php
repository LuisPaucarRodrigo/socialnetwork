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
        'huawei_project_id',
        'unit_price',
        'quantity',
        'state'
    ];

    protected $appends = [
        'amount'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }

    public function getAmountAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
