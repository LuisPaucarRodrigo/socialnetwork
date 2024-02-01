<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProduct extends Model
{
    use HasFactory;
    protected $table = 'project_product';
    protected $fillable = [
        'project_id',
        'product_id',        
        'quantity',
        'observation',
    ];

    protected $appends = ['total_output_project_product', 'state', 'quantity_with_liquidation'];

    public function liquidation() {
        return $this->hasMany(Liquidation::class);
    }

    public function getQuantityWithLiquidationAttribute() {
        return $this->total_output_project_product - $this->liquidation()->sum('liquidated_quantity');
    }

    public function output_project_product () {
        return $this->hasMany(OutputProjectProduct::class);
    }
    public function getTotalOutputProjectProductAttribute(){
        return $this->output_project_product()->sum('output_project_product.quantity');
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function getStateAttribute(){
        return $this->quantity - $this->total_output_project_product == 0 ? 'Completo' : 'Incompleto';
    }

}
