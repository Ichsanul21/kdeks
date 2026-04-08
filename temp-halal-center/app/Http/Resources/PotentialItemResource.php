<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PotentialItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'icon_key' => $this->icon_key,
            'accent_color' => $this->accent_color,
            'summary' => $this->summary,
            'sort_order' => $this->sort_order,
        ];
    }
}
