<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoReport extends Model
{
    use HasFactory;
    protected $table = 'photoreports';
    protected $fillable = [
        'pdf_report',
        'excel_report',
        'preproject_id'
    ];

    
    public function preproject() {
        return $this->belongsTo(Preproject::class,'preproject_id');
    }
}
