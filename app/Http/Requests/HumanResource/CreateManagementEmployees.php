<?php

namespace App\Http\Requests\HumanResource;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;

class CreateManagementEmployees extends FormRequest
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
            'curriculum_vitae' => 'nullable|mimes:pdf|max:51200',
            'cropped_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|in:Masculino,Femenino',
            'state_civil' => 'required|string|in:Casado(a),Soltero(a),Viudo(a),Divorciado(a),Conviviente',
            'birthdate' => 'required|date|before_or_equal:' . Date::now()->subYears(18)->format('Y-m-d'),
            'dni' => 'required|numeric|digits:8|unique:' . Employee::class,
            'email' => 'required|email|max:255|unique:' . Employee::class,
            'email_company' => 'nullable|email|max:255|unique:' . Employee::class,
            'phone1' => 'required|numeric|digits:9|unique:' . Employee::class,
            'phone2' => 'nullable|numeric|digits:9|unique:' . Employee::class,

            'cost_line_id' => 'required|numeric',
            'type_contract' => 'required|string',
            'state_travel_expenses' => 'required|boolean',
            'amount_travel_expenses' => 'nullable|numeric|required_if:state_travel_expenses,true',

            'discount_remuneration' => 'required|boolean',
            'discount_sctr' => 'required|boolean',
            'pension_type' => 'required|string',
            'basic_salary' => 'required|numeric',
            'life_ley' => 'required|numeric',
            'hire_date' => 'required|date',
            'education_level' => 'required|string|in:Universidad,Instituto,Otros',
            'education_status' => 'required|string|in:Incompleto,Completo,En Progreso',

            'specialization' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',

            'emergencyContacts.*.emergency_name' => 'required|string|max:255',
            'emergencyContacts.*.emergency_lastname' => 'required|string|max:255',
            'emergencyContacts.*.emergency_relations' => 'required|string|max:255',
            'emergencyContacts.*.emergency_phone' => 'required|numeric|digits:9',

            'familyDependents.*.family_dni' => 'nullable|numeric|digits:8',
            'familyDependents.*.family_education' => 'required|string|in:Universidad,Instituto,Secundaria,Primaria,Inicial,Otros',
            'familyDependents.*.family_relation' => 'required|string|max:255',
            'familyDependents.*.family_name' => 'required|string|max:255',
            'familyDependents.*.family_lastname' => 'required|string|max:255',

            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB-,AB+,O+,O-,RH+',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'shoe_size' => 'nullable|numeric',
            'shirt_size' => 'nullable|string',
            'pants_size' => 'nullable|numeric',

            'medical_condition' => 'required|string|max:255',
            'allergies' => 'required|string|max:255',
            'operations' => 'required|string|max:255',
            'accidents' => 'required|string|max:255',
            'vaccinations' => 'required|string|max:255',
        ];
    }
}
