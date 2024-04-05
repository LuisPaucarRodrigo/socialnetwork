<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_id',
        'type',
        'quantify',
        'unitary_price',
        'entry_date',
        'observations',
    ];


    //Relations
    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'inventory_id');
    }

    public function project_entry()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function purchase_entry()
    {
        return $this->hasOne(PurchasesEntry::class);
    }

    public function normal_entry()
    {
        return $this->hasOne(NormalEntry::class);
    }

    public function retrieval_entry()
    {
        return $this->hasOne(RetrievalEntry::class);
    }
}
