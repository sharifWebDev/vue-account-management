<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyIdRequest extends FormRequest
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
                Rule::exists(Company::class, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Company ID is required.',
            'id.integer' => 'Company ID must be an integer.',
            'id.exists' => 'Company not found.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        validationThrowException($validator);
    }
}
