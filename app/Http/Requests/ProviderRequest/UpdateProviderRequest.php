<?php

namespace App\Http\Requests\ProviderRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProviderRequest extends FormRequest
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

        $providerId = $this->input('id');

        return [
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone1' => [
                'nullable',
                'numeric',
                'digits:9',
                //Rule::unique('providers', 'phone1')->ignore($providerId)
            ],
            'phone2' => [
                'nullable',
                'numeric',
                'digits:9',
                //Rule::unique('providers', 'phone2')->ignore($providerId)
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                //Rule::unique('providers', 'email')->ignore($providerId)
            ],
            'category_id' => 'required|numeric',
            'segments' => 'required|array',
            'zone' => 'required|string',
            'account_number' => 'required|string',
            'ruc' => [
                'required',
                'string',
                'size:11',
                Rule::unique('providers', 'ruc')->ignore($providerId)
            ],
        ];
    }
}
