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

    public function project_product()
    {
        return $this->belongsTo(ProjectProduct::class, 'project_product_id');
    }

}
