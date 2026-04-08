<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class HalalLocation extends Model
{
    use Sluggable;

    protected $fillable = [
        'region_id',
        'lph_partner_id',
        'name',
        'slug',
        'location_type',
        'category',
        'city_name',
        'business_scale',
        'owner_name',
        'brand_name',
        'product_name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website_url',
        'description',
        'certificate_number',
        'certificate_issued_at',
        'certificate_expires_at',
        'image_path',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'certificate_issued_at' => 'date',
            'certificate_expires_at' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function lphPartner(): BelongsTo
    {
        return $this->belongsTo(LphPartner::class);
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
