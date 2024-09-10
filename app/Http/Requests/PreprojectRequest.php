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

        if ($this->input('customer_id') === 1) {
            $rules['reportStages'] = 'nullable|array';
            $rules['reportStages.*.name'] = 'nullable|string|max:255';
            $rules['reportStages.*.title_id'] = 'nullable|numeric|max:255';
            if ($this->route('preproject')) {
                $rules['reportStages'] = 'nullable';
            }
        } else {
            $rules['reportStages'] = 'required|array';
            $rules['reportStages.*.name'] = 'required|string|max:255';
            $rules['reportStages.*.title_id'] = 'required|numeric|max:255';
            if ($this->route('preproject')) {
                $rules['reportStages'] = 'nullable';
            }
        }
        return $rules;
    }
}
