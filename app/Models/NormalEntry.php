<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_id',
        'purchase_product_id',
        'referral_guide',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }
}
