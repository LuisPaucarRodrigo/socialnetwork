<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use App\Models\CostLineCenterEmployee;
use App\Models\Employee;
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

    public function cost_line_employee($cl_id) {
        $employees = Employee::with('contract')->whereHas('contract', function($query){
            $query->where('cost_line_id',null);
        })->orderBy('name')->get()->each->setAppends([]);
        $currentEmployees = Employee::with('contract.cost_line')->whereHas('contract', function($query) use ($cl_id){
            $query->where('cost_line_id',$cl_id);
        })->orderBy('name')->get()->each->setAppends([]);
        $cost_line = CostLine::find($cl_id);
        return Inertia::render('Finance/Budget/CostLineEmployee',[
            'employees'=>$employees,
            'currentEmployees'=>$currentEmployees,
            'cost_line'=>$cost_line,
        ]);
    }

    public function cost_line_employee_destroy($emp_id) {
        $contract = Employee::findOrFail($emp_id)->contract();
        CostLineCenterEmployee::where('employee_id', $emp_id)->delete();
        $contract->update(['cost_line_id'=>null]);
        return response()->json(true);
    }

    public function cost_line_employee_store(Request $request ) {
        $data = $request->validate([
            'employee_id' => 'required',
            'cost_line_id' => 'required'
        ]);
        $employee = Employee::with('contract')->findOrFail($data['employee_id'])->setAppends([]);
        $contract = Employee::findOrFail($data['employee_id'])->contract();
        $contract->update(['cost_line_id'=>$data['cost_line_id']]);
        return response()->json($employee);
    }

    public function searchCostLineEmployees($cl_id =null){
        $employees = Employee::with('contract')->whereHas('contract', function($query) use ($cl_id){
            $query->where('cost_line_id',$cl_id);
        })->orderBy('name')->get()->each->setAppends([]);
        return response()->json($employees);
    }

    public function cost_centers_index ($cl_id) {
        $employees = Employee::with('contract.cost_line')->whereHas('contract', function($query) use ($cl_id){
            $query->where('cost_line_id',$cl_id);
        })->orderBy('name')->get()->each->setAppends([]);
        $costCenters = CostCenter::with('clc_employees.employee')->where('cost_line_id', $cl_id)->get();
        return Inertia::render('Finance/Budget/CostCenters', [
            'costCenters' => $costCenters,
            'cost_line' => CostLine::find($cl_id),
            'employees' => $employees,
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

    public function cost_centers_employee_store (Request $request) {
        $data = $request->validate([
            'employees' => 'required|array',
            'cost_center_id' => 'required',
        ]);
        CostLineCenterEmployee::where('cost_center_id', $data['cost_center_id'])->delete();
        foreach($data['employees'] as $emp_id){
            CostLineCenterEmployee::create([
                'employee_id' => $emp_id,
                'cost_center_id'=>$data['cost_center_id'],
            ]);
        }
        $costCenter =  CostCenter::with('clc_employees.employee')->find($data['cost_center_id']);
        return response()->json($costCenter);
    }

    
}
