<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'company_id' => ['required', 'integer', 'max:11', 'exists:companies,id'],
            'bank_id' => ['required', 'integer', 'max:11', 'exists:banks,id'],
            'branch_id' => ['required', 'integer', 'max:11', 'exists:branches,id'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'cheque_book' => ['required', 'string', 'max:65535'],
            'opening_balance' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'created' => ['nullable', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['nullable', 'boolean', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'The company id field is required.',
            'company_id.integer' => 'The company id must be an integer.',
            'company_id.max' => 'The company id must not exceed 11 characters.',
            'company_id.exists' => 'The selected company id is invalid or does not exist in companies.',
            'bank_id.required' => 'The bank id field is required.',
            'bank_id.integer' => 'The bank id must be an integer.',
            'bank_id.max' => 'The bank id must not exceed 11 characters.',
            'bank_id.exists' => 'The selected bank id is invalid or does not exist in banks.',
            'branch_id.required' => 'The branch id field is required.',
            'branch_id.integer' => 'The branch id must be an integer.',
            'branch_id.max' => 'The branch id must not exceed 11 characters.',
            'branch_id.exists' => 'The selected branch id is invalid or does not exist in branches.',
            'account_name.required' => 'The account name field is required.',
            'account_name.string' => 'The account name must be a string.',
            'account_name.max' => 'The account name must not exceed 255 characters.',
            'account_number.required' => 'The account number field is required.',
            'account_number.string' => 'The account number must be a string.',
            'account_number.max' => 'The account number must not exceed 255 characters.',
            'cheque_book.required' => 'The cheque book field is required.',
            'cheque_book.string' => 'The cheque book must be a string.',
            'cheque_book.max' => 'The cheque book must not exceed 65535 characters.',
            'opening_balance.required' => 'The opening balance field is required.',
            'opening_balance.numeric' => 'The opening balance must be a number.',
            'opening_balance.regex' => 'The opening balance must be a valid decimal number.',
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
