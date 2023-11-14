<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'cropped_image',
        'gender',
        'state_civil',
        'birthdate',
        'dni',
        'email',
        'phone1',
        'phone2'
        ];
    public function contract(){
        return $this->hasOne(Contract::class);
    }
    public function education(){
        return $this->hasOne(Education::class);
    }
    public function address(){
        return $this->hasOne(Address::class);
    }
    public function emergency(){
        return $this->hasOne(Emergency::class);
    }
    public function family(){
        return $this->hasOne(Family::class);
    }
    public function health(){
        return $this->hasOne(Health::class);
    }
}
