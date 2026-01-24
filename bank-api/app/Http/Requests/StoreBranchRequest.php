<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
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
            'branch_name' => ['required', 'string', 'max:255'],
            'bank_id' => ['required', 'exists:banks,id'],
            'address' => ['required', 'string', 'max:65535'],
            'phone' => ['required', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'mobile' => ['required', 'string', 'max:255', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
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
            'branch_name.required' => 'The branch name field is required.',
            'branch_name.string' => 'The branch name must be a string.',
            'branch_name.max' => 'The branch name must not exceed 255 characters.',
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
