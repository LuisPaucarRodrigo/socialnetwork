<?php

namespace App\Http\Controllers\SocialNetwork;

use App\Http\Controllers\Controller;
use App\Models\SNSot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SotController extends Controller
{
    public function sot_index () {
        $sots = SNSot::with('sot_operation',
                            'sot_liquidation',
                            'sot_payment',
                            'sot_control')
                     ->paginate(15);
        return Inertia::render('SocialNetworkSot/SotIndex', [
            'sots' => $sots
        ]);
    }
}
