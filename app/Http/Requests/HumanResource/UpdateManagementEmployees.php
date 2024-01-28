<?php

namespace App\Http\Requests\HumanResource;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class UpdateManagementEmployees extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|in:Masculino,Femenino',
            'state_civil' => 'required|string|in:Casado(a),Soltero(a),Viudo(a),Divorciado(a),Conviviente',
            'birthdate' => 'required|date',
            'dni' => 'required|numeric|digits:8',
            'email' => 'required|email|max:255',
            'phone1' => 'required|numeric|digits:9',
            'phone2' => 'nullable|numeric|digits:9',
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
        ];
    }
}