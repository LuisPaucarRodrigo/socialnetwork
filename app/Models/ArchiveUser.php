<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveUser extends Model
{
    use HasFactory;
    protected $table = 'archive_user';
    protected $fillable = [
        "archive_id",
        "user_id",
        "state",
        "status",
        "observation",
        "evaluation_date",
    ];
}
