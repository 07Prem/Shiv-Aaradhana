<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'country' => ['required', 'string', 'max:80'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website_hp' => ['nullable', 'max:0'], // Anti-spam honeypot
        ];
    }

    public function messages(): array
    {
        return [
            'website_hp.max' => 'Spam detection triggered.',
            'message.min' => 'Please provide at least 10 characters detailing your trade inquiry.',
        ];
    }
}
