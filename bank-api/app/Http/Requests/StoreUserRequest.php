<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:65535'],
            'phone' => ['nullable', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'mobile' => ['nullable', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                'lowercase',
                //  'email:filter,spoof,strict',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
                'min:8',
                'confirmed',
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
            'date_of_birth' => ['nullable', 'date'],
            'joining_date' => ['nullable', 'date'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,bmp,ico', 'max:10240'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'google_plus' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'company_ids' => ['sometimes', 'string', 'max:255'],
            'user_type' => ['required', 'string', 'max:255'],
            'created' => ['nullable', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['nullable', 'boolean', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name must not exceed 255 characters.',
            'last_name.required' => 'The last name field is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name must not exceed 255 characters.',
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
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'email.lowercase' => 'The email must be in lowercase.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'joining_date.date' => 'The joining date must be a valid date.',
            'image.required' => 'The image field is required.',
            'image.image' => 'The image must be a valid image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, GIF, SVG, WEBP, BMP, or ICO file.',
            'image.max' => 'The image must not exceed 10MB.',
            'facebook.required' => 'The facebook field is required.',
            'facebook.string' => 'The facebook must be a string.',
            'facebook.max' => 'The facebook must not exceed 255 characters.',
            'twitter.required' => 'The twitter field is required.',
            'twitter.string' => 'The twitter must be a string.',
            'twitter.max' => 'The twitter must not exceed 255 characters.',
            'instagram.required' => 'The instagram field is required.',
            'instagram.string' => 'The instagram must be a string.',
            'instagram.max' => 'The instagram must not exceed 255 characters.',
            'google_plus.required' => 'The google plus field is required.',
            'google_plus.string' => 'The google plus must be a string.',
            'google_plus.max' => 'The google plus must not exceed 255 characters.',
            'linkedin.required' => 'The linkedin field is required.',
            'linkedin.string' => 'The linkedin must be a string.',
            'linkedin.max' => 'The linkedin must not exceed 255 characters.',
            'company_ids.required' => 'The company ids field is required.',
            'company_ids.string' => 'The company ids must be a string.',
            'company_ids.max' => 'The company ids must not exceed 255 characters.',
            'user_type.required' => 'The user type field is required.',
            'user_type.string' => 'The user type must be a string.',
            'user_type.max' => 'The user type must not exceed 255 characters.',
            'created.required' => 'The created field is required.',
            'created.date' => 'The created must be a valid date and time.',
            'modified.date' => 'The modified must be a valid date and time.',
            'status.required' => 'The status field is required.',
            'status.boolean' => 'The status must be true or false.',
            'status.max' => 'The status must not exceed 1 characters.',
        ];
    }
}
