<?php

namespace App\Http\Controllers\SocialNetwork;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialNetwork\CreateAndpdateControl;
use App\Http\Requests\SocialNetwork\CreateAndpdatePayment;
use App\Models\SNSot;
use App\Models\SNSotControl;
use App\Models\SNSotPayment;
use Inertia\Inertia;

class SotController extends Controller
{
    public function sot_index()
    {
        return Inertia::render('SocialNetworkSot/SotIndex');
    }

    public function sot_payment_index()
    {   
        return Inertia::render('SocialNetworkSot/PaymentArea',[
            'payments' => SNSot::with('sot_payment')->paginate()
        ]);
    }

    public function sot_payment_udpate(CreateAndpdatePayment $request, $sot_id)
    {
        $validateData = $request->validated();
        SNSotPayment::updateOrCreate(
            ['s_n_sot_id' => $sot_id],
            $validateData
        );
    }

    public function sot_control_index()
    {
        return Inertia::render('SocialNetworkSot/ControlArea',[
            'controls' => SNSot::with('sot_control')->paginate()
        ]);
    }

    public function sot_control_udpate(CreateAndpdateControl $request, $sot_id)
    {
        $validateData = $request->validated();
        SNSotControl::updateOrCreate(
            ['s_n_sot_id' => $sot_id],
            $validateData
        );
    }
}
