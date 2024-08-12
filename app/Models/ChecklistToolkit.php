<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistToolkit extends Model
{
    use HasFactory;
    protected $fillable = [
        'reason',
        'user_id',
        'personal_2',
        'zone',
        'mosqueton',
        'pelacable',
        'crimpeadora',
        'crimpeadorater',
        'limas',
        'allen',
        'readline',
        'impacto',
        'dielectricos',
        'corte',
        'fuerza',
        'recto',
        'francesas',
        'sierra',
        'silicona',
        'polea',
        'wincha',
        'eslinga',
        'botiquin',
        'brocas',
        'sacabocado',
        'extractor',
        'maletagrande',
        'maletamediana',
        'juego_llaves',
        'juego_bravos',
        'cuter',
        'arnes',
        'hidrolavadora',
        'sopladora',
        'megometro',
        'telurometro',
        'aperimetrica',
        'manometro',
        'pirometro',
        'laptop',
        'taladro',
        'brujula',
        'inclinometro',
        'linterna',
        'powermeter',
        'pistola',
        'pertiga',
        'soga75',
        'escalera',
        'extension',
        'pistolaestano',
        'escaleratijera',
        'pulverizadora',
        'testeadorrj45',
        'cableconsolared',
        'adaptadorred',
        'observations'
    ];
}
