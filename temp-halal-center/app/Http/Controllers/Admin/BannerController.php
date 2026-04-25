<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BannerRequest;
use App\Models\Banner;

class BannerController extends BaseCrudController
{
    protected string $modelClass = Banner::class;
    protected string $pageTitle = 'Banner Landing Page';
    protected string $routePrefix = 'admin.banners';
    protected string $requestClass = BannerRequest::class;
    protected array $searchColumns = ['title', 'subtitle'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'is_active', 'label' => 'Aktif'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
    ];
    protected array $publicFileFields = ['image_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'subtitle', 'label' => 'Subjudul', 'type' => 'text'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
        ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox'],
        ['name' => 'image_path', 'label' => 'Gambar Banner (Maks 1MB)', 'type' => 'image'],
    ];
}
