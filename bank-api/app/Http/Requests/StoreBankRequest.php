<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization logic if needed
    }

    public function rules()
    {
        return [
            'bank_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:65535'],
            'phone' => ['required', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'mobile' => ['nullable', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'fax' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                'lowercase',
                //  'email:filter,spoof,strict',
                'unique:banks,email',
            ],
            'website' => ['nullable', 'string', 'max:255', 'url', 'active_url'],
            'created' => ['nullable', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['nullable', 'boolean', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'bank_name.required' => 'The bank name field is required.',
            'bank_name.string' => 'The bank name must be a string.',
            'bank_name.max' => 'The bank name must not exceed 255 characters.',
            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address must not exceed 65535 characters.',
            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone must not exceed 255 characters.',
            'phone.regex' => 'The phone must be a valid phone number.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.string' => 'The mobile must be a string.',
            'mobile.max' => 'The mobile must not exceed 255 characters.',
            'fax.required' => 'The fax field is required.',
            'fax.string' => 'The fax must be a string.',
            'fax.max' => 'The fax must not exceed 255 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'email.lowercase' => 'The email must be in lowercase.',
            'website.required' => 'The website field is required.',
            'website.string' => 'The website must be a string.',
            'website.max' => 'The website must not exceed 255 characters.',
            'created.required' => 'The created field is required.',
            'created.date' => 'The created must be a valid date and time.',
            'modified.date' => 'The modified must be a valid date and time.',
            'status.required' => 'The status field is required.',
            'status.boolean' => 'The status must be true or false.',
            'status.max' => 'The status must not exceed 1 characters.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'created' => now(),
        ]);
    }
}
