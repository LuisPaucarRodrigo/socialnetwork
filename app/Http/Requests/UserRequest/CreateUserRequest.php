<?php

namespace App\Http\Requests\UserRequest;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'dni' => ['required',
                      'string',
                      'max:8',
                      Rule::unique('users', 'dni')],
            'email' => ['required',
                        'string',
                        'max:255',
                        'email',
                        Rule::unique('users', 'email')],
            'platform' => 'required|string|in:Movil,Web,Ambos',
            'phone' => [
                'required',
                'string',
                Rule::unique('users', 'phone')
            ],
            'rol' => 'required|numeric',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
