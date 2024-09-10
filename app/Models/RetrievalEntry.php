<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetrievalEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_id',
        'project_entry_liquidation_id',
        'state',
        'purchase_product_id'
    ];


    //Rlations
    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    public function project_entry_liquidation()
    {
        return $this->belongsTo(ProjectEntryLiquidation::class, 'project_entry_liquidation_id');
    }

    public function purchase_product()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }
}
