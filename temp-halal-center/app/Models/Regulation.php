<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'regulation_type',
        'regulation_number',
        'issued_at',
        'summary',
        'document_path',
        'external_url',
        'is_featured',
        'sector_item_id',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'is_featured' => 'boolean',
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

    public function directorate()
    {
        return $this->belongsTo(SectorItem::class, 'sector_item_id');
    }
}
