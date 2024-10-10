<?php

namespace App\Http\Requests\Huawei;

use Illuminate\Foundation\Http\FormRequest;

class QuickMaterialsOutputRequest extends FormRequest
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
            'output_date' => 'nullable',
            'quantity' => 'nullable',
            'employee' => 'nullable',
            'observation' => 'nullable',
            'huawei_project_id' => 'nullable'
        ];
    }
}
