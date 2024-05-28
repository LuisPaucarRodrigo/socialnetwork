<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SNSot extends Model
{
    use HasFactory;
    protected $table = 'sn_sots';
    protected $fillable = [
        'user_owner_id',
        'user_assignee_id',
        'name',
        'description',
        'assigned_date',
    ];

    public function user_owner () {
        return $this->belongsTo(User::class, 'user_owner_id');
    }

    public function user_assignee () {
        return $this->belongsTo(User::class, 'user_assignee_id');
    }

    public function sot_operation () {
        return $this->hasOne(SNSotOperation::class, 'sot_id');
    }
    public function sot_liquidation () {
        return $this->hasOne(SNSotLiquidation::class, 'sot_id');
    }
    public function sot_control () {
        return $this->hasOne(SNSotControl::class);
    }

    public function sot_payment () {
        return $this->hasOne(SNSotPayment::class);

    }
}
