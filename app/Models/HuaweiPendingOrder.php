<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiPendingOrder extends Model
{
    use HasFactory;

    protected $table = 'huawei_pending_orders';

    protected $fillable = [
        'order_number',
        'order_date',
        'observation',
        'status'
    ];

    public function huawei_entry_details ()
    {
        return $this->hasMany(HuaweiEntryDetail::class, 'huawei_order_id');
    }
}
