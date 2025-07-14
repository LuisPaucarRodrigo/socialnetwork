<?php

namespace App\Http\Requests\RoomRental;

use Illuminate\Foundation\Http\FormRequest;

class RoomRentalRequest extends FormRequest
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
            'rental_type' => 'required',
            'address' => 'required',
            'observations' => 'nullable',
            'photo' => 'nullable',
            'provider_id' => 'required',
            'cost_line_id' => 'required',
        ];
    }
}
