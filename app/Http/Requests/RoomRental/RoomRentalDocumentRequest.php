<?php

namespace App\Http\Requests\RoomRental;

use Illuminate\Foundation\Http\FormRequest;

class RoomRentalDocumentRequest extends FormRequest
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
        $isUpdating = $this->route('carDocument');

        return [
            'archive' => $isUpdating ? 'nullable' : 'required',
            'observations' => 'nullable',
            'expiration_date' => 'required|date',
            'room_id' => 'required|exists:rooms,id',
        ];
    }
}
