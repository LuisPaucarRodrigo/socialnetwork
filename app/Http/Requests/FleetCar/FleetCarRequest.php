<?php 

namespace App\Http\Requests\FleetCar;

use Illuminate\Foundation\Http\FormRequest;

class FleetCarRequest extends FormRequest
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
            'brand' => 'required',
            'model' => 'required',
            'plate' => 'required',
            'year' => 'required',
            'type' => 'required',
            'photo' => 'nullable',
            'user_id' => 'required',
            'cost_line_id' => 'required',
        ];
    }
}