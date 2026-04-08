<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LphPartner extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'partner_type',
        'contact_person',
        'phone',
        'email',
        'website_url',
        'address',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function halalLocations(): HasMany
    {
        return $this->hasMany(HalalLocation::class);
    }

    public function sehatiRegistrations(): HasMany
    {
        return $this->hasMany(SehatiRegistration::class);
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
