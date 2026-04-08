<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegulationResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'regulation_type' => $this->regulation_type,
            'regulation_number' => $this->regulation_number,
            'issued_at' => optional($this->issued_at)->toDateString(),
            'summary' => $this->summary,
            'download_url' => $this->document_path ? route('documents.download', ['type' => 'regulation', 'id' => $this->id]) : null,
        ];
    }
}
