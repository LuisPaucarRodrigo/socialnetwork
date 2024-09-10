<?php

namespace App\Http\Requests\SocialNetwork;

use Illuminate\Foundation\Http\FormRequest;

class CreateAndpdateControl extends FormRequest
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
            'p_bad_installation' => 'nullable|string|in:OK,Penalidad',
            'p_no_rf' => 'nullable|string|in:OK,Penalidad',
            'p_rejections' => 'nullable|string|in:OK,Penalidad',
        ];
    }
}
