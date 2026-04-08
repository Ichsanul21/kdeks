<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HalalProductRequest;
use App\Models\HalalProduct;
use App\Models\Region;

class HalalProductController extends BaseCrudController
{
    protected string $modelClass = HalalProduct::class;
    protected string $pageTitle = 'Direktori Produk';
    protected string $routePrefix = 'admin.halal-products';
    protected string $requestClass = HalalProductRequest::class;
    protected array $searchColumns = ['name', 'brand_name', 'category', 'certificate_number'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Produk'],
        ['key' => 'brand_name', 'label' => 'Merek'],
        ['key' => 'category', 'label' => 'Kategori'],
        ['key' => 'region.name', 'label' => 'Wilayah'],
    ];
    protected array $publicFileFields = ['image_path'];

    protected function resolvedFields(): array
    {
        $regions = Region::query()->orderBy('name')->pluck('name', 'id')->all();

        return [
            ['name' => 'region_id', 'label' => 'Wilayah', 'type' => 'select', 'options' => $regions],
            ['name' => 'name', 'label' => 'Nama Produk', 'type' => 'text', 'required' => true],
            ['name' => 'brand_name', 'label' => 'Merek', 'type' => 'text'],
            ['name' => 'category', 'label' => 'Kategori', 'type' => 'text', 'required' => true],
            ['name' => 'certificate_number', 'label' => 'No. Sertifikat', 'type' => 'text'],
            ['name' => 'certificate_issued_at', 'label' => 'Terbit', 'type' => 'date'],
            ['name' => 'certificate_expires_at', 'label' => 'Expired', 'type' => 'date'],
            ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'richtext'],
            ['name' => 'description_en', 'label' => 'Deskripsi EN', 'type' => 'richtext'],
            ['name' => 'product_url', 'label' => 'URL Produk', 'type' => 'url'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
            ['name' => 'image_path', 'label' => 'Gambar Produk', 'type' => 'image'],
        ];
    }
}
