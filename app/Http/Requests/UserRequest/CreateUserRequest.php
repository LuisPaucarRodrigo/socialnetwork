<?php

namespace App\Http\Requests\UserRequest;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

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
            'dni' => 'required|string|max:8|unique:'.User::class,
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'platform' => 'required|string|in:Movil,Web,Ambos',
            'rol' => 'required|numeric',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
