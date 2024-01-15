<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class MobileDeviceRequest extends FormRequest
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
        $baseRules = [
            'phone_brand' => 'required|max:255',
            'model' => 'required|max:255',
            'serie_number' => 'required|max:255|unique:mobile_devices',
            'state' => 'required',
            'adquisition_date' => 'required|date',
            'price' => 'required|numeric',
        ];
        if ($this->isMethod('put')) {
            unset($baseRules['serie_number']);
        }

        return $baseRules;
    }
    public function messages()
    {
        return [
            'phone_brand.required' => 'El campo marca es obligatorio.',
            'phone_brand.max' => 'El campo nombre no puede tener más de :max caracteres.',

            'model.required' => 'El campo modelo es obligatorio.',
            'model.max' => 'El campo modelo no puede tener más de :max caracteres.',

            'serie_number.required' => 'El campo número de serie es obligatorio.',
            'serie_number.max' => 'El campo número de serie no puede tener más de :max caracteres.',
            'serie_number.unique' => 'El número de serie ya está en uso. Elija otro.',

            'state.required' => 'El campo estado es obligatorio.',

            'adquisition_date.required' => 'El campo fecha de adquisición es obligatorio.',
            'adquisition_date.date' => 'El campo fecha de adquisición debe ser una fecha válida.',

            'price.required' => 'El campo precio es obligatorio.',
            'price.numeric' => 'El campo precio debe ser un valor numérico.',
        ];
    }
}
