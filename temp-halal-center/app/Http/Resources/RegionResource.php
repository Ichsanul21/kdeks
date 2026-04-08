<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'halal_msmes_count' => $this->halal_msmes_count,
            'mentor_count' => $this->mentor_count,
            'issued_certificate_count' => $this->issued_certificate_count,
            'service_office_count' => $this->service_office_count,
        ];
    }
}
