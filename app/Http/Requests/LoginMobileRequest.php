<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;

class LoginMobileRequest extends FormRequest
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
            'dni' => ['required','string','max:8'],
            'password' => ['required','string','max:255'],
        ];
    }

    public function hasMobileAccess($user)
    {
        return $user->platform === 'Movil' || $user->platform === 'Web/Movil';
    }

}
