<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'latitude',
        'longitude',
        'halal_msmes_count',
        'mentor_count',
        'issued_certificate_count',
        'service_office_count',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function halalLocations(): HasMany
    {
        return $this->hasMany(HalalLocation::class);
    }

    public function halalProducts(): HasMany
    {
        return $this->hasMany(HalalProduct::class);
    }

    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class);
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
