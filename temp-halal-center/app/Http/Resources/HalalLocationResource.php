<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HalalLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'location_type' => $this->location_type,
            'category' => $this->category,
            'city_name' => $this->city_name ?: $this->region?->name,
            'business_scale' => $this->business_scale,
            'brand_name' => $this->brand_name,
            'product_name' => $this->product_name,
            'address' => $this->address,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'phone' => $this->phone,
            'email' => $this->email,
            'description' => $this->description,
            'certificate_number' => $this->certificate_number,
            'certificate_issued_at' => optional($this->certificate_issued_at)->toDateString(),
            'certificate_expires_at' => optional($this->certificate_expires_at)->toDateString(),
            'region' => [
                'id' => $this->region?->id,
                'name' => $this->region?->name,
            ],
            'lph_partner' => [
                'id' => $this->lphPartner?->id,
                'name' => $this->lphPartner?->name,
                'type' => $this->lphPartner?->partner_type,
            ],
        ];
    }
}
