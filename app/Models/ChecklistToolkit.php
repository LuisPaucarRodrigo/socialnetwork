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
        'additionalEmployees',
        'zone',
        'carabiner',
        'wireStripper',
        'crimper',
        'terminalCrimper',
        'files',
        'allenKeys',
        'readlineKit',
        'impactWrench',
        'dielectricTools',
        'cuttingTools',
        'forceps',
        'straightWrench',
        'frenchWrench',
        'saw',
        'silicone',
        'pulley',
        'tapeMeasure',
        'sling',
        'kit',
        'drillBits',
        'punch',
        'extractor',
        'wrenchSet',
        'cutter',
        'hammer',
        'largeToolBag',
        'mediumToolBag',
        'fallProtectionCar',
        'lifeline',
        'positioningLanyard',
        'harness',
        'pressureWasher',
        'blower',
        'megommeter',
        'earthTester',
        'perimeterMeter',
        'manometer',
        'pyrometer',
        'laptop',
        'drill',
        'compass',
        'inclinometer',
        'flashlight',
        'powerMeter',
        'glueGun',
        'solderingGun',
        'stepLadder',
        'sprayer',
        'rj45Connector',
        'networkConsole',
        'networkAdapter',
        'hotStick',
        'rope75',
        'ladder',
        'extensionCord',
        'longCable',
        'padlock',
        'chains',
        'hose',
        'corporatePhone',
        'observation',
        'badTools',
    ];
}
