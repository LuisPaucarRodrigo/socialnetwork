<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuaweiRefund extends Model
{
    use HasFactory;

    protected $table = 'huawei_refunds';
    //buena paucar

    protected $fillable = [
        'huawei_entry_detail_id',
        'quantity',
        'observation'
    ];

    public function huawei_entry_detail ()
    {
        return $this->belongsTo(HuaweiEntryDetail::class, 'huawei_entry_detail_id');
    }
}
