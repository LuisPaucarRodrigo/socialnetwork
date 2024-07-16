<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreReportHuaweiGeneral extends Model
{
    use HasFactory;
    protected $fillable = [
        'site',
        'elaborated',
        'code',
        'name',
        'address',
        'reference',
        'access',
    ];
}
