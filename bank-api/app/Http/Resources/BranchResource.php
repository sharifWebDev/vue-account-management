<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class BranchResource extends BaseResource
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
            'branch_name' => $this->branch_name,
            'bank_id' => (int) $this->bank_id,
            'bank_name' => $this->bank?->bank_name ?? '',
            'address' => $this->address,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'fax' => $this->fax,
            'email' => $this->email,
            'website' => $this->website,
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
