<?php

namespace App\Http\Requests\SocialNetwork;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SotStoreRequest extends FormRequest
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
        return [
            'user_owner_id' => 'required',
            'user_assignee_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'assigned_date' => 'required',
        ];
    }
}
