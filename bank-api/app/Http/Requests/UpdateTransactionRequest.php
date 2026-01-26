<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'in:Deposit,Withdraw,Transfer,Cheque Bounce,Other'],
            'details' => ['nullable', 'string', 'max:65535'],
            'deposit' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'withdraw' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'receive_from' => ['nullable', 'string', 'max:255', 'required_if:type,Deposit'],
            'payment_to' => ['nullable', 'string', 'max:255', 'required_if:type,Withdraw'],
            'bounce_details' => ['nullable', 'string', 'max:255'],
            'bounce_transaction_id' => ['nullable', 'integer', 'exists:transactions,id'],
            'status' => ['nullable'],
        ];

        // Validate that either deposit or withdraw is provided based on type
        if ($this->input('type') === 'Deposit') {
            $rules['deposit'] = ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'];
            $rules['withdraw'] = ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'];
        } elseif ($this->input('type') === 'Withdraw') {
            $rules['withdraw'] = ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'];
            $rules['deposit'] = ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'receive_from.required_if' => 'The receive from field is required for deposit transactions.',
            'payment_to.required_if' => 'The payment to field is required for withdrawal transactions.',
            'deposit.required' => 'Deposit amount is required for deposit transactions.',
            'withdraw.required' => 'Withdraw amount is required for withdrawal transactions.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
            'deposit' => $this->deposit ?? 0,
            'withdraw' => $this->withdraw ?? 0,
            'status' => $this->status ?? true,
            'date' => $this->date ?? now(),
        ]);
    }
}
