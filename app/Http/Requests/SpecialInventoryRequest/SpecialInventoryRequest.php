<?php

namespace App\Http\Requests\SpecialInventoryRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SpecialInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if ($this->input('id')){
            return $user->role_id === 1;
        }
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
            "purchase_product_id" => "required",
            "warehouse_id" => "required",
            "cpe" => "required",
            "referral_guide" => "required",
            "entry_date" => "required",
            "sub_warehouse" => "required",
            "quantity" => "required",
            "product_serial_number" => "required",
            "entry_observations" => "nullable",
            "zone" => "required",
        ];
    }
}
