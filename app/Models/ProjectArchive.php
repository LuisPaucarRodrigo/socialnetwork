<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectArchive extends Model
{
    use HasFactory;
    protected $table = 'project_archives';
    protected $fillable = [
        'name',
        'path',
        'state',
        'upper_folder_id',
    ];

    public function upper_folder () {
        $this->belongsTo(ProjectFolder::class, 'upper_folder_id');
    }
}
