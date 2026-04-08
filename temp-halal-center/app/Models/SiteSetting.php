<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'institution_name',
        'institution_name_en',
        'tagline',
        'tagline_en',
        'short_description',
        'short_description_en',
        'hero_badge',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'address',
        'address_en',
        'consultation_url',
        'sehati_url',
        'logo_path',
        'og_image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'default_locale',
    ];
}
