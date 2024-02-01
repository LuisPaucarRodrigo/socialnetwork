<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputProjectProduct extends Model
{
    use HasFactory;
    protected $table = 'output_project_product';
    protected $fillable = [
        'project_product_id',
        'quantity',
        'observation'
    ];

    public function project_product () {
        return $this->belongsTo(ProjectProduct::class);
    }
}
