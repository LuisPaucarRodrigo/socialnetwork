<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaPurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'cicsa_purchase_orders';

    protected $fillable = [
        'oc_date',
        'oc_number',
        'master_format',
        'item3456',
        'budget',
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
