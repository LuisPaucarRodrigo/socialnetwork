<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaChargeArea extends Model
{
    use HasFactory;

    protected $table = 'cicsa_charge_areas';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'payment_date',
        'deposit_date',
        'amount',
        'user_name',
        'user_id',
        'cicsa_assignation_id'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_assignation ()
    {
        return $this->belongsTo(CicsaAssignation::class, 'cicsa_assignation_id');
    }
}
