<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ComponentAndMaterialRequest extends FormRequest
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
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'quantity' => 'required|numeric',
            'state' => 'required',
            'adquisition_date' => 'required|date',
            'price' => 'required|numeric',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener más de :max caracteres.',

            'description.required' => 'El campo descripcion es obligatorio.',
            'description.max' => 'El campo modelo no puede tener más de :max caracteres.',

            'quantity.required' => 'El campo cantidad de serie es obligatorio.',
            'quantity.max' => 'El campo número de serie no puede tener más de :max caracteres.',
            'quantity.unique' => 'El número de serie ya está en uso. Elija otro.',

            'state.required' => 'El campo estado es obligatorio.',

            'adquisition_date.required' => 'El campo fecha de adquisición es obligatorio.',
            'adquisition_date.date' => 'El campo fecha de adquisición debe ser una fecha válida.',

            'price.required' => 'El campo precio es obligatorio.',
            'price.numeric' => 'El campo precio debe ser un valor numérico.',
        ];
    }
}
