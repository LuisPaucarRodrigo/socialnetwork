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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ManagementEmployees extends Controller
{
    public function index(){
        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employees' => Employee::paginate()
        ]);
    }

    public function index_info_additional(){
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesInformation', [
            'users' => User::all()
        ]);
    }

    public function create(Request $request){
        $request->validate([
            'id' => 'required',
            'cropped_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Masculino,Femenino',
            'state_civil' => 'required|string|in:Casado(a),Soltero(a),Viudo(a),Divorciado(a),Conviviente',
            'birthdate' => 'required|date',
            'dni' => 'required|numeric|digits:8',
            'email' => 'required|email|max:255',
            'phone1' => 'required|numeric|digits:9',
            'phone2' => 'nullable|numeric|digits:9',
            'pension_system' => 'required|string|in:ONP,INTEGRA,HABITAT,PROFUTURO,PRIMA,INTEGRAMX,HABITATMX,PROFUTUROMX,PRIMAMX',
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
            'family_dni' => 'required|numeric|digits:8',
            'family_education' => 'required|string|in:Universidad,Instituto,Otros',
            'family_relation' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'family_lastname' => 'required|string|max:255',
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
        $croppedImage = $request->file('cropped_image');
        $imageName = 'imagen_recortada_' . time() . '.' . $croppedImage->getClientOriginalExtension();
        $croppedImage->move(public_path('image'), $imageName);
        $imageUrl = url('image/' . $imageName);

        $employee = Employee::updateOrCreate([
            'name' => $request->name,
            'lastname' => $request->last_name,
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

        Contract::updateOrCreate([
            'pension_system' => $request->pension_system,
            'basic_salary' => $request->basic_salary,
            'hire_date' => $request->hire_date,
            'employee_id' => $employeeId,
        ]);

        Education::updateOrCreate([
            'education_level' => $request->education_level,
            'education_status' => $request->education_status,
            'specialization' => $request->specialization,
            'employee_id' => $employeeId,
        ]);

        Address::updateOrCreate([
            'street_address' => $request->street_address,
            'department' => $request->department,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'employee_id' => $employeeId,
        ]);

        Emergency::updateOrCreate([
            'emergency_name' => $request->emergency_name,
            'emergency_lastname' => $request->emergency_lastname,
            'emergency_relations' => $request->emergency_relations,
            'emergency_phone' => $request->emergency_phone,
            'employee_id' => $employeeId,
        ]);

        Family::updateOrCreate([
            'family_dni' => $request->family_dni,
            'family_education' => $request->family_education,
            'family_relation' => $request->family_relation,
            'family_name' => $request->family_name,
            'family_lastname' => $request->family_lastname,
            'employee_id' => $employeeId,
        ]);

        Health::updateOrCreate([
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

        return Inertia::render('HumanResource/ManagementEmployees/Employees', [
            'employees' => Employee::paginate()
        ]);
        
    }

    public function details($id){
        // dd('preuba');
        return Inertia::render('HumanResource/ManagementEmployees/EmployeesDetails');
    }
}
