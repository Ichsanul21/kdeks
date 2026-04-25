<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class HalalProduct extends Model
{
    use Sluggable;

    protected $fillable = [
        'region_id',
        'name',
        'slug',
        'brand_name',
        'category',
        'certificate_number',
        'certificate_issued_at',
        'certificate_expires_at',
        'description',
        'image_path',
        'product_url',
        'is_featured',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'certificate_issued_at' => 'date',
            'certificate_expires_at' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
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
