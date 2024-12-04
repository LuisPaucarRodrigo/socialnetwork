<?php

namespace App\Http\Requests\HumanResource;

use App\Models\ExternalEmployee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrUpdateEmployeesExternal extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'name' => 'required|string',
            'lastname' => 'required|string',
            'expense_line' => 'required|string',
            'cropped_image' => 'nullable',
            'gender' => 'required|in:Masculino,Femenino',
            'address' => 'required|string',
            'birthdate' => 'required|date',
            'dni' => 'required|numeric|digits:8',
            'phone1' => 'required|numeric|digits:9',
            'salary' => 'required|numeric',
            'sctr' => 'required|boolean',
            'curriculum_vitae' => 'nullable'
        ];
        if ($this->route('external_id')) {
            $rules['email'] = 'required|email';
            $rules['email_company'] = 'nullable|email';
        } else {
            $rules['email'] = 'required|email|unique:' . ExternalEmployee::class;
            $rules['email_company'] = 'nullable|email|unique:' . ExternalEmployee::class;
        }
        return $rules;
    }
}
