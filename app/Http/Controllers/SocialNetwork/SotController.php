<?php

namespace App\Http\Controllers\SocialNetwork;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SotController extends Controller
{
    public function sot_index()
    {
        return Inertia::render('SocialNetworkSot/SotIndex');
    }

    public function sot_control_index()
    {
        return Inertia::render('SocialNetworkSot/ControlArea');
    }

    public function sot_control_udpate(Request $request,$soat_id)
    {
        
    }
}
