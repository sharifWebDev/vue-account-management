<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserIdRequest extends FormRequest
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
                Rule::exists(User::class, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'User ID is required.',
            'id.integer' => 'User ID must be an integer.',
            'id.exists' => 'User not found.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        validationThrowException($validator);
    }
}
