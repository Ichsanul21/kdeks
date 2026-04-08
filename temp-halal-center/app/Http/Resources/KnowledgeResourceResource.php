<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KnowledgeResourceResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'download_url' => $this->document_path ? route('documents.download', ['type' => 'resource', 'id' => $this->id]) : null,
        ];
    }
}
