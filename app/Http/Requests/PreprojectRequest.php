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
        return [
            'customer_id' => 'required',
            'subcustomer_id' => 'required',
            'code' => 'required',
            'description' => 'required',
            'date' => 'required',
            'observation' => 'nullable',
            'contacts' => 'required|array',
        ];
    }
}
