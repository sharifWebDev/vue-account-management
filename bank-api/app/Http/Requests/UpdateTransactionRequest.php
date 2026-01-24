<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'user_id' => ['sometimes', 'integer', 'max:11', 'exists:users,id'],
            'account_id' => ['sometimes', 'integer', 'max:11', 'exists:accounts,id'],
            'bounce_transaction_id' => ['nullable', 'integer', 'max:11', 'exists:transactions,id'],
            'date' => ['sometimes', 'date'],
            'type' => ['sometimes', 'string', 'max:255'],
            'details' => ['sometimes', 'string', 'max:65535'],
            'deposit' => ['sometimes', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'withdraw' => ['sometimes', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'receive_from' => ['sometimes', 'string', 'max:255'],
            'payment_to' => ['sometimes', 'string', 'max:255'],
            'attachment' => ['sometimes', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,rar,7z', 'max:20480'],
            'bounce_details' => ['sometimes', 'string', 'max:255'],
            'created' => ['sometimes', 'date'],
            'modified' => ['nullable', 'date'],
            'status' => ['sometimes', 'boolean', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.integer' => 'The user id must be an integer.',
            'user_id.max' => 'The user id must not exceed 11 characters.',
            'user_id.exists' => 'The selected user id is invalid or does not exist in users.',
            'account_id.integer' => 'The account id must be an integer.',
            'account_id.max' => 'The account id must not exceed 11 characters.',
            'account_id.exists' => 'The selected account id is invalid or does not exist in accounts.',
            'bounce_transaction_id.integer' => 'The bounce transaction id must be an integer.',
            'bounce_transaction_id.max' => 'The bounce transaction id must not exceed 11 characters.',
            'bounce_transaction_id.exists' => 'The selected bounce transaction id is invalid or does not exist in transactions.',
            'date.date' => 'The date must be a valid date and time.',
            'type.string' => 'The type must be a string.',
            'type.max' => 'The type must not exceed 255 characters.',
            'details.string' => 'The details must be a string.',
            'details.max' => 'The details must not exceed 65535 characters.',
            'deposit.numeric' => 'The deposit must be a number.',
            'deposit.regex' => 'The deposit must be a valid decimal number.',
            'withdraw.numeric' => 'The withdraw must be a number.',
            'withdraw.regex' => 'The withdraw must be a valid decimal number.',
            'receive_from.string' => 'The receive from must be a string.',
            'receive_from.max' => 'The receive from must not exceed 255 characters.',
            'payment_to.string' => 'The payment to must be a string.',
            'payment_to.max' => 'The payment to must not exceed 255 characters.',
            'attachment.file' => 'The attachment must be a valid file.',
            'attachment.mimes' => 'The attachment must be a PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV, ZIP, RAR, or 7Z file.',
            'attachment.max' => 'The attachment must not exceed 20MB.',
            'bounce_details.string' => 'The bounce details must be a string.',
            'bounce_details.max' => 'The bounce details must not exceed 255 characters.',
            'created.date' => 'The created must be a valid date and time.',
            'modified.date' => 'The modified must be a valid date and time.',
            'status.boolean' => 'The status must be true or false.',
            'status.max' => 'The status must not exceed 1 characters.',
        ];
    }
}
