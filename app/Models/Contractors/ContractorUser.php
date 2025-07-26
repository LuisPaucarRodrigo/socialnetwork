<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Model;

class ContractorUser extends Model
{
    protected $connection = 'mysql_secondary';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'dni',
        'platform',
        'password',
        'role_id',
        'area_id',
        'phone'
    ];
}
