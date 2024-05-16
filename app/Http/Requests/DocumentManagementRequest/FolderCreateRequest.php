<?php

namespace App\Http\Requests\DocumentManagementRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FolderCreateRequest extends FormRequest
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

        $rules = [
            'name' => 'required',
            'type' => 'required',
            'areas' => 'required',
            'user_id' => 'required'
        ];

        if ($this->input('type')=== 'Archivos'){
            $rules['archive_type'] = 'required';
        }

        return $rules;
    }
}