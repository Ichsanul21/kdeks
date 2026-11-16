<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        'sector_item_id',
        'title',
        'media_type',
        'caption',
        'media_path',
        'media_paths',
        'external_video_url',
        'recorded_at',
        'is_featured',
    ];

    public function sectorItem()
    {
        return $this->belongsTo(SectorItem::class, 'sector_item_id');
    }

    public function directorate()
    {
        return $this->belongsTo(SectorItem::class, 'sector_item_id');
    }

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'is_featured' => 'boolean',
            'media_paths' => 'array',
        ];
    }

    public function getAllMediaPathsAttribute(): array
    {
        if (!empty($this->media_paths) && is_array($this->media_paths)) {
            return array_values($this->media_paths);
        }
        return $this->media_path ? [$this->media_path] : [];
    }

    public function getAllMediaUrlsAttribute(): array
    {
        $urls = [];
        foreach ($this->all_media_paths as $path) {
            $urls[] = Storage::url($path);
        }
        return $urls;
    }
}
