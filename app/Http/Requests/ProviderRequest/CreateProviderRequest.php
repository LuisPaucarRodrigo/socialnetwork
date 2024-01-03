<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class CreateProviderRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone1' => 'required|numeric|digits:9',
            'phone2' => 'nullable|numeric|digits:9',
            'email' => 'required|email|max:255',
            'category' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
        ];
    }
}
