<?php

namespace App\Http\Requests\HumanResource;

use App\Models\Document;
use App\Models\DocumentRegister;
use App\Models\Employee;
use App\Models\ExternalEmployee;
use Illuminate\Foundation\Http\FormRequest;

class DocumentCreateRequest extends FormRequest
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
        $rules = [
            'document' => 'required',
            'subdivision_id' => 'required|numeric',
            'employeeType' => 'required',
            'has_exp_date' => 'required',
        ];
        
        
        if ($this->input('employeeType')){
            $rules['employee_id'] = ['required', function ($attribute, $value, $fail) {
                 $cant = Document::where('subdivision_id',$this->input('subdivision_id'))
                 ->where('employee_id', $value)->count();
                 if ($cant===1) {
                    $fail('Este colaborador ya tiene un documento en esta subdivisión');
                 }
                 if ($cant > 1) {
                    $fail('FATAL ERROR NOOOO');
                 }
            }];
        } else {
            $rules['e_employee_id'] = ['required', function ($attribute, $value, $fail) {
                $cant = Document::where('subdivision_id',$this->input('subdivision_id'))
                 ->where('e_employee_id', $value)->count();
                 if ($cant===1) {
                    $fail('Este colaborador ya tiene un documento en esta subdivisión');
                 }
                 if ($cant > 1) {
                    $fail('FATAL ERROR NOOOO');
                 }
            }];
        }
        if ($this->input('has_exp_date')){
            $rules['exp_date'] = 'required';
        }
        return $rules;
    }
}
