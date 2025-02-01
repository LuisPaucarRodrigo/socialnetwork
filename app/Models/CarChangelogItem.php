<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarChangelogItem extends Model
{
    use HasFactory;

    protected $table = 'car_changelog_items';

    protected $fillable = [
        'name',
        'observation',
    ];

    //relations
    public function car_changelog()
    {
        return $this->belongsTo(CarChangelog::class, 'car_changelog_id');
    }
}
