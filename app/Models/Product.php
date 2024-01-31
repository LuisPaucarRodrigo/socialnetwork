<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'warehouse_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    
    public function productHeaders()
    {
        return $this->hasMany(ProductsHeader::class, 'product_id');
    }

    public function headers()
    {
        return $this->belongsToMany(Header::class, 'products_headers', 'product_id', 'header_id')->withTimestamps();
    }
}
