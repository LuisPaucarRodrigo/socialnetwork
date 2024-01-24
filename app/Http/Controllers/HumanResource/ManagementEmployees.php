<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
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
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ManagementEmployees extends Controller
{
    public function index()
    {
        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employees' => Employee::whereHas('contract', function ($query) {
                $query->where('state', 'active');
            })->paginate()
        ]);
    }

    public function index_info_additional()
    {
        $pension = Pension::all();
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesStoreAndUpdate', ['pensions' => $pension]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'curriculum_vitae' => 'required|mimes:pdf|max:2048',
            'cropped_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|in:Masculino,Femenino',
            'state_civil' => 'required|string|in:Casado(a),Soltero(a),Viudo(a),Divorciado(a),Conviviente',
            'birthdate' => 'required|date',
            'dni' => 'required|numeric|digits:8|unique:' . Employee::class,
            'email' => 'required|email|max:255|unique:' . Employee::class,
            'phone1' => 'required|numeric|digits:9|unique:' . Employee::class,
            'phone2' => 'nullable|numeric|digits:9|unique:' . Employee::class,
            'maternity_subsidy' => 'nullable',
            'pension_system' => 'required|numeric',
            'basic_salary' => 'required|numeric',
            'hire_date' => 'required|date',
            'education_level' => 'required|string|in:Universidad,Instituto,Otros',
            'education_status' => 'required|string|in:Culminado,En Progreso',
            'specialization' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'emergencyContacts.*.emergency_name' => 'required|string|max:255',
            'emergencyContacts.*.emergency_lastname' => 'required|string|max:255',
            'emergencyContacts.*.emergency_relations' => 'required|string|max:255',
            'emergencyContacts.*.emergency_phone' => 'required|numeric|digits:9',

            'familyDependents.*.family_dni' => 'required|numeric|digits:8',
            'familyDependents.*.family_education' => 'required|string|in:Universidad,Instituto,Otros',
            'familyDependents.*.family_relation' => 'required|string|max:255',
            'familyDependents.*.family_name' => 'required|string|max:255',
            'familyDependents.*.family_lastname' => 'required|string|max:255',

            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB-,AB+,O+,O-',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'shoe_size' => 'required|numeric',
            'shirt_size' => 'required|string',
            'pants_size' => 'required|numeric',
            'medical_condition' => 'required|string|max:255',
            'allergies' => 'required|string|max:255',
            'operations' => 'required|string|max:255',
            'accidents' => 'required|string|max:255',
            'vaccinations' => 'required|string|max:255',
        ]);
        DB::beginTransaction();
        try {
            $document = $request->file('curriculum_vitae');
            $documentName = time() . '. ' . $document->getClientOriginalName();
            $document->storeAs('documents', $documentName);

            $croppedImage = $request->file('cropped_image');
            $imageName = 'imagen_' . time() . '. ' . $croppedImage->getClientOriginalExtension();
            $croppedImage->move(public_path('image'), $imageName);
            $imageUrl = url('image/' . $imageName);

            $employee = Employee::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'cropped_image' => $imageUrl,
                'gender' => $request->gender,
                'state_civil' => $request->state_civil,
                'birthdate' => $request->birthdate,
                'dni' => $request->dni,
                'email' => $request->email,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
            ]);

            $employeeId = $employee->id;

            Contract::create([
                'maternity_subsidy' => $request->maternity_subsidy,
                'basic_salary' => $request->basic_salary,
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
                'address' => $request->address,
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
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            Log::error($errorMessage);
        }
    }

    public function edit($id)
    {
        $employeesedit = Employee::with('contract', 'contract.pension', 'education', 'address', 'emergency', 'family', 'health')->find($id);
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesStoreAndUpdate', ['employees' => $employeesedit, 'pensions' => Pension::all()]);
    }

    public function update(UpdateManagementEmployees $request, $id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $employee->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'gender' => $request->gender,
                'state_civil' => $request->state_civil,
                'birthdate' => $request->birthdate,
                'dni' => $request->dni,
                'email' => $request->email,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
            ]);

            $employee->contract->update([
                'maternity_subsidy' => $request->maternity_subsidy,
                'basic_salary' => $request->basic_salary,
                'hire_date' => $request->hire_date,
                'pension_id' => $request->pension_system,
            ]);

            $employee->education->update([
                'education_level' => $request->education_level,
                'education_status' => $request->education_status,
                'specialization' => $request->specialization,
            ]);

            $employee->address->update([
                'street_address' => $request->street_address,
                'department' => $request->department,
                'province' => $request->province,
                'address' => $request->address,
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
            DB::commit();
            return to_route('management.employees');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            Log::error($errorMessage) ;
        }
    }

    public function destroy($id)
    {
        $education = Education::where('employee_id', $id)->first();
        $filePath = storage_path("app/documents/$education->curriculum_vitae");
        if (file_exists($filePath)) {
            Storage::delete("documents/$education->curriculum_vitae");
            Employee::destroy($id);
        } else {
            dd("El archivo no existe en la ruta: $filePath");
        }
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

    public function download($filename)
    {
        $filePath = '/documents/' . $filename;
        if (Storage::disk('local')->exists($filePath)) {
            $file = Storage::disk('local')->get($filePath);
            $response = new Response($file, 200);
            $response->header('Content-Type', 'application/pdf');
            $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            return $response;
        }
        abort(404);
    }
}
