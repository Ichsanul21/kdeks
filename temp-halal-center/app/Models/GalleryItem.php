<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'sector_item_id',
        'title',
        'media_type',
        'caption',
        'media_path',
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
        ];
    }
}
