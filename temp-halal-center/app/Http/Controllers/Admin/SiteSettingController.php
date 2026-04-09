<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;

class SiteSettingController extends BaseCrudController
{
    private const WATERMARK_FIELDS = [
        'watermark_enabled',
        'watermark_text',
        'watermark_image_path',
        'watermark_opacity',
    ];

    protected string $modelClass = SiteSetting::class;
    protected string $pageTitle = 'Profil Lembaga';
    protected string $routePrefix = 'admin.site-settings';
    protected string $requestClass = SiteSettingRequest::class;
    protected array $searchColumns = ['institution_name', 'tagline', 'contact_email'];
    protected array $tableColumns = [
        ['key' => 'institution_name', 'label' => 'Nama Institusi'],
        ['key' => 'tagline', 'label' => 'Tagline'],
        ['key' => 'contact_email', 'label' => 'Email'],
    ];
    protected array $formFields = [
        ['name' => 'institution_name', 'label' => 'Nama Institusi', 'type' => 'text', 'required' => true],
        ['name' => 'institution_name_en', 'label' => 'Institution Name EN', 'type' => 'text'],
        ['name' => 'hero_badge', 'label' => 'Hero Badge', 'type' => 'text'],
        ['name' => 'tagline', 'label' => 'Tagline', 'type' => 'text', 'required' => true],
        ['name' => 'tagline_en', 'label' => 'Tagline EN', 'type' => 'text'],
        ['name' => 'short_description', 'label' => 'Deskripsi Singkat', 'type' => 'richtext', 'required' => true],
        ['name' => 'short_description_en', 'label' => 'Short Description EN', 'type' => 'richtext'],
        ['name' => 'contact_email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'contact_phone', 'label' => 'Telepon', 'type' => 'text'],
        ['name' => 'whatsapp_number', 'label' => 'WhatsApp', 'type' => 'text'],
        ['name' => 'consultation_url', 'label' => 'URL Konsultasi', 'type' => 'url'],
        ['name' => 'sehati_url', 'label' => 'URL Sehati', 'type' => 'url'],
        ['name' => 'address', 'label' => 'Alamat', 'type' => 'textarea'],
        ['name' => 'address_en', 'label' => 'Address EN', 'type' => 'textarea'],
        ['name' => 'meta_title', 'label' => 'Meta Title', 'type' => 'text'],
        ['name' => 'meta_description', 'label' => 'Meta Description', 'type' => 'textarea'],
        ['name' => 'meta_keywords', 'label' => 'Meta Keywords', 'type' => 'text'],
        ['name' => 'default_locale', 'label' => 'Bahasa Default', 'type' => 'select', 'options' => ['id' => 'Indonesia', 'en' => 'English']],
        ['name' => 'logo_path', 'label' => 'Logo', 'type' => 'image'],
        ['name' => 'og_image_path', 'label' => 'OG Image', 'type' => 'image'],
        ['name' => 'watermark_enabled', 'label' => 'Aktifkan Watermark Global', 'type' => 'checkbox'],
        ['name' => 'watermark_text', 'label' => 'Teks Watermark', 'type' => 'text'],
        ['name' => 'watermark_image_path', 'label' => 'Gambar Watermark (PNG)', 'type' => 'image'],
        ['name' => 'watermark_opacity', 'label' => 'Opacity Watermark', 'type' => 'number', 'step' => '0.05'],
    ];

    protected array $publicFileFields = ['logo_path', 'og_image_path', 'watermark_image_path'];

    protected function resolvedFields(): array
    {
        if (auth()->user()?->hasRole('developer')) {
            return $this->formFields;
        }

        return array_values(array_filter(
            $this->formFields,
            fn (array $field): bool => ! in_array($field['name'], self::WATERMARK_FIELDS, true)
        ));
    }

    protected function validatedData(?Model $model = null): array
    {
        $validated = parent::validatedData($model);

        if (! auth()->user()?->hasRole('developer')) {
            foreach (self::WATERMARK_FIELDS as $field) {
                unset($validated[$field]);
            }
        }

        return $validated;
    }
}
