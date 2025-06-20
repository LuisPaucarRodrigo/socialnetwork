<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\DocumentRegisterRequest;
use App\Http\Requests\HumanResource\InsuranceExpDateRequest;
use App\Models\CostLine;
use App\Models\Document;
use App\Models\DocumentRegister;
use App\Models\DocumentSection;
use App\Models\Employee;
use App\Models\Subdivision;
use App\Models\ExternalEmployee;
use App\Policies\HumanResources\DocumentSpreedSheetPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DocumentSpreedSheetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $employees = Employee::with([
                'document_registers',
                'contract:id,state,employee_id,hire_date,discount_sctr',
            ])->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
                ->select(
                    'id',
                    'name',
                    'lastname',
                    'phone1',
                    'email',
                    'email_company',
                    'dni',
                    'l_policy',
                    'sctr_exp_date',
                    'policy_exp_date',
                )
                ->orderBy('name')
                ->get();
            $employees->each->setAppends(['sctr_about_to_expire', 'policy_about_to_expire']);
            $e_employees = ExternalEmployee::with([
                'document_registers',
            ])
                ->select(
                    'id',
                    'name',
                    'lastname',
                    'phone1',
                    'email',
                    'email_company',
                    'dni',
                    'sctr',
                    'l_policy',
                    'sctr_exp_date',
                    'policy_exp_date',
                )

                ->get();
            $e_employees->each->setAppends(['sctr_about_to_expire', 'policy_about_to_expire']);
        } elseif ($request->isMethod('post')) {
            $searchquery = $request->searchquery;
            $employees = Employee::with([
                'document_registers',
                'contract:id,state,employee_id,hire_date,discount_sctr,cost_line_id',
                'contract.cost_line:id,name'
            ])
                ->whereHas('contract', function ($query) {
                    $query->where('state', 'Active');
                })
                ->select(
                    'id',
                    'name',
                    'lastname',
                    'phone1',
                    'email',
                    'email_company',
                    'dni',
                    'l_policy',
                    'sctr_exp_date',
                    'policy_exp_date',
                )
                ->whereHas('contract', function ($query) use ($searchquery) {
                    if ($searchquery) {
                        $query->whereHas('cost_line', function ($subquery) use ($searchquery) {
                            $subquery->where('name', 'like', '%' . $searchquery . '%');
                        });
                    } else {
                        return;
                    }
                })
                ->orderBy('name')
                ->get();
            $employees->each->setAppends(['sctr_about_to_expire', 'policy_about_to_expire']);

            $e_employees = ExternalEmployee::with([
                'document_registers',
            ])
                ->select(
                    'id',
                    'name',
                    'lastname',
                    'phone1',
                    'email',
                    'email_company',
                    'dni',
                    'sctr',
                    'l_policy',
                    'sctr_exp_date',
                    'policy_exp_date',
                    'cost_line_id'
                );
            if ($searchquery) {
                $e_employees = $e_employees->whereHas('cost_line', function ($query) use ($searchquery) {
                    $query->where('name', 'like', '%' . $searchquery . '%');
                })->get();
            } else {
                $e_employees = $e_employees->get();
            }
            $e_employees->each->setAppends(['sctr_about_to_expire', 'policy_about_to_expire']);
        }

        $employees->map(function ($emp) {
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

        $e_employees->map(function ($emp) {
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
            $emp->contract = ['state' => 'External'];
            $emp->setRelation('document_registers', $formattedDr);
            return $emp;
        });
        $sections = DocumentSection::with([
            'subdivisions' => function ($subq) {
                $subq->where('is_visible', true);
            }
        ])
            ->where('is_visible', true)
            ->get();
        $costLines = CostLine::all();
        if ($request->isMethod('get')) {
            return Inertia::render(
                'HumanResource/DocumentSpreedSheet/Index',
                [
                    'employees' => $employees,
                    'e_employees' => $e_employees,
                    'sections' => $sections,
                    'costLines' => $costLines,
                ]
            );
        } elseif ($request->isMethod('post')) {
            return response()->json([
                'employees' => $employees,
                'e_employees' => $e_employees,
            ], 200);
        }
    }


    public function employee_document_alarms($emp_id, $type)
    {
        $employee = null;
        if ($type === 'employees') {
            $employee = Employee::with([
                'document_registers',
                'contract:id,state,employee_id,hire_date,discount_sctr',
            ])
                ->select(
                    'id',
                    'name',
                    'lastname',
                    'phone1',
                    'email',
                    'email_company',
                    'dni',
                    'l_policy',
                    'sctr_exp_date',
                    'policy_exp_date',
                )
                ->find($emp_id);
            $employee->setAppends(['sctr_about_to_expire', 'policy_about_to_expire']);
        } else if ($type === 'external') {
            $employee = ExternalEmployee::with([
                'document_registers',
            ])
                ->select(
                    'id',
                    'name',
                    'lastname',
                    'phone1',
                    'email',
                    'email_company',
                    'dni',
                    'sctr',
                    'l_policy',
                    'sctr_exp_date',
                    'policy_exp_date',
                    'cost_line_id'
                )
                ->find($emp_id);
            $employee->setAppends(['sctr_about_to_expire', 'policy_about_to_expire']);
        }

        if ($employee) {
            $formattedDr = $employee->document_registers->mapWithKeys(function ($dr) {
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
                    ],
                ];
            });

            $employee->setRelation('document_registers', $formattedDr);
        }
        //$sectionsToSearch = app(DocumentSpreedSheetPolicy::class)->sections();
        $sections = DocumentSection::with([
            'subdivisions' => function ($subq) {
                $subq->where('is_visible', true);
            }
        ])
            ->where('is_visible', true)
            ->get();
        return Inertia::render('HumanResource/DocumentSpreedSheet/EmployeeDocumentAlarms', [
            'employee' => $employee,
            'sections' => $sections,
        ]);
    }




    public function create(DocumentRegisterRequest $request)
    {
        $data = $request->validated();
        $state = $data['state'];
        $docRegPrev = new DocumentRegister($data);
        $docItem = $this->getDocument($docRegPrev);
        if ($state === 'Completado') {
            if ($docItem) {
                $this->file_delete($docItem);
                $document = $request->file('document');
                $data['title'] = $this->file_store($data, $document);
                $docItem->update($data);
            } else {
                $document = $request->file('document');
                $data['title'] = $this->file_store($data, $document);
                $docItem = Document::create($data);
            }
            $data['document_id'] = $docItem->id;
            $docReg = DocumentRegister::create($data);
        } else {
            $docReg = DocumentRegister::create($data);
        }
        return response()->json([$docReg->subdivision_id => $docReg]);
    }


    public function update(DocumentRegisterRequest $request, $dr_id)
    {
        $data = $request->validated();
        $docReg = DocumentRegister::find($dr_id);
        $docItem = $this->getDocument($docReg);
        $state = $data['state'];
        if ($state === 'Completado') {
            if ($docItem) {
                $this->file_delete($docItem);
                $document = $request->file('document');
                $data['title'] = $this->file_store($data, $document);
                $docItem->update($data);
            } else {
                $document = $request->file('document');
                $data['title'] = $this->file_store($data, $document);
                $docItem = Document::create($data);
                $data['document_id'] = $docItem->id;
            }
        }
        if ($state === 'No Corresponde' && $docItem) {
            $this->file_delete($docItem);
            $docItem->delete();
        }
        $docReg->update($data);
        return response()->json([$docReg->subdivision_id => $docReg]);
    }






    public function destroy($dr_id = null)
    {
        $item = DocumentRegister::with('document')->find($dr_id);
        $docItem = $item->document;
        if ($docItem) {
            $this->file_delete($docItem);
            $docItem->delete();
        }
        $item->delete();
        return response()->json(['msg' => 'Eliminado'], 200);
    }


    public function insurance_exp_date(InsuranceExpDateRequest $request)
    {
        $data = $request->validated();
        if ($data['title'] === 'SCTR') {
            Employee::whereHas('contract', function ($query) {
                $query->where('discount_sctr', 1);
            })->update(['sctr_exp_date' => $data['exp_date']]);
            ExternalEmployee::where('sctr', 1)->update(['sctr_exp_date' => $data['exp_date']]);
        }
        if ($data['title'] === 'Póliza') {
            Employee::where('l_policy', '1')->update(['policy_exp_date' => $data['exp_date']]);
            ExternalEmployee::where('l_policy', 1)->update(['sctr_exp_date' => $data['exp_date']]);
        }
        return response()->json(['msg' => 'success'], 200);
    }



    public function employeesDocumentAlarms()
    {
        try {
            $employees = Employee::whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })->orderBy('name')->get();
            $employees->each->setAppends(['documents_about_to_expire', 'type']);
            $employees = $employees->filter(function ($item) {
                return $item->documents_about_to_expire > 0;
            })->values()->all();

            $e_employees = ExternalEmployee::orderBy('lastname')->get();
            $e_employees->each->setAppends(['documents_about_to_expire', 'type']);
            $e_employees = $e_employees->filter(function ($item) {
                return $item->documents_about_to_expire > 0;
            })->values()->all();
            $employees = array_merge($employees, $e_employees);
            return response()->json($employees, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    public function employeesNoDocumentAlarms()
    {
        try {
            $employees = Employee::whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })->orderBy('name')->get();
            $employees->each->setAppends(['no_documents', 'type']);
            $employees = $employees->filter(function ($item) {
                return $item->no_documents;
            })->values()->all();

            $e_employees = ExternalEmployee::orderBy('lastname')->get();
            $e_employees->each->setAppends(['no_documents', 'type']);
            $e_employees = $e_employees->filter(function ($item) {
                return $item->no_documents;
            })->values()->all();
            $employees = array_merge($employees, $e_employees);
            return response()->json($employees, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function createMasive(Request $request, $employee_id) {
        DB::beginTransaction();
        try{
            $data = $request->all();
            foreach($data as $subdivision_id=>$state){
                if($state === 'No corresponde'){
                    DocumentRegister::create([
                        'subdivision_id'=> $subdivision_id,
                        'document_id'=> null,
                        'employee_id'=> $employee_id,
                        'e_employee_id'=> null,
                        'exp_date'=> null,
                        'state'=> $state,
                        'observations'=> null,
                    ]);
                }
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }


    // public function updateDocReg () {
    //     $items = DocumentRegister::all();

    //     DB::beginTransaction();
    //     try{
    //         foreach($items as $dr){
    //             if ($dr->document_id){
    //                 Document::find($dr->document_id)->update([
    //                     'employee_id'=> $dr->employee_id,
    //                     'e_employee_id'=> $dr->e_employee_id,
    //                 ]);
    //             }

    //         }
    //         DB::commit();
    //     } catch (e) {
    //         DB::rollBack();
    //         dd('errrorr creando doc registers');
    //     }
    // }








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
    private function getDocument($docReg)
    {
        return Document::where('subdivision_id', $docReg->subdivision_id)
            ->where('employee_id', $docReg->employee_id)
            ->where('e_employee_id', $docReg->e_employee_id)
            ->first();
    }

    private function file_store($data, $document)
    {
        $name = $this->getFileName($data, $document);
        $document->move(public_path('documents/documents/'), $name);
        return $name;
    }

    private function file_delete($docItem)
    {
        $fileName = $docItem->title;
        $filePath = "documents/documents/$fileName";
        $path = public_path($filePath);
        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
    }

    private function getFileName($data, $document)
    {
        $employee_name = $data['employee_id'] ? Employee::where('id', $data['employee_id'])
            ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
            ->first() : ExternalEmployee::where('id', $data['e_employee_id'])
            ->selectRaw("CONCAT(name, ' ', lastname) as full_name")
            ->first();
        $name = Subdivision::find($data['subdivision_id'])->name . ' - ' . $employee_name->full_name . '.' . $document->getClientOriginalExtension();
        return $name;
    }
}
