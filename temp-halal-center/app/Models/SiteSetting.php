<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'institution_name',
        'tagline',
        'short_description',
        'hero_badge',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'address',
        'consultation_url',
        'sehati_url',
        'logo_path',
        'og_image_path',
        'organization_structure_image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'default_locale',
        'watermark_enabled',
        'watermark_text',
        'watermark_image_path',
        'watermark_opacity',
        'visitor_count',
    ];

    protected function casts(): array
    {
        return [
            'watermark_enabled' => 'boolean',
            'watermark_opacity' => 'float',
        ];
    }
}
