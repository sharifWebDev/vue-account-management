<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AccountResource extends BaseResource
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
            'company_id' => $this->company_id,
            'company_id_details' => $this->Company,
            'company_name' => $this->company?->company_name ?? $this->company?->title ?? $this->company?->code ?? null,
            'bank_id' => $this->bank_id,
            'bank_id_details' => $this->bank,
            'bank_name' => $this->bank?->bank_name ?? $this->bank?->title ?? $this->bank?->code ?? null,
            'branch_id' => $this->branch_id,
            'branch_id_details' => $this->branch,
            'branch_name' => $this->branch?->branch_name ?? $this->branch?->title ?? $this->branch?->code ?? null,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'cheque_book' => $this->cheque_book,
            'opening_balance' => $this->opening_balance ? (float) $this->opening_balance : 0.0,
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
