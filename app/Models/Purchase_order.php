<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_order extends Model
{
    use HasFactory;
    protected $fillable = [
        'state',
        'purchase_quote_id', 
        'code',
        'facture_doc',
        'facture_date',
        'facture_number',
        'remission_guide_doc',
        'remission_guide_date',
        'remission_guide_number',
    ];

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }
}
