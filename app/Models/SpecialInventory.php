<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialInventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_product_id',
        'warehouse_id',
        'cpe',
        'referral_guide',
        'entry_date',
        'sub_warehouse',
        'quantify',
        'product_serial_number',
        'entry_observations',
    ];

    //Relations
    public function project_entry()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function purchase_product_id()
    {
        return $this->belongsTo(Purchase_product::class, 'purchase_product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
