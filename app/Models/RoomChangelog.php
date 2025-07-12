<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChangelog extends Model
{
    use HasFactory;
    protected $table = 'room_changelogs';

    protected $fillable = [
        'date',
        'mileage',
        'type',
        'invoice',
        'workshop',
        'contact_name',
        'contact_phone',
        'observation',
        'is_accepted',
        'room_id',
    ];

    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function room_changelog_items() {
        return $this->hasMany(RoomChangelogItem::class, 'room_changelog_id');
    }
}
