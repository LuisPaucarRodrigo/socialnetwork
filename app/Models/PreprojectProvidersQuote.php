<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprojectProvidersQuote extends Model
{
    use HasFactory;
    protected $table = 'preproject_providersquotes';
    protected $fillable = [
        'provider_quote',
        'status',
        'preproject_id',
    ];

    public function preproject() {
        return $this->belongsTo(Preproject::class);
    }

}
