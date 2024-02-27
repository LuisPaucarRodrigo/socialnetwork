<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceDescription extends Model
{
    use HasFactory;
    protected $table = "resource_descriptions";
    protected $fillable = ['name'];
}
