<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionIdRequest extends FormRequest
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
                Rule::exists(Transaction::class, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Transaction ID is required.',
            'id.integer' => 'Transaction ID must be an integer.',
            'id.exists' => 'Transaction not found.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        validationThrowException($validator);
    }
}
