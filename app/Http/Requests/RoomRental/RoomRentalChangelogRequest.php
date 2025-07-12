<?php

namespace App\Http\Requests\RoomRental;

use Illuminate\Foundation\Http\FormRequest;

class RoomRentalChangelogRequest extends FormRequest
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
            'date' => 'required|date',
            'mileage' => 'required|numeric',
            'type' => 'required|string',
            'invoice' => 'required',
            'workshop' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'observation' => 'nullable',
            'items' => 'required|array',
        ];
    }
}
