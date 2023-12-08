<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_order extends Model
{
    use HasFactory;
    protected $fillable = ['project','title','product_description','amount','quote_deadline','purchase_image','response','provider_id'];
   
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
