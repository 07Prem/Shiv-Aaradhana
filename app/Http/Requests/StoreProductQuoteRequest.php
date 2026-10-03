<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'full_name' => ['required', 'string', 'max:120'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'country' => ['required', 'string', 'max:80'],
            'target_quantity' => ['required', 'string', 'max:80'],
            'packaging_requirements' => ['nullable', 'string', 'max:150'],
            'port_of_destination' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website_hp' => ['nullable', 'max:0'], // Anti-spam honeypot
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'The selected product is not recognized in our export catalog.',
            'target_quantity.required' => 'Please provide an estimated shipment quantity or container count.',
            'website_hp.max' => 'Spam detection triggered.',
        ];
    }
}
