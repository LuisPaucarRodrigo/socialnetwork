<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDocument extends Model
{
    use HasFactory;

    protected $table = 'car_documents';

    protected $fillable = [
        'ownership_card',
        'technical_review',
        'soat',
        'insurance',
        'car_id',
    ];

    //relations
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
