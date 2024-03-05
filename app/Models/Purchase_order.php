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
        'facture_doc',
        'facture_date',
        'facture_number',
        'remission_guide_doc',
        'remission_guide_date',
        'remission_guide_number',
    ];

    public $appends = [
        'code'
    ];

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }

    public function getCodeAttribute()
    {
        if ($this->exists) {
            return 'PR' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        } else {
            return 'TMP' . now()->format('ymdHis');
        }
    }
}
