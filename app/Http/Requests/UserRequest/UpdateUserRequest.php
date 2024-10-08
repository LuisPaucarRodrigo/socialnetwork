<?php

namespace App\Http\Requests\UserRequest;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->input('user_id');

        $rules = [
            'name' =>  [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore($userId)
            ],
            'dni' => [
                'required',
                'string',
                'max:8',
                Rule::unique('users', 'dni')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'platform' => 'required|string|in:Movil,Web,Web/Movil',
            'phone' => [
                'required',
                'string',
                Rule::unique('users', 'phone')->ignore($userId)
            ],
            'role_id' => 'nullable|numeric',
        ];

        if ($this->input('platform') !== 'Movil'){
            $rules['area_id'] = 'required';
            $rules['role_id'] = 'required';
        }

        return $rules;
    }
}
