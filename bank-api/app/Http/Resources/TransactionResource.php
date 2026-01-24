<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TransactionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'user_id' => $this->user_id,
            'user_id_details' => null,
            'user_name' => $this->user?->name ?? $this->user?->title ?? $this->user?->code ?? null,
            'account_id' => $this->account_id,
            'account_id_details' => $this->Account,
            'account_name' => $this->account?->account_name ?? $this->account?->title ?? $this->account?->code ?? null,
            'bounce_transaction_id' => $this->bounce_transaction_id,
            'bounce_transaction_id_details' => $this->Transaction,
            'bounce_transaction_name' => $this->Transaction?->name ?? $this->Transaction?->title ?? $this->Transaction?->code ?? null,
            'date' => $this->date?->format('Y-m-d H:i:s'),
            'type' => $this->type,
            'details' => $this->details,
            'deposit' => $this->deposit ? (float) $this->deposit : 0.0,
            'withdraw' => $this->withdraw ? (float) $this->withdraw : 0.0,
            'receive_from' => $this->receive_from,
            'payment_to' => $this->payment_to,
            'attachment' => $this->attachment ? asset(\Illuminate\Support\Facades\Storage::url($this->attachment)) : null,
            'attachment_name' => $this->attachment ? basename($this->attachment) : null,
            'bounce_details' => $this->bounce_details,
            'created' => $this->created?->format('Y-m-d H:i:s'),
            'modified' => $this->modified?->format('Y-m-d H:i:s'),
            'status' => (bool) $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_at_formatted' => $this->created_at?->format('M d, Y h:i A'),
            'updated_at_formatted' => $this->updated_at?->format('M d, Y h:i A'),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),
        ];
    }
}
