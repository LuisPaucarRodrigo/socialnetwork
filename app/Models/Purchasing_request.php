<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasing_request extends Model
{
    use HasFactory;
    protected $fillable = ['project','title','product_description','due_date'];
}
