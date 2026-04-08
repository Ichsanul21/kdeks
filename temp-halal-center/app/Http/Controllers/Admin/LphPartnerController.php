<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LphPartnerRequest;
use App\Models\LphPartner;

class LphPartnerController extends BaseCrudController
{
    protected string $modelClass = LphPartner::class;
    protected string $pageTitle = 'LPH / LP3H';
    protected string $routePrefix = 'admin.lph-partners';
    protected string $requestClass = LphPartnerRequest::class;
    protected array $searchColumns = ['name', 'partner_type', 'contact_person'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'partner_type', 'label' => 'Tipe'],
        ['key' => 'contact_person', 'label' => 'PIC'],
        ['key' => 'phone', 'label' => 'Telepon'],
    ];
    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama Lembaga', 'type' => 'text', 'required' => true],
        ['name' => 'partner_type', 'label' => 'Tipe Mitra', 'type' => 'select', 'options' => ['lph' => 'LPH', 'lp3h' => 'LP3H']],
        ['name' => 'contact_person', 'label' => 'PIC', 'type' => 'text'],
        ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'website_url', 'label' => 'Website', 'type' => 'url'],
        ['name' => 'address', 'label' => 'Alamat', 'type' => 'textarea'],
        ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'richtext'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true],
        ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox'],
    ];
}
