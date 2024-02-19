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
        'has_photo_report',
        'is_appropriate',
    ];


    public function project() {
        return $this->hasOne(Project::class);
    }


    public function getHasPhotoReportAttribute() {
        return true;
    }


    public function quote() {
        return $this->hasOne(PreProjectQuote::class,'preproject_id');
    }

    public function getIsAppropriateAttribute(){
        $quote_status =  $this->quote()->first()?->state;
        $project = $this->project()->first();
        return $quote_status && ($project === null);
    }
}
