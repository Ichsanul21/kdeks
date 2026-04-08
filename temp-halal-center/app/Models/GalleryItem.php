<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'media_type',
        'caption',
        'media_path',
        'external_video_url',
        'recorded_at',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }
}
