<?php

namespace App\Http\Requests\SocialNetwork;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\SNSotOperation;

class SotOperationUpdateRequest extends FormRequest
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
            'sot_id' => 'required',
            'i_state' => 'required',
            'additionals' => 'required',
            'photo_report' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Obtener el estado actual del photo_report del modelo
                    $sotOperation = SNSotOperation::find($this->input('id'));

                    if ($sotOperation && $sotOperation->photo_report === 'Completo' && $value !== 'Completo') {
                        $fail('El estado del reporte ya está completado y no se puede modificar.');
                    }
                }
            ],
            'ic_date' => 'required',
            'bill_amount' => 'required'
        ];
    }
}
