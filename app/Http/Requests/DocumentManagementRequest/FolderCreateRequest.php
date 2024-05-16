<?php

namespace App\Http\Requests\DocumentManagementRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Folder;

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
            'name' => [
                'required',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚ\-_]+$/',
                function ($attribute, $value, $fail){
                    $name = $this->input('name');
                    $currentPath = $this->input('currentPath');
                    $folder = Folder::where('name', $name)->where('path', $currentPath.'/'.$name)->first();
                    if ($folder){
                        $fail(_('Ya existe una carpeta con ese nombre'));
                    }
                }  
            ],
            'type' => 'required',
            'areas' => 'required',
            'user_id' => 'required',
            'currentPath' => 'required'
        ];

        if ($this->input('type') === 'Archivos') {
            $rules['archive_type'] = 'required';
        }

        return $rules;
    }


    public function messages(): array
    {
        return [
            'name.regex' => 'El nombre solo puede contener letras mayúsculas, minúsculas, números, guiones, subguiones y sin espacios.',
        ];
    }
}
