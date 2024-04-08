<?php

namespace App\Http\Requests\SpecialInventoryRequest;

use App\Models\ProjectEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SpecialInventoryOutputRequest extends FormRequest
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
        $entry = ProjectEntry::find($this->input('project_entry_id'));
        return [
            "project_entry_id" => "required",
            "quantity" => [
                "required",
                "lte:" . $entry->remaining_quantity,
            ],
        ];
    }

    public function messages()
    {
        $entry = ProjectEntry::find($this->input('project_entry_id'));
        return [
            "quantity.lte" => "La cantidad debe ser menor o igual que " . $entry->remaining_quantity,
        ];
    }


}
