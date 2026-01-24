<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'company_name' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:65535'],
            'phone' => ['sometimes', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'mobile' => ['sometimes', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'email' => ['sometimes', 'string', 'max:255', 'email', 'lowercase'],
            'website' => ['sometimes', 'string', 'max:255', 'url', 'active_url'],
            'logo' => ['sometimes', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,bmp,ico', 'max:10240'],
            'created' => ['sometimes', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['sometimes', 'boolean', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'company_name.string' => 'The company name must be a string.',
            'company_name.max' => 'The company name must not exceed 255 characters.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address must not exceed 65535 characters.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone must not exceed 255 characters.',
            'phone.regex' => 'The phone must be a valid phone number.',
            'mobile.string' => 'The mobile must be a string.',
            'mobile.max' => 'The mobile must not exceed 255 characters.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'email.lowercase' => 'The email must be in lowercase.',
            'website.string' => 'The website must be a string.',
            'website.max' => 'The website must not exceed 255 characters.',
            'logo.image' => 'The logo must be a valid image.',
            'logo.mimes' => 'The logo must be a JPEG, PNG, JPG, GIF, SVG, WEBP, BMP, or ICO file.',
            'logo.max' => 'The logo must not exceed 10MB.',
            'created.date' => 'The created must be a valid date and time.',
            'modified.date' => 'The modified must be a valid date and time.',
            'status.boolean' => 'The status must be true or false.',
            'status.max' => 'The status must not exceed 1 characters.',
        ];
    }

    // set modified value = now
    public function prepareForValidation()
    {
        $this->merge([
            'modified' => now(),
            'updated_at' => now(),
        ]);
    }
}
