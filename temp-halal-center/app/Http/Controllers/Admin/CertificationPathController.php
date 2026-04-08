<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CertificationPathRequest;
use App\Models\CertificationPath;

class CertificationPathController extends BaseCrudController
{
    protected string $modelClass = CertificationPath::class;
    protected string $pageTitle = 'Panduan Sertifikasi';
    protected string $routePrefix = 'admin.certification-paths';
    protected string $requestClass = CertificationPathRequest::class;
    protected array $searchColumns = ['name', 'path_type'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama Jalur'],
        ['key' => 'path_type', 'label' => 'Tipe'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
    ];
    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama Jalur', 'type' => 'text', 'required' => true],
        ['name' => 'path_type', 'label' => 'Tipe Jalur', 'type' => 'select', 'options' => ['self_declare' => 'Self Declare', 'regular' => 'Reguler']],
        ['name' => 'summary', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'content', 'label' => 'Panduan Lengkap', 'type' => 'richtext', 'required' => true],
        ['name' => 'cta_label', 'label' => 'Label CTA', 'type' => 'text'],
        ['name' => 'cta_url', 'label' => 'URL CTA', 'type' => 'url'],
        ['name' => 'icon', 'label' => 'Ikon', 'type' => 'text'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
    ];
}
