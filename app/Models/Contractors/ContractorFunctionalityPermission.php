<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorFunctionalityPermission extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';

    protected $table = 'functionality_permissions';

    protected $fillable = [
        'functionality_id',
        'permission_id'
    ];
}
