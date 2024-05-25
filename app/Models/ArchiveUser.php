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
        "due_date",
        "observation",
        "evaluation_date",
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class, 'archive_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


}
