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

    protected $appends = ['total_assigned_to_projects', 'total_available', 'state'];

    public function projects(){
        return $this->belongsToMany(Project::class,'project_product');
    }

    public function getStateAttribute () {
        return $this->total_available > 0 ? 'Disponible' : 'No disponible';
    }


    public function project_product(){
        return $this->hasMany(ProjectProduct::class)->withPivot('quantity_with_liquidation');
    }
    public function getTotalAssignedToProjectsAttribute(){
        return $this->projects()->sum('project_product.quantity');
    }




    public function getTotalAvailableAttribute () {
        $quantity = $this->productHeaders()->where('header_id', 8)->first();
        return $quantity->content - $this->total_assigned_to_projects;
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
}
