<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_name' => ['required', 'string', 'max:255'],
            'institution_name_en' => ['nullable', 'string', 'max:255'],
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'tagline_en' => ['nullable', 'string', 'max:255'],
            'short_description' => ['required', 'string'],
            'short_description_en' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'consultation_url' => ['nullable', 'url'],
            'sehati_url' => ['nullable', 'url'],
            'address' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'default_locale' => ['required', 'in:id,en'],
            'logo_path' => ['nullable', 'image', 'max:2048'],
            'og_image_path' => ['nullable', 'image', 'max:4096'],
            'watermark_enabled' => ['nullable', 'boolean'],
            'watermark_text' => ['nullable', 'string', 'max:255'],
            'watermark_image_path' => ['nullable', 'image', 'max:4096'],
            'watermark_opacity' => ['nullable', 'numeric', 'between:0.05,1'],
        ];
    }
}
