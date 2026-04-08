<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RegulationRequest;
use App\Models\Regulation;

class RegulationController extends BaseCrudController
{
    protected string $modelClass = Regulation::class;
    protected string $pageTitle = 'Regulasi';
    protected string $routePrefix = 'admin.regulations';
    protected string $requestClass = RegulationRequest::class;
    protected array $searchColumns = ['title', 'regulation_type', 'regulation_number'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'regulation_type', 'label' => 'Jenis'],
        ['key' => 'regulation_number', 'label' => 'Nomor'],
        ['key' => 'issued_at', 'label' => 'Tanggal'],
    ];
    protected array $privateFileFields = ['document_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'regulation_type', 'label' => 'Jenis Regulasi', 'type' => 'text', 'required' => true],
        ['name' => 'regulation_number', 'label' => 'Nomor', 'type' => 'text'],
        ['name' => 'issued_at', 'label' => 'Tanggal Terbit', 'type' => 'date'],
        ['name' => 'summary', 'label' => 'Ringkasan', 'type' => 'richtext'],
        ['name' => 'external_url', 'label' => 'External URL', 'type' => 'url'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'document_path', 'label' => 'Dokumen Regulasi', 'type' => 'file'],
    ];
}
