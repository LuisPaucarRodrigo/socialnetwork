<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'warehouse_id'
    ];

    public function getUnitPriceAttribute() {
        return floatval($this->productHeaders()->where('header_id', 29)->first()->content)/$this->productHeaders()->where('header_id', 8)->first()->content;
    }

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

    protected $appends = ['unit_price','total_assigned_to_projects', 'total_available', 'state', 'has_different_price', 'total_sent_quantity_projects', 'total_refund_quantity_projects', 'total_used_quantity_projects'];

    public function projects(){
        return $this->belongsToMany(Project::class,'project_product');
    }

    public function getStateAttribute () {
        return $this->total_available > 0 ? 'Disponible' : 'No disponible';
    }
    public function getHasDifferentPriceAttribute () {
        return $this->productHeaders()->where('header_id',32 )->first()->content == '1'? true: false;
    }

    public function project_product(){
        return $this->hasMany(ProjectProduct::class);
    }

    public function getTotalAssignedToProjectsAttribute(){
        return $this->projects()->sum('project_product.quantity');
    }

    public function getTotalSentQuantityProjectsAttribute(){
        $total = $this->project_product()->get()->sum(function ($item) {
            return $item->total_output_project_product ?? 0;
        });
        return $total;
    }


    public function getTotalRefundQuantityProjectsAttribute(){
        $total = $this->project_product()->get()->sum(function ($item) {
            return $item->total_refund_quantity ?? 0;
        });
        return $total;
    }

    
    public function getTotalUsedQuantityProjectsAttribute(){
        $total = $this->project_product()->get()->sum(function ($item) {
            return $item->total_used_quantity ?? 0;
        });
        return $total;
    }
    
    public function getTotalAvailableAttribute () {
        $quantity = $this->productHeaders()->where('header_id', 8)->first();
        return $quantity->content - $this->total_assigned_to_projects + $this->total_refund_quantity_projects;
    }




















   
}
