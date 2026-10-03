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
            'product_id' => ['nullable', 'exists:products,id'],
            'target_quantity' => ['nullable', 'max:100'],
            'items' => ['nullable', 'array'],
            'items.*.product_id' => ['required_with:items', 'exists:products,id'],
            'items.*.quantity' => ['required_with:items', 'max:100'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],
            'full_name' => ['required', 'string', 'max:120'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'country' => ['required', 'string', 'max:80'],
            'packaging_requirements' => ['nullable', 'string', 'max:150'],
            'port_of_destination' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:3000'],
            'website_hp' => ['nullable', 'max:0'], // Anti-spam honeypot
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasItems = ! empty($this->input('items')) && is_array($this->input('items')) && count($this->input('items')) > 0;
            $hasProduct = ! empty($this->input('product_id'));

            if (! $hasItems && ! $hasProduct) {
                $validator->errors()->add('items', 'Please select at least one commodity or add products to your quotation request.');
                $validator->errors()->add('product_id', 'Please select at least one commodity or add products to your quotation request.');
            }

            if ($hasProduct && ! $hasItems && empty($this->input('target_quantity'))) {
                $validator->errors()->add('target_quantity', 'Please specify an estimated shipment volume or container count.');
            }

            if ($hasItems) {
                foreach ($this->input('items') as $idx => $item) {
                    $qty = $item['quantity'] ?? null;
                    if (is_numeric($qty) && (float) $qty <= 0) {
                        $validator->errors()->add("items.{$idx}.quantity", 'Quantity must be greater than zero.');
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'The selected product is not recognized in our export catalog.',
            'items.*.product_id.exists' => 'One or more selected products are invalid or no longer available.',
            'items.*.quantity.required_with' => 'Please specify a target quantity for each product in your quotation list.',
            'target_quantity.required' => 'Please provide an estimated shipment quantity or container count.',
            'website_hp.max' => 'Spam detection triggered.',
        ];
    }
}
