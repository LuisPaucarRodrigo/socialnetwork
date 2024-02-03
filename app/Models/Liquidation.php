<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    use HasFactory;
    protected $table = 'liquidations';
    protected $fillable = [
        'output_project_product_id',
        'liquidated_quantity',
        'refund_quantity',
        'observations'
    ];

    protected $appends = ['product'];

    public function output_project_product()
    {
        return $this->belongsTo(OutputProjectProduct::class, 'output_project_product_id');
    }

    public function getProjectProductAttribute()
    {
        return $this->output_project_product->project_product;
    }

    public function getProductAttribute()
    {
        return $this->project_product->product;
    }
    public function getProjectAttribute()
    {
        return $this->project_product->project;
    }
}
