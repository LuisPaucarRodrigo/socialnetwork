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
            'document' => 'required',
            'subdivision_id' => 'required|numeric',
            'employeeType' => 'required',
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


        $docReg = $this->input('employee_id') ? DocumentRegister::where('subdivision_id', $this->input('subdivision_id'))
            ->where('employee_id', $this->input('employee_id'))->first() :
            ($this->input('e_employee_id') ? DocumentRegister::where('subdivision_id', $this->input('subdivision_id'))
                ->where('employee_id', $this->input('e_employee_id'))->first() : null);

        if ($docReg?->exp_date) {
            array_push(
                $rules['has_exp_date'],
                function ($attribute, $value, $fail) use ($docReg) {
                    $expDateInput = Carbon::parse($this->input('exp_date'));
                    $docRegExpDate = Carbon::parse($docReg?->exp_date);
                    if ($expDateInput->lessThan($docRegExpDate)) {
                        $fail('La fecha de vencimiento debe ser igual o mayor a ' . $docRegExpDate->format('d/m/Y'));
                    }
                }
            );
        }



        if ($this->input('has_exp_date')) {
            $rules['exp_date'] = 'required';
        }
        return $rules;
    }
}
