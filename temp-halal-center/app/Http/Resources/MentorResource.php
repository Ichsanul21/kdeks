<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MentorResource extends JsonResource
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
            'phone' => $this->phone,
            'email' => $this->email,
            'expertise' => $this->expertise,
            'district_coverage' => $this->district_coverage,
            'bio' => $this->bio,
            'photo_url' => $this->photo_path ? asset('storage/'.$this->photo_path) : null,
            'region' => [
                'id' => $this->region?->id,
                'name' => $this->region?->name,
            ],
        ];
    }
}
