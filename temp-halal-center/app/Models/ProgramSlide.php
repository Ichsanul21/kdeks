<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'cta_label',
        'cta_url',
        'image_path',
        'status',
        'starts_at',
        'ends_at',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }
}
