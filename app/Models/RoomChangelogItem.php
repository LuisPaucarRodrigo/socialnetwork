<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChangelogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'room_changelog_id'
    ];

    public function room_changelog()
    {
        return $this->belongsTo(RoomChangelog::class, 'room_changelog_id');
    }

}
