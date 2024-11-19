<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderSegment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'provider_category_id'
    ];

    public function providersCategory() : BelongsTo {
        return $this->belongsTo(ProviderCategory::class,'provider_category_id');
    }
}
