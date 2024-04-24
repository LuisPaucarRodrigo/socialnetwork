<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProjectProduct extends Model
{
    use HasFactory;
    protected $table = 'project_product';
    protected $fillable = [
        'project_id',
        'product_id',
        'quantity',
        'observation',
        'unit_price'
    ];

    protected $appends = [
        'total_output_project_product',
        'state',
        'is_deletable',
        'is_editable',
        'total_refund_quantity',
        'total_used_quantity'
    ];

    //CALCULATED
    public function getTotalRefundQuantityAttribute()
    {
        $outputProjectProducts = $this->output_project_product()->with('liquidation')->get();
        $totalRefundQuantity = $outputProjectProducts->sum(function ($item) {
            return $item->liquidation->refund_quantity ?? 0;
        });
        return $totalRefundQuantity;
    }

    public function getTotalUsedQuantityAttribute()
    {
        $outputProjectProducts = $this->output_project_product()->with('liquidation')->get();
        $totalRefundQuantity = $outputProjectProducts->sum(function ($item) {
            return $item->liquidation ? $item->liquidation->liquidated_quantity - $item->liquidation->refund_quantity : 0;
        });
        return $totalRefundQuantity;
    }

    public function getTotalOutputProjectProductAttribute()
    {
        return $this->output_project_product()->sum('output_project_product.quantity');
    }

    public function getStateAttribute()
    {
        return $this->quantity - $this->total_output_project_product == 0 ? 'Completo' : 'Incompleto';
    }

    public function getIsDeletableAttribute()
    {
        return $this->output_project_product()->get()->isEmpty();
    }

    public function getIsEditableAttribute()
    {
        return ($this->total_output_project_product != 0) && ($this->state != 'Completo');
    }

    //RELACTIONS
    public function liquidation()
    {
        return $this->hasMany(Liquidation::class);
    }

    public function output_project_product()
    {
        return $this->hasMany(OutputProjectProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
