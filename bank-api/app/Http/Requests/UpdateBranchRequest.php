<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'branch_name' => ['sometimes', 'string', 'max:255'],
            'bank_id' => ['required', 'exists:banks,id'],
            'phone' => ['sometimes', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'mobile' => ['sometimes', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'fax' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'max:255', 'email', 'lowercase'],
            'website' => ['sometimes', 'string', 'max:255', 'url', 'active_url'],
            'created' => ['sometimes', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['sometimes', 'boolean', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'branch_name.string' => 'The branch name must be a string.',
            'branch_name.max' => 'The branch name must not exceed 255 characters.',
            'created.date' => 'The created must be a valid date and time.',
            'modified.date' => 'The modified must be a valid date and time.',
            'status.boolean' => 'The status must be true or false.',
            'status.max' => 'The status must not exceed 1 characters.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'updated' => now(),
        ]);
    }
}
