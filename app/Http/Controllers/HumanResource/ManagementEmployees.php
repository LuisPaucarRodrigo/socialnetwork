<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Contract;
use App\Models\Education;
use App\Models\Emergency;
use App\Models\Employee;
use App\Models\Family;
use App\Models\Health;
use App\Models\Pension;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Inertia\Inertia;

class ManagementEmployees extends Controller
{
    public function index()
    {
        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employees' => Employee::paginate()
        ]);
    }

    public function index_info_additional()
    {   
        $pension = Pension::all();
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesInformation',['pensions' => $pension]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'curriculum_vitae' => 'required|mimes:pdf,doc,docx|max:2048',
            'cropped_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|in:Masculino,Femenino',
            'state_civil' => 'required|string|in:Casado(a),Soltero(a),Viudo(a),Divorciado(a),Conviviente',
            'birthdate' => 'required|date',
            'dni' => 'required|numeric|digits:8',
            'email' => 'required|email|max:255',
            'phone1' => 'required|numeric|digits:9',
            'phone2' => 'nullable|numeric|digits:9',
            'pension_system' => 'required|string',
            'basic_salary' => 'required|numeric',
            'hire_date' => 'required|date',
            'education_level' => 'required|string|in:Universidad,Instituto,Otros',
            'education_status' => 'required|string|in:Culminado,En Progres',
            'specialization' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'emergency_name' => 'required|string|max:255',
            'emergency_lastname' => 'required|string|max:255',
            'emergency_relations' => 'required|string|max:255',
            'emergency_phone' => 'required|numeric|digits:9',
            'familyDependents.*.family_dni' => 'required|numeric|digits:8',
            'familyDependents.*.family_education' => 'required|string|in:Universidad,Instituto,Otros',
            'familyDependents.*.family_relation' => 'required|string|max:255',
            'familyDependents.*.family_name' => 'required|string|max:255',
            'familyDependents.*.family_lastname' => 'required|string|max:255',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB-,AB+,0+,0-',
            'weight' => 'required|string',
            'height' => 'required|string',
            'shoe_size' => 'required|string|max:10',
            'shirt_size' => 'required|string|max:10',
            'pants_size' => 'required|string|max:10',
            'medical_condition' => 'nullable|string|max:255',
            'allergies' => 'nullable|string|max:255',
            'operations' => 'nullable|string|max:255',
            'accidents' => 'nullable|string|max:255',
            'vaccinations' => 'nullable|string|max:255',
        ]);
        $document = $request->file('curriculum_vitae'); // Obtener el objeto de archivo
        $documentName = time() . '.' . $document->getClientOriginalName(); // Generar un nombre único para el archivo
        $document->storeAs('documents', $documentName); // Guardar el archivo en storage/app/documents   

        $croppedImage = $request->file('cropped_image');
        $imageName = 'imagen_' . time() . '.' . $croppedImage->getClientOriginalExtension();
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
            'postal_code' => $request->postal_code,
            'employee_id' => $employeeId,
        ]);

        Emergency::create([
            'emergency_name' => $request->emergency_name,
            'emergency_lastname' => $request->emergency_lastname,
            'emergency_relations' => $request->emergency_relations,
            'emergency_phone' => $request->emergency_phone,
            'employee_id' => $employeeId,
        ]);

        if ($request->filled('family')) {
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
        }

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

        return to_route('management.employees');
    }

    public function edit($id)
    {   
        $employeesedit = Employee::with('contract', 'contract.pension', 'education', 'address', 'emergency', 'family', 'health')->find($id);
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesInformation',['employeesedit' => $employeesedit]);
    }

    public function destroy($id)
    {
        Employee::destroy($id);
        return to_route('management.employees');
    }

    public function details($id)
    {
        $details = Employee::with('contract', 'contract.pension', 'education', 'address', 'emergency', 'family', 'health')->find($id);
        //dd($details);
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesInformation', ['details' => $details]);
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
