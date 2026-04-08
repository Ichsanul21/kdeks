<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SectorItemRequest;
use App\Models\SectorItem;

class SectorItemController extends BaseCrudController
{
    protected string $modelClass = SectorItem::class;
    protected string $pageTitle = 'Sektor Ekonomi Syariah';
    protected string $routePrefix = 'admin.sector-items';
    protected string $requestClass = SectorItemRequest::class;
    protected array $searchColumns = ['title', 'summary', 'icon_key'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'icon_key', 'label' => 'Ikon'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
    ];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'icon_key', 'label' => 'Kunci Ikon Lucide', 'type' => 'text', 'required' => true],
        ['name' => 'summary', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true],
        ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox'],
    ];
}
