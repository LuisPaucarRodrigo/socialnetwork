<?php

namespace App\Http\Controllers\SocialNetwork;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialNetwork\SotStoreRequest;
use App\Http\Requests\SocialNetwork\SotOperationUpdateRequest;
use App\Http\Requests\SocialNetwork\SotLiquidationUpdateRequest;

use App\Models\SNSot;
use App\Models\SNSotLiquidation;
use App\Models\SNSotOperation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    public function sot_delete ($sot_id) {
        $user = Auth::user();
        if ($user->role_id !== 1) {return abort(404, "No autorizado");}
        SNSot::findOrFail($sot_id)->delete();
        return redirect()->back();
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

    public function sot_programation_update (SotStoreRequest $request, $sot_id) {
        $data = $request->validated();
        SNSot::findOrFail($sot_id)->update($data);
        return redirect()->back();
    }

    public function sot_liquidation ()
    {
        $user = Auth::user();
        if($user->role_id == 1){
            $sots = SNSot::with('user_assignee')
            ->whereDoesntHave('sot_liquidation')
            ->get();
        }else{
            $sots = SNSot::with('user_assignee')
            ->whereDoesntHave('sot_liquidation')
            ->where('user_assignee_id', $user->id)
            ->get();
        }
        $sotsLiquidation = SNSotLiquidation::with('sot')->paginate(15);
        return Inertia::render('SocialNetworkSot/SotLiquidation', [
            'sotsLiquidation' => $sotsLiquidation,
            'sots' => $sots
        ]);
    }   

    public function sot_liquidation_store (Request $request) {
        $data = $request->validate([
            'sot_id' => 'required',
            'up_minutes' => 'required',
            'liquidation' => 'required',
            'down_warehouse' => 'required',
            'liquidation_date' => 'required',
            'sot_status' => 'required'
        ]);
        SNSotLiquidation::create($data);
        return redirect()->back();
    }

    public function sot_liquidation_update (SotLiquidationUpdateRequest $request, $sot_liquidation_id) {
        $data = $request->validated();

        SNSotLiquidation::findOrFail($sot_liquidation_id)->update($data);
        return redirect()->back();
    }

    public function sot_operation ()
    {
        $user = Auth::user();
        if($user->role_id == 1){
            $sots = SNSot::with('user_assignee')
            ->whereDoesntHave('sot_operation')
            ->get();
        }else{
            $sots = SNSot::with('user_assignee')
            ->whereDoesntHave('sot_operation')
            ->where('user_assignee_id', $user->id)
            ->get();
        }
 
        $sotsOperation = SNSotOperation::with('sot')->paginate(15);
        return Inertia::render('SocialNetworkSot/SotOperation', [
            'sotsOperation' => $sotsOperation,
            'sots' => $sots
        ]);
    }  

    public function sot_operation_store (Request $request) {
        $user = Auth::user();
        $sot = SNSot::with('sot_operation')->find($request->sot_id);
        if ($user->id == $sot->user_assignee_id || $user->role_id == 1) {
            if($sot->sot_operation){
                abort(500, 'Acción no permitida');
            }else{
                $data = $request->validate([
                    'sot_id' => 'required',
                    'i_state' => 'required',
                    'additionals' => 'required',
                    'photo_report' => 'required',
                    'ic_date' => 'required',
                    'bill_amount' => 'required'
                ]);
                SNSotOperation::create($data);
                return redirect()->back();
            }
        }else{
            abort(403, 'No está autorizado');
        }  
    }

    public function sot_operation_update (SotOperationUpdateRequest $request, $sot_operation_id) {
        $user = Auth::user();
        $sot = SNSot::find($request->sot_id);
        if ($user->id == $sot->user_assignee_id || $user->role_id == 1) {
            $data = $request->validated();
            SNSotOperation::findOrFail($sot_operation_id)->update($data);
            return redirect()->back();
        }else{
            abort(403, 'No está autorizado');
        }
    }
    
}
