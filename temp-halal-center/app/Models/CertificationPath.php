<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class CertificationPath extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'path_type',
        'summary',
        'content',
        'cta_label',
        'cta_url',
        'icon',
        'sort_order',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
