<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomDocument extends Model
{
    use HasFactory;

    protected $table = 'room_documents';

    protected $fillable = [
        'archive',
        'observations',
        'expiration_date',
        'room_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function approvel_room_document()
    {
        return $this->hasMany(ApprovalRoomDocument::class);
    }
}
