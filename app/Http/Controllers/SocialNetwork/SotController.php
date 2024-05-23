<?php

namespace App\Http\Controllers\SocialNetwork;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialNetwork\SotStoreRequest;
use App\Models\SNSot;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SotController extends Controller
{
    public function sot_index () {
        $sots = SNSot::with('user_assignee',
                            'sot_operation',
                            'sot_liquidation',
                            'sot_payment',
                            'sot_control')
                     ->paginate(15);
        return Inertia::render('SocialNetworkSot/SotIndex', [
            'sots' => $sots
        ]);
    }

    public function sot_programation () {
        $users = User::all();
        $snop_users = $users->filter(function ($user) {
            return $user->role_id !== 1 && 
                    $user->hasPermission('SocialNetwork') &&
                   $user->hasPermission('SocialNetworkOperation');
        });
        $sots = SNSot::with('user_assignee')->paginate(15);
        return Inertia::render('SocialNetworkSot/SotProgramation', [
            'sots' => $sots,
            'snop_users' => $snop_users
        ]);
    }

    public function sot_programation_store (SotStoreRequest $request) {
        $data = $request->validated();
        SNSot::create($data);
        return redirect()->back();
    }

    public function sot_programation_update () {
        $sots = SNSot::paginate(15);
        return Inertia::render('SocialNetworkSot/SotProgramation', [
            'sots' => $sots
        ]);
    }
}
