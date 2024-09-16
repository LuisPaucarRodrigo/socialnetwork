<?php

namespace App\Http\Controllers;

use App\Http\Requests\HumanResource\DocumentRegisterRequest;
use App\Http\Requests\HumanResource\InsuranceExpDateRequest;
use App\Models\Document;
use App\Models\DocumentRegister;
use App\Models\DocumentSection;
use App\Models\Employee;
use App\Models\ExternalEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DocumentSpreedSheetController extends Controller
{
    public function index()
    {
        $employees = Employee::with([
            'document_registers',
            'contract:id,state,employee_id,hire_date,discount_sctr,pension_id',
            ])
            ->select(
                'id',
                'name',
                'lastname',
                'phone1',
                'email',
                'dni',
                'l_policy',
                'sctr_exp_date',
                'policy_exp_date',
            )
            ->orderBy('lastname')
            ->get()
            ->map(function ($emp) {
                $formattedDr = $emp->document_registers->mapWithKeys(
                    function ($dr) {
                        $itemDr = [
                            $dr->subdivision_id => [
                                'id' => $dr->id,
                                'document_id' => $dr->document_id,
                                'employee_id' => $dr->employee_id,
                                'e_employee_id' => $dr->e_employee_id,
                                'exp_date' => $dr->exp_date,
                                'state' => $dr->state,
                                'observations' => $dr->observations,
                                'sync_status' => $dr->sync_status,
                                'display' => $dr->display,

                            ]
                        ];
                        return $itemDr;
                    }
                );
                $emp->setRelation('document_registers', $formattedDr);
                return $emp;
            });
        $e_employees = ExternalEmployee::with([
            'document_registers',
            ])
            ->select(
                'id',
                'name',
                'lastname',
                'phone1',
                'email',
                'dni',
                'sctr',
                'l_policy',
                'sctr_exp_date',
                'policy_exp_date',
            )
            ->get()
            ->map(function ($emp) {
                $formattedDr = (object) [];
                $formattedDr = $emp->document_registers->mapWithKeys(
                    function ($dr) {
                        return [
                            $dr->subdivision_id => [
                                'id' => $dr->id,
                                'document_id' => $dr->document_id,
                                'employee_id' => $dr->employee_id,
                                'e_employee_id' => $dr->e_employee_id,
                                'exp_date' => $dr->exp_date,
                                'state' => $dr->state,
                                'observations' => $dr->observations,
                                'sync_status' => $dr->sync_status,
                                'display' => $dr->display,

                            ]
                        ];
                    }
                );
                $emp->setRelation('document_registers', $formattedDr);
                return $emp;
            });
        $sections = DocumentSection::with('subdivisions')->get();
        return Inertia::render(
            'HumanResource/DocumentSpreedSheet/Index',
            [
                'employees' => $employees,
                'e_employees' => $e_employees,
                'sections' => $sections,
            ]
        );
    }

    public function store(DocumentRegisterRequest $request, $dr_id=null) {
        $data = $request->validated();
        $item = DocumentRegister::find($dr_id);
        if ($item){
            $item->update($data);
        } else {
            $item = DocumentRegister::create($data);
        }
        $docItem = Document::find($item->document_id);
        if ($docItem && $docItem->exp_date === null){
            $docItem->update(['exp_date'=>$item->exp_date]);
        }

        $item->without('document');
        return response()->json([$item->subdivision_id=>$item], 200);
    }


    public function destroy ($dr_id=null){
        $item = DocumentRegister::find($dr_id);
        $item->delete();
        return response()->json(['msg'=>'Eliminado'], 200);
    }


    public function insurance_exp_date (InsuranceExpDateRequest  $request){
        $data = $request->validated();
        if($data['title']==='SCTR'){
            Employee::whereHas('contract', function ($query) {
                $query->where('discount_sctr', 1);
            })->update(['sctr_exp_date'=>$data['exp_date']]);
        }
        if($data['title']==='Póliza'){
            Employee::where('l_policy', '1')->update(['policy_exp_date'=>$data['exp_date']]);
        }
        return response()->json(['msg'=>'success'], 200);
    }









    // public function buildDocReg () {
    //     $string = 'jimmy';
    //     $emp = Employee::where('name', 'like', '%'.$string. '%');
    //     // $emp = Employee::where('lastname', 'like', '%'.$string. '%');
    //     if($emp->get()->count()===0){dd('no hay registros');}
    //     if ($emp->get()->count()>1){
    //         dd('mas de un employee');
    //     } 
    //     $selEmp = $emp->first();
    //     $documents = Document::where('title', 'like', '%'.$string.'%')->get();
    //     DB::beginTransaction();
    //     try{
    //         foreach($documents as $item){
    //             DocumentRegister::create([
    //             'subdivision_id'=> $item->subdivision_id,
    //             'document_id'=> $item->id,
    //             'employee_id'=> $selEmp->id,
    //             'state'=> 'Completado',
    //             ]);
    //         }
    //         DB::commit();
    //     } catch (e){
    //         DB::rollBack();
    //         dd('errrorr creando doc registers');
    //     }
        
    // }

}
