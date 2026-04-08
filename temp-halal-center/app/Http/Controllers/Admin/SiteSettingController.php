<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SiteSettingRequest;
use App\Models\SiteSetting;

class SiteSettingController extends BaseCrudController
{
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
    protected array $publicFileFields = ['logo_path', 'og_image_path'];
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
    ];
}
