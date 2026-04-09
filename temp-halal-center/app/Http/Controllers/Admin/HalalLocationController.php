<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HalalLocationRequest;
use App\Models\HalalLocation;
use App\Models\LphPartner;
use App\Models\Region;

class HalalLocationController extends BaseCrudController
{
    protected string $modelClass = HalalLocation::class;
    protected string $pageTitle = 'Titik WebGIS';
    protected string $routePrefix = 'admin.halal-locations';
    protected string $requestClass = HalalLocationRequest::class;
    protected ?string $publicIndexRoute = 'home';
    protected ?string $publicShowRoute = 'locations.show';
    protected array $searchColumns = ['name', 'category', 'brand_name', 'product_name', 'city_name', 'certificate_number'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'region.name', 'label' => 'Wilayah'],
        ['key' => 'city_name', 'label' => 'Kota'],
        ['key' => 'category', 'label' => 'Kategori'],
        ['key' => 'location_type', 'label' => 'Tipe'],
    ];
    protected array $publicFileFields = ['image_path'];

    protected function resolvedFields(): array
    {
        $regions = Region::query()->orderBy('name')->pluck('name', 'id')->all();
        $lphPartners = LphPartner::query()->orderBy('name')->pluck('name', 'id')->all();

        return [
            ['name' => 'region_id', 'label' => 'Wilayah', 'type' => 'select', 'options' => $regions],
            ['name' => 'lph_partner_id', 'label' => 'LPH / LP3H', 'type' => 'select', 'options' => $lphPartners],
            ['name' => 'name', 'label' => 'Nama Lokasi', 'type' => 'text', 'required' => true],
            ['name' => 'location_type', 'label' => 'Tipe', 'type' => 'select', 'options' => ['umkm' => 'UMKM', 'service_office' => 'Kantor Layanan']],
            ['name' => 'category', 'label' => 'Kategori', 'type' => 'select', 'options' => [
                'Makanan' => 'Makanan',
                'Minuman' => 'Minuman',
                'Wisata Ramah' => 'Wisata Ramah',
                'Unit Usaha Ponpes' => 'Unit Usaha Ponpes',
                'Produk Halal Lainnya' => 'Produk Halal Lainnya',
                'Rumah Potong' => 'Rumah Potong',
                'Industri Kreatif' => 'Industri Kreatif',
                'Perbankan Syariah' => 'Perbankan Syariah',
                'Lembaga Keuangan' => 'Lembaga Keuangan',
            ]],
            ['name' => 'city_name', 'label' => 'Kabupaten / Kota', 'type' => 'text'],
            ['name' => 'business_scale', 'label' => 'Skala Usaha', 'type' => 'text'],
            ['name' => 'owner_name', 'label' => 'Pemilik', 'type' => 'text'],
            ['name' => 'brand_name', 'label' => 'Brand', 'type' => 'text'],
            ['name' => 'product_name', 'label' => 'Produk', 'type' => 'text'],
            ['name' => 'address', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true, 'id' => 'location-address'],
            ['name' => 'location_picker', 'label' => 'Pilih Titik Peta', 'type' => 'map-picker', 'latitude_target' => 'location-latitude', 'longitude_target' => 'location-longitude', 'address_target' => 'location-address'],
            ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'number', 'step' => '0.0000001', 'required' => true, 'id' => 'location-latitude'],
            ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'number', 'step' => '0.0000001', 'required' => true, 'id' => 'location-longitude'],
            ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['name' => 'website_url', 'label' => 'Website', 'type' => 'url'],
            ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'richtext'],
            ['name' => 'certificate_number', 'label' => 'Nomor Sertifikat', 'type' => 'text'],
            ['name' => 'certificate_issued_at', 'label' => 'Tanggal Terbit', 'type' => 'date'],
            ['name' => 'certificate_expires_at', 'label' => 'Tanggal Expired', 'type' => 'date'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
            ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
            ['name' => 'image_path', 'label' => 'Gambar', 'type' => 'image'],
        ];
    }
}
