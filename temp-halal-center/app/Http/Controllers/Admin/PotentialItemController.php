<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PotentialItemRequest;
use App\Models\PotentialItem;

class PotentialItemController extends BaseCrudController
{
    protected string $modelClass = PotentialItem::class;
    protected string $pageTitle = 'Potensi Pengembangan';
    protected string $routePrefix = 'admin.potential-items';
    protected string $requestClass = PotentialItemRequest::class;
    protected array $searchColumns = ['title', 'summary', 'icon_key'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'icon_key', 'label' => 'Ikon'],
        ['key' => 'accent_color', 'label' => 'Warna'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
    ];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'icon_key', 'label' => 'Kunci Ikon Lucide', 'type' => 'text', 'required' => true],
        ['name' => 'accent_color', 'label' => 'Aksen Warna', 'type' => 'text', 'required' => true],
        ['name' => 'summary', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true],
        ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox'],
    ];
}
