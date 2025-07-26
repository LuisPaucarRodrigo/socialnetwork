<?php

namespace App\Http\Controllers;

use App\Constants\PextConstants;
use App\Constants\PintConstants;

class ConstantController extends Controller
{
    public function facturation($type)
    {
        $zonesList = $type == '2' ? PextConstants::getZone() : PintConstants::allZones();
        $clients = [
            'CICSA',
            'STL',
            'INDRA',
            'OTROS',
        ];
        $data = [
            'zonesList' => $zonesList,
            'clientsList' => $clients,
        ];
        return response()->json($data, 200);
    }
}
