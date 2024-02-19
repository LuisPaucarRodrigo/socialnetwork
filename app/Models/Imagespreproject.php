<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagespreproject extends Model
{
    use HasFactory;
    protected $fillable=[
        'description',
        'image',
        'customervisit_id'
    ];

    public function customervisit() {
        return $this->belongsTo(Customervisit::class, 'customervisit_id');
    }
}
