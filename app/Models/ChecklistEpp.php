<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistEpp extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'helmet',
        'chin_strap',
        'windbreaker',
        'vest',
        'safety_shoes',
        'tshirt_ls',
        'shirt_ls',
        'jeans',
        'coveralls',
        'jacket',
        'dark_glasses',
        'clear_glasses',
        'overglasses',
        'dust_mask',
        'earplugs',
        'latex_oil_gloves',
        'nitrile_oil_gloves',
        'split_leather_gloves',
        'precision_gloves',
        'cut_resistant_gloves',
        'double_lanyard',
        'harness',
        'positioning_lanyard',
        'carabiners',
        'ascenders',
        'sunscreen',
        'ccip',
        'claro',
        'vericom',
    ];
}
