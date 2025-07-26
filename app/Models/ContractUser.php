<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractUser extends Model
{
    protected $connection = 'mysql2';
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
