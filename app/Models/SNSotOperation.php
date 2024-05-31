<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SNSotOperation extends Model
{
    use HasFactory;
    protected $table = 'sn_sot_operation';
    protected $fillable = [
        'sot_id',
        'i_state',
        'additionals',
        'photo_report',
        'ic_date',
    ];

    public function sot() {
        return $this->belongsTo(SNSot::class, 'sot_id');
    }
}
