<?php

namespace App\Http\Requests\HumanResource;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Document;
use App\Models\DocumentRegister;
use Carbon\Carbon;

class DocumentUpdateRequest extends FormRequest
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
        $docItem = Document::find($this->route('id'));
        $rules = [
            'document' => 'required|file|mimes:png,pdf,jpeg,jpg',
            'subdivision_id' => 'required|numeric',
            'employeeType' => 'required',
            'e_employee_id' => 'nullable',
            'employee_id' => 'nullable',
            'has_exp_date' => ['required'],
        ];




        if ($this->input('employeeType')) {
            $rules['employee_id'] = [
                'required',
                function ($attribute, $value, $fail) use ($docItem) {
                    $it = Document::where('subdivision_id', $this->input('subdivision_id'))
                        ->where('employee_id', $value)->get();
                    if ($this->input('subdivision_id') != $docItem->subdivision_id || $this->input('employee_id') != $docItem->employee_id) {
                        if ($it->count() === 1) {
                            $fail('Este colaborador ya tiene un documento en esta subdivisión');
                        }
                        if ($it->count() > 1) {
                            $fail('FATAL ERROR NOOOO');
                        }
                    }


                }
            ];
        } else {
            $rules['e_employee_id'] = [
                'required',
                function ($attribute, $value, $fail) use ($docItem) {
                    $cant = Document::where('subdivision_id', $this->input('subdivision_id'))
                        ->where('e_employee_id', $value)->count();
                    if ($this->input('subdivision_id') != $docItem->subdivision_id || $this->input('e_employee_id') != $docItem->e_employee_id) {
                        if ($cant === 1) {
                            $fail('Este colaborador ya tiene un documento en esta subdivisión');
                        }
                        if ($cant > 1) {
                            $fail('FATAL ERROR NOOOO');
                        }
                    }
                }
            ];
        }

        $rules['exp_date'] = $this->input('has_exp_date') === '1' ? 'required' : 'nullable';
        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $employeeId = $this->input('employee_id');
            $eEmployeeId = $this->input('e_employee_id');
            if (empty($employeeId) && empty($eEmployeeId)) {
                $validator->errors()->add('employee_id', 'Debes completar uno de los dos campos: employee_id o e_employee_id.');
            }
            if (!empty($employeeId) && !empty($eEmployeeId)) {
                $validator->errors()->add('employee_id', 'Solo uno de los campos employee_id o e_employee_id debe estar completo, no ambos.');
            }
        });
    }
}
