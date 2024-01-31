<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'header_id',
        'content'
    ];

    public function header()
    {
        return $this->belongsTo(Header::class, 'header_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}
