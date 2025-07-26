<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorRoleFunctionality extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';

    protected $table = 'role_functionalities';
}
