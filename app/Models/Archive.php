<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;
    protected $table = 'archives';
    protected $fillable = [
        "name",
        "path",
        "user_id",
        "version",
        "folder_id",
    ];

    public function users () {
        return $this->belongsToMany(User::class, 'archive_user');
    }

    public function archive_users() {
        return $this->hasMany(ArchiveUser::class, 'archive_id');
    }


}
