<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RegionRequest;
use App\Models\Region;

class RegionController extends BaseCrudController
{
    protected string $modelClass = Region::class;
    protected string $pageTitle = 'Wilayah';
    protected string $routePrefix = 'admin.regions';
    protected string $requestClass = RegionRequest::class;
    protected array $searchColumns = ['name', 'slug'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Kabupaten / Kota'],
        ['key' => 'halal_msmes_count', 'label' => 'UMKM'],
        ['key' => 'issued_certificate_count', 'label' => 'Sertifikat'],
        ['key' => 'mentor_count', 'label' => 'Pendamping'],
    ];
    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama Wilayah', 'type' => 'text', 'required' => true],
        ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'number', 'step' => '0.0000001', 'required' => true],
        ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'number', 'step' => '0.0000001', 'required' => true],
        ['name' => 'halal_msmes_count', 'label' => 'Total UMKM Halal', 'type' => 'number', 'required' => true],
        ['name' => 'issued_certificate_count', 'label' => 'Total Sertifikat', 'type' => 'number', 'required' => true],
        ['name' => 'mentor_count', 'label' => 'Total Pendamping', 'type' => 'number', 'required' => true],
        ['name' => 'service_office_count', 'label' => 'Kantor Layanan', 'type' => 'number', 'required' => true],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true],
    ];
}
