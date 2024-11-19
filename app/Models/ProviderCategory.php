<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function providersSegment(){
        return $this->hasMany(ProviderSegment::class);
    }
}
