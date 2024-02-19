<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preproject extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer',
        'code',
        'phone',
        'description',
        'address',
        'date',
        'observation',
        'facade',
    ];
    protected $appends = [
        'has_photo_report'
    ];


    public function project() {
        return $this->HasOne(Project::class);
    }

    public function imagepreproject() {
        return $this->HasMany(Imagespreproject::class);
    }


    public function getHasPhotoReportAttribute() {
        return true;
    }


    public function quote() {
        return $this->hasOne(PreProjectQuote::class,'preproject_id');
    }
}
