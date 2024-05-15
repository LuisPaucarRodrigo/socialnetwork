<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $table = 'folders';
    protected $fillable = [
        "name",
        "path",
        "type",
        "archive_type",
        "state",
        "user_id",
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function areas() {
        return $this->belongsToMany(Area::class, 'folder_area');
    }

    public function folder_areas () {
        return $this->hasMany(FolderArea::class, 'folder_id');
    }

    public function archives () {
        return $this->hasMany(Archive::class, 'folder_id');
    }


}
