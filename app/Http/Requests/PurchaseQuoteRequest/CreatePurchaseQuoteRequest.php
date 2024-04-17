<?php

namespace App\Http\Requests\PurchaseQuoteRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Purchasing_request;
use Illuminate\Support\Facades\Auth;

class CreatePurchaseQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $purchasing_request = Purchasing_request::with('preproject')
                                ->find($this->input('purchasing_request_id'));
        if ($purchasing_request->preproject){
            return !$purchasing_request->preproject->has_quote;
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
        $purchasing_request = Purchasing_request::find($this->input('purchasing_request_id'));
        if ($purchasing_request) {
            $isPermitted = $purchasing_request ->checkQuotesProductsQuantity(
                $this->input('products')
            );
        } else {
            dd("a vre que paso");
        }

        $rules = [
            'quote_deadline' => 'required|date|before_or_equal:due_date',
            'due_date' => 'nullable|date',
            'purchase_doc' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'igv' => 'required',
            'igv_percentage' => 'required',
            'deliverable_time' => 'required',
            'payment_type' => 'required|string',
            'account_number' => 'required',
            'provider_id' => 'required',
            'currency' => 'required',
            'purchasing_request_id' => 'required|numeric',
            'products' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($isPermitted) {
                    if (!$isPermitted) {
                        $fail(__('Uno o más productos exceden la cantidad disponible, disminuya la cantidad o rechace alguna de las otras cotizaciones hechas para esta solicitud'));
                    }
                },
                function ($attribute, $value, $fail) {
                    $allZeroQuantity = true;
                    foreach ($value as $product) {
                        if ($product['quantity'] !== 0) {
                            $allZeroQuantity = false;
                            break;
                        }
                    }
                    if ($allZeroQuantity) {
                        $fail(__('Cantidad Inválida de uno o más productos, se recomienda rechazar alguna de las cotizaciones hechas para esta solicitud'));
                    }
                }
            ],
        ];
        return $rules;
    }

}

