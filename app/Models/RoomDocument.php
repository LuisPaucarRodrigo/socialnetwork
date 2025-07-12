<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomDocument extends Model
{
    use HasFactory;

    protected $table = 'room_documents';

    protected $fillable = [
        'ownership_card',
        'technical_review',
        'technical_review_date',
        'soat',
        'soat_date',
        'insurance',
        'insurance_date',
        'rental_contract',
        'rental_contract_date',
        'address_web',
        'user',
        'password',
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
