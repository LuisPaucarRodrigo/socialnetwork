<?php

namespace App\Http\Requests\PreprojectRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectPextCreateRequest extends FormRequest
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
        return [
            'cost_center_id' => 'required',
            'date' => 'required',
            'contacts' => 'required',
            'cpe' => 'required',
            'services' => 'nullable',
            'employees' => 'required',
        ];
    }
}
