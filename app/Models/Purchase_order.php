<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_order extends Model
{
    use HasFactory;
    protected $fillable = ['date_issue','state','purchase_quote_id'];

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class , 'purchase_quote_id');
    }
}
