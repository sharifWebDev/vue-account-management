<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CompanyResource extends BaseResource
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
            'company_name' => $this->company_name,
            'address' => $this->address,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'logo' => $this->logo ? asset(\Illuminate\Support\Facades\Storage::url($this->logo)) : null,
            'logo_name' => $this->logo ? basename($this->logo) : null,
            'created' => $this->created?->format('Y-m-d H:i:s'),
            'modified' => $this->modified?->format('Y-m-d H:i:s'),
            'status' => (bool) $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'created_at_formatted' => $this->created_at?->format('M d, Y h:i A'),
            'updated_at_formatted' => $this->updated_at?->format('M d, Y h:i A'),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),
        ];
    }
}
