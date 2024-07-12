<?php

namespace App\Http\Controllers\HumanResource;

use App\Actions\Jetstream\UpdateTeamName;
use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\CreateManagementEmployees;
use App\Http\Requests\HumanResource\FiredContractEmployees;
use App\Http\Requests\HumanResource\UpdateManagementEmployees;
use App\Models\Address;
use App\Models\Contract;
use App\Models\Education;
use App\Models\Emergency;
use App\Models\Employee;
use App\Models\Family;
use App\Models\Health;
use App\Models\Pension;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ManagementEmployees extends Controller
{
    public function index($reentry = false)
    {
        if ($reentry == false) {
            $employees = Employee::with('contract')->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })->paginate();
        } else {
            $employees = Employee::with('contract')->whereHas('contract', function ($query) {
                $query->where('state', 'Inactive');
            })->paginate();
        }
        $employees->each(function ($employee) {
            $employee->cropped_image = url($employee->cropped_image ? '/image/profile/' . $employee->cropped_image : '/image/projectimage/DefaultUser.png');
        });

        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employees' => $employees,
            'boolean' => boolval($reentry)
        ]);
    }


    public function search(Request $request) {
        $searchTerm = strtolower($request->query('searchTerm'));
        $isActive = $request->query('isActive');

        if ($isActive) {
            $employees = Employee::with('contract')
                ->whereHas('contract', function ($query) {
                    $query->where('state', 'Active');
                });
        } else {
            $employees = Employee::with('contract')
                ->whereHas('contract', function ($query) {
                    $query->where('state', 'Inactive');
                });        
        }
        $employees = $employees->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone1', 'like', '%' . $searchTerm . '%')
                  ->orWhere('dni', 'like', '%' . $searchTerm . '%');
        })
        ->get();
        $employees->each(function ($employee) {
            $employee->cropped_image = url($employee->cropped_image ? '/image/profile/' . $employee->cropped_image : '/image/projectimage/DefaultUser.png');
        });

        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employees' => $employees,
            'search' => $searchTerm,
            'boolean' => !boolval($isActive)
        ]);
    }



    public function create()
    {
        $pension = Pension::all();
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesStoreAndUpdate', ['pensions' => $pension]);
    }

    public function store(CreateManagementEmployees $request)
    {
        DB::beginTransaction();
        try {
            $imageUrl = null;
            $documentName = null;
            if ($request->hasFile('curriculum_vitae')) {
                $document = $request->file('curriculum_vitae');
                $documentName = time() . '._' . $document->getClientOriginalName();
                $document->move(public_path('documents/curriculum_vitae/'), $documentName);
            }

            // Procesar y almacenar la imagen
            if ($request->hasFile('cropped_image')) {
                $croppedImage = $request->file('cropped_image');
                $imageUrl = time() . '._' . $croppedImage->getClientOriginalName();
                $croppedImage->move(public_path('image/profile/'), $imageUrl);
            }

            $employee = Employee::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'cropped_image' => $imageUrl,
                'gender' => $request->gender,
                'state_civil' => $request->state_civil,
                'birthdate' => $request->birthdate,
                'dni' => $request->dni,
                'email' => $request->email,
                'email_company' => $request->email_company,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
            ]);

            $employeeId = $employee->id;

            Contract::create([
                'basic_salary' => $request->basic_salary,
                'life_ley' => $request->life_ley,
                'discount_remuneration' => $request->discount_remuneration,
                'discount_sctr' => $request->discount_sctr,
                'hire_date' => $request->hire_date,
                'employee_id' => $employeeId,
                'pension_id' => $request->pension_system,
            ]);

            Education::create([
                'education_level' => $request->education_level,
                'education_status' => $request->education_status,
                'specialization' => $request->specialization,
                'curriculum_vitae' => $documentName,
                'employee_id' => $employeeId,
            ]);

            Address::create([
                'street_address' => $request->street_address,
                'department' => $request->department,
                'province' => $request->province,
                'district' => $request->district,
                'employee_id' => $employeeId,
            ]);

            if ($request->filled('emergencyContacts')) {
                foreach ($request->emergencyContacts as $emergency) {
                    Emergency::create([
                        'emergency_name' => $emergency['emergency_name'],
                        'emergency_lastname' => $emergency['emergency_lastname'],
                        'emergency_relations' => $emergency['emergency_relations'],
                        'emergency_phone' => $emergency['emergency_phone'],
                        'employee_id' => $employeeId,
                    ]);
                }
            };

            if ($request->filled('familyDependents')) {
                foreach ($request->familyDependents as $dependent) {
                    Family::create([
                        'family_dni' => $dependent['family_dni'],
                        'family_education' => $dependent['family_education'],
                        'family_relation' => $dependent['family_relation'],
                        'family_name' => $dependent['family_name'],
                        'family_lastname' => $dependent['family_lastname'],
                        'employee_id' => $employeeId,
                    ]);
                }
            };

            Health::create([
                'blood_group' => $request->blood_group,
                'weight' => $request->weight,
                'height' => $request->height,
                'shoe_size' => $request->shoe_size,
                'shirt_size' => $request->shirt_size,
                'pants_size' => $request->pants_size,
                'medical_condition' => $request->medical_condition,
                'allergies' => $request->allergies,
                'operations' => $request->operations,
                'accidents' => $request->accidents,
                'vaccinations' => $request->vaccinations,
                'employee_id' => $employeeId,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $employeesedit = Employee::with('contract', 'contract.pension', 'education', 'address', 'emergency', 'family', 'health')->find($id);
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesStoreAndUpdate', ['employees' => $employeesedit, 'pensions' => Pension::all()]);
    }

    public function update(UpdateManagementEmployees $request, $id)
    {
        $employee = Employee::with('education')->findOrFail($id);
        $imageUrl = $employee->cropped_image;
        $documentName = $employee->education->curriculum_vitae;

        if ($request->hasFile('curriculum_vitae')) {
            $document = $request->file('curriculum_vitae');
            $documentName = $employee->education->curriculum_vitae ? $employee->education->curriculum_vitae : time() . '._' . $document->getClientOriginalName();
            $document->move(public_path('documents/curriculum_vitae/'), $documentName);
        }

        if ($request->hasFile('cropped_image')) {
            $croppedImage = $request->file('cropped_image');
            $imageUrl = $employee->cropped_image ? $employee->cropped_image : time() . '._' . $croppedImage->getClientOriginalName();
            $croppedImage->move(public_path('image/profile/'), $imageUrl);
        }

        $employee->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'cropped_image' => $imageUrl,
            'gender' => $request->gender,
            'state_civil' => $request->state_civil,
            'birthdate' => $request->birthdate,
            'dni' => $request->dni,
            'email' => $request->email,
            'email_company' => $request->email_company,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
        ]);

        $employee->contract->update([
            'basic_salary' => $request->basic_salary,
            'life_ley' => $request->life_ley,
            'discount_remuneration' => $request->discount_remuneration,
            'discount_sctr' => $request->discount_sctr,
            'hire_date' => $request->hire_date,
            'pension_id' => $request->pension_system,
        ]);

        $employee->education->update([
            'education_level' => $request->education_level,
            'education_status' => $request->education_status,
            'specialization' => $request->specialization,
            'curriculum_vitae' => $documentName,
        ]);

        $employee->address->update([
            'street_address' => $request->street_address,
            'department' => $request->department,
            'province' => $request->province,
            'district' => $request->district,
        ]);

        if ($request->filled('emergencyContacts')) {
            Emergency::where('employee_id', $employee->id)->delete();
            foreach ($request->emergencyContacts as $emergency) {
                Emergency::create(
                    [
                        'emergency_name' => $emergency['emergency_name'],
                        'emergency_lastname' => $emergency['emergency_lastname'],
                        'emergency_relations' => $emergency['emergency_relations'],
                        'emergency_phone' => $emergency['emergency_phone'],
                        'employee_id' => $employee->id,
                    ]
                );
            }
        }

        if ($request->filled('familyDependents')) {
            Family::where('employee_id', $employee->id)->delete();
            foreach ($request->familyDependents as $dependent) {
                Family::create(
                    [
                        'family_dni' => $dependent['family_dni'],
                        'family_education' => $dependent['family_education'],
                        'family_relation' => $dependent['family_relation'],
                        'family_name' => $dependent['family_name'],
                        'family_lastname' => $dependent['family_lastname'],
                        'employee_id' => $employee->id,
                    ]
                );
            }
        }

        $employee->health->update([
            'blood_group' => $request->blood_group,
            'weight' => $request->weight,
            'height' => $request->height,
            'shoe_size' => $request->shoe_size,
            'shirt_size' => $request->shirt_size,
            'pants_size' => $request->pants_size,
            'medical_condition' => $request->medical_condition,
            'allergies' => $request->allergies,
            'operations' => $request->operations,
            'accidents' => $request->accidents,
            'vaccinations' => $request->vaccinations,
        ]);
        return to_route('management.employees');
        // DB::beginTransaction();
        // try {


        //     DB::commit();
        //     return to_route('management.employees');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return $e->getMessage();
        // }
    }

    public function destroy($id)
    {
        $education = Education::where('employee_id', $id)->first();
        $employee = Employee::find($id);
        if ($employee->cropped_image != null) {
            $filePath = "image/profile/{$employee->cropped_image}";
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
            } else {
                abort(404, 'Imagen no encontrada');
            }
        }
        if ($education->curriculum_vitae != null) {
            $filePath = "documents/curriculum_vitae/{$education->curriculum_vitae}";
            $path = public_path($filePath);

            if (file_exists($path)) {
                unlink($path);
            } else {
                abort(404, 'Documento no encontrado');
            }
        }

        Employee::destroy($id);
        return to_route('management.employees');
    }

    public function fired(FiredContractEmployees $request, $id)
    {
        $validateData = $request->validated();
        $contract = Contract::where('employee_id', $id)->first();
        $contract->update($validateData);
    }

    public function details($id)
    {
        $details = Employee::with('contract', 'contract.pension', 'education', 'address', 'emergency', 'family', 'health')->find($id);
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesDetails', ['details' => $details]);
    }

    public function download(Education $id)
    {
        $filename = $id->curriculum_vitae;
        $filePath = 'documents/curriculum_vitae/' . $filename;
        $path = public_path($filePath);

        if (file_exists($path)) {
            return response()->download($filePath, $filename);
        }
        abort(404);
    }

    public function reentry(Request $request, $id)
    {
        $reentry = Contract::find($id);
        $reentry->update([
            'hire_date' => $request->reentry_date,
            'fired_date' => null,
            'days_taken' => 0,
            'state' => 'Active'
        ]);
    }
}
