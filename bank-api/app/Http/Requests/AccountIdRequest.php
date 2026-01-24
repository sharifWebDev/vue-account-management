<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function all($keys = null): array
    {
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists(Account::class, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Account ID is required.',
            'id.integer' => 'Account ID must be an integer.',
            'id.exists' => 'Account not found.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        validationThrowException($validator);
    }
}
