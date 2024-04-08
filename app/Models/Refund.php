<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_entry_liquidation_id',
        'quantify',
        'warehouse_id',
        'state'
    ];

    public function project_entry_liquidation()
    {
        return $this->belongsTo(ProjectEntryLiquidation::class, 'project_entry_liquidation_id');
    }
}
