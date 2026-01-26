<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'user_id' => ['nullable', 'integer',  'exists:users,id'],
            'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'bounce_transaction_id' => ['nullable', 'integer', 'exists:transactions,id'],
            'date' => ['nullable', 'date'],
            'type' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:65535'],
            'deposit' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'withdraw' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'receive_from' => ['nullable', 'string', 'max:255'],
            'payment_to' => ['nullable', 'string', 'max:255'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,rar,7z', 'max:20480'],
            'bounce_details' => ['nullable', 'string', 'max:255'],
            'created' => ['nullable', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['nullable'],
        ];
    }


    public function messages()
    {
        return [
            'user_id.required' => 'The user id field is required.',
            'user_id.integer' => 'The user id must be an integer.',
            'user_id.max' => 'The user id must not exceed 11 characters.',
            'user_id.exists' => 'The selected user id is invalid or does not exist in users.',
            'account_id.required' => 'The account id field is required.',
            'account_id.integer' => 'The account id must be an integer.',
            'account_id.max' => 'The account id must not exceed 11 characters.',
            'account_id.exists' => 'The selected account id is invalid or does not exist in accounts.',
            'bounce_transaction_id.integer' => 'The bounce transaction id must be an integer.',
            'bounce_transaction_id.max' => 'The bounce transaction id must not exceed 11 characters.',
            'bounce_transaction_id.exists' => 'The selected bounce transaction id is invalid or does not exist in transactions.',
            'date.required' => 'The date field is required.',
            'date.date' => 'The date must be a valid date and time.',
            'type.required' => 'The type field is required.',
            'type.string' => 'The type must be a string.',
            'type.max' => 'The type must not exceed 255 characters.',
            'details.required' => 'The details field is required.',
            'details.string' => 'The details must be a string.',
            'details.max' => 'The details must not exceed 65535 characters.',
            'deposit.required' => 'The deposit field is required.',
            'deposit.numeric' => 'The deposit must be a number.',
            'deposit.regex' => 'The deposit must be a valid decimal number.',
            'withdraw.required' => 'The withdraw field is required.',
            'withdraw.numeric' => 'The withdraw must be a number.',
            'withdraw.regex' => 'The withdraw must be a valid decimal number.',
            'receive_from.required' => 'The receive from field is required.',
            'receive_from.string' => 'The receive from must be a string.',
            'receive_from.max' => 'The receive from must not exceed 255 characters.',
            'payment_to.required' => 'The payment to field is required.',
            'payment_to.string' => 'The payment to must be a string.',
            'payment_to.max' => 'The payment to must not exceed 255 characters.',
            'attachment.required' => 'The attachment field is required.',
            'attachment.file' => 'The attachment must be a valid file.',
            'attachment.mimes' => 'The attachment must be a PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV, ZIP, RAR, or 7Z file.',
            'attachment.max' => 'The attachment must not exceed 20MB.',
            'bounce_details.required' => 'The bounce details field is required.',
            'bounce_details.string' => 'The bounce details must be a string.',
            'bounce_details.max' => 'The bounce details must not exceed 255 characters.',
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
            'user_id' => auth()->id(),           // shorthand for auth()->user()->id
            'created_by' => auth()->id(),        // shorthand for auth()->user()->id
            'date' => now(),
            'deposit' => $this->deposit ?? 0,
            'withdraw' => $this->withdraw ?? 0,
            'receive_from' => $this->receive_from ?? '', // default empty string if not provided
            'payment_to' => $this->payment_to ?? '',
            'attachment' => $this->attachment ?? null,  // null if not uploaded
            'bounce_details' => $this->input('details') ?? '', // fixed
            'created' => now(),
        ]);
    }
}
