<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiProjectRealEarning extends Model
{
    use HasFactory;

    protected $table = 'huawei_project_real_earnings';

    protected $fillable = [
        'invoice_number',
        'amount',
        'invoice_date',
        'deposit_date',
        'huawei_project_id'
    ];

    public function huawei_project ()
    {
        return $this->belongsTo(HuaweiProject::class, 'huawei_project_id');
    }
}
