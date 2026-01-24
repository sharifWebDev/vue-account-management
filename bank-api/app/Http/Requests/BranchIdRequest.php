<?php

namespace App\Http\Requests;

use App\Models\Branch;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchIdRequest extends FormRequest
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
                Rule::exists(Branch::class, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Branch ID is required.',
            'id.integer' => 'Branch ID must be an integer.',
            'id.exists' => 'Branch not found.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        validationThrowException($validator);
    }
}
