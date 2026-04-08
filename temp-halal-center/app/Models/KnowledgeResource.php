<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class KnowledgeResource extends Model
{
    use Sluggable;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'summary',
        'content',
        'thumbnail_path',
        'document_path',
        'external_url',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
}
