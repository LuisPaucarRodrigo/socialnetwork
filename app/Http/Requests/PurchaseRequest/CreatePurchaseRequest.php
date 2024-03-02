<?php

namespace App\Http\Requests\PurchaseRequest;

use App\Models\Purchasing_request;
use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string',
            'due_date' => 'required|date',
            'products' => 'required|array',
        ];
        if (!is_null($this->input('project_id'))) {
            $rules['project_id'] = 'required';
        }
        return $rules;
    }
}
