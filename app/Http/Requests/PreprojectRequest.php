<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreprojectRequest extends FormRequest
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
            'customer_id' => 'required',
            'code' => 'required',
            'description' => 'required',
            'date' => 'required',
            'observation' => 'nullable',

            'cpe' => 'nullable',
            'contacts' => 'required|array',
        ];
        if ($this->has('hasSubcustomer') && $this->input('hasSubcustomer')) {
            $rules['subcustomer_id'] = 'required';
        } else {
            $rules['subcustomer_id'] = 'nullable';
        }
        if ($this->input('customer_id') !== 1) {
            $rules['title_factibilidad_id'] = 'required';
            $rules['title_implementation_id'] = 'required';
        }
        if ($this->input('preproject')) {
            $rules['title_factibilidad_id'] = 'nullable';
            $rules['title_implementation_id'] = 'nullable';
        }
        return $rules;
    }
}
