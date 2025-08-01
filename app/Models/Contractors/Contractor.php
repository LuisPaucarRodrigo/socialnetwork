<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';

    protected $table = 'contractors';

    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->hasMany(ContractorUser::class, 'contractor_id');
    }

    public function employees()
    {
        return $this->hasMany(ContractorEmployee::class, 'contractor_id');
    }

    public function external_employees()
    {
        return $this->hasMany(ContractorExternalEmployee::class, 'contractor_id');
    }
}
