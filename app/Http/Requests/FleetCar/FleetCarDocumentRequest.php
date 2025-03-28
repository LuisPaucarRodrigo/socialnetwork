<?php 

namespace App\Http\Requests\FleetCar;

use Illuminate\Foundation\Http\FormRequest;

class FleetCarDocumentRequest extends FormRequest
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
            'ownership_card' => 'nullable|required_without_all:technical_review,soat,insurance',
            'technical_review' => 'nullable|required_without_all:ownership_card,soat,insurance',
            'soat' => 'nullable|required_without_all:ownership_card,technical_review,insurance',
            'insurance' => 'nullable|required_without_all:ownership_card,technical_review,soat',
            'technical_review_date' => 'nullable|date',
            'soat_date' => 'nullable|date',
            'insurance_date' => 'nullable|date',
            'address_web' => 'required', 
            'user' => 'required', 
            'password' => 'required', 
            'car_id' => 'required'
        ];
    }
}
