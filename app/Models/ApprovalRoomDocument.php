<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRoomDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'ownership_card',
        'technical_review',
        'technical_review_date',
        'soat',
        'soat_date',
        'insurance',
        'insurance_date',
        'address_web',
        'user',
        'password',
        'room_document_id',
    ];

    public function room_document()
    {
        return $this->belongsTo(RoomDocument::class, 'room_document_id');
    }
}
