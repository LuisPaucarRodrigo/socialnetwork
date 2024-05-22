<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SNSotControl extends Model
{
    use HasFactory;
    protected $table = 'sn_sot_control';
    protected $fillable = [
        'sot_id',
        'p_bad_installation',
        'p_no_rf',
        'p_rejections',
    ];

    public function sot() {
        return $this->belongsTo(SNSot::class, 'sot_id');
    }
}
