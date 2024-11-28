<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use App\Models\CostLine;
use Inertia\Inertia;

class CostLineController extends Controller
{
    
    public function cost_line_store (Request $request, $cl_id = null) {
        $data = $request->validate(['name'=>'required']);
        $rg = CostLine::updateOrCreate(['id'=>$cl_id],$data);
        return response()->json($rg);
    }

    public function cost_line_destroy ($cl_id) {
        CostLine::findOrFail($cl_id)->delete();
        return response()->json(true);
    }

    public function cost_centers_index ($cl_id) {
        $costCenters = CostCenter::where('cost_line_id', $cl_id)->get();
        return Inertia::render('Finance/Budget/CostCenters', [
            'costCenters' => $costCenters,
            'cost_line' => CostLine::find($cl_id),
        ]);
    }

    public function cost_center_store (Request $request, $cc_id = null) {
        $data = $request->validate(['name'=>'required', 'percentage'=>'required', 'cost_line_id'=>'required']);
        $rg = CostCenter::updateOrCreate(['id'=>$cc_id],$data);
        return response()->json($rg);
    }
    
    public function cost_center_destroy ($cc_id) {
        CostCenter::findOrFail($cc_id)->delete();
        return response()->json(true);
    }
}
