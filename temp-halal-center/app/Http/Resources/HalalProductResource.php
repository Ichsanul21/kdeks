<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HalalProductResource extends JsonResource
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
            'brand_name' => $this->brand_name,
            'category' => $this->category,
            'certificate_number' => $this->certificate_number,
            'certificate_issued_at' => optional($this->certificate_issued_at)->toDateString(),
            'certificate_expires_at' => optional($this->certificate_expires_at)->toDateString(),
            'description' => $this->description,
            'image_url' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'region' => [
                'id' => $this->region?->id,
                'name' => $this->region?->name,
            ],
        ];
    }
}
