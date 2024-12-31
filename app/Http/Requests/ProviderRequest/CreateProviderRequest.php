<?php

namespace App\Http\Requests\ProviderRequest;

use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'phone1' => [
                'nullable',
                'numeric',
                'digits:9',
                //Rule::unique('providers', 'phone1')
            ],
            'phone2' => [
                'nullable',
                'numeric',
                'digits:9',
                //Rule::unique('providers', 'phone2')
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                //Rule::unique('providers', 'email')
            ],
            'category_id' => 'required|numeric',
            'segments' => 'required|array',
            'zone' => 'required|string',
            'ruc' => [
                'required',
                'string',
                'size:11',
                Rule::unique('providers', 'ruc')
            ],
        ];
    }
}
