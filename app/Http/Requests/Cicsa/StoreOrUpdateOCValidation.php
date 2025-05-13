<?php

namespace App\Http\Requests\Cicsa;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateOCValidation extends FormRequest
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
            'validation_date' => 'nullable',
            'materials_control' => 'required',
            'file_validation' => 'required',
            'supervisor' => 'required',
            'warehouse' => 'required',
            'boss' => 'required',
            'liquidator' => 'required',
            'superintendent' => 'required',
            'observations' => 'nullable',
            'user_name' => 'required',
            'user_id' => 'required',
        ];
    }
}
