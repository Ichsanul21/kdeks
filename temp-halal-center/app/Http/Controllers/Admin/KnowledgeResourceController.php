<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\KnowledgeResourceRequest;
use App\Models\KnowledgeResource;

class KnowledgeResourceController extends BaseCrudController
{
    protected string $modelClass = KnowledgeResource::class;
    protected string $pageTitle = 'Ruang Pengetahuan';
    protected string $routePrefix = 'admin.knowledge-resources';
    protected string $requestClass = KnowledgeResourceRequest::class;
    protected array $searchColumns = ['title', 'type', 'summary'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'type', 'label' => 'Tipe'],
        ['key' => 'published_at', 'label' => 'Publikasi'],
    ];
    protected array $publicFileFields = ['thumbnail_path'];
    protected array $privateFileFields = ['document_path'];
    protected array $formFields = [
        ['name' => 'type', 'label' => 'Tipe', 'type' => 'select', 'options' => ['module' => 'Modul', 'ebook' => 'E-Book', 'infographic' => 'Infografik', 'template' => 'Template']],
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'summary', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'content', 'label' => 'Konten', 'type' => 'richtext'],
        ['name' => 'external_url', 'label' => 'External URL', 'type' => 'url'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'published_at', 'label' => 'Publikasi', 'type' => 'datetime-local'],
        ['name' => 'thumbnail_path', 'label' => 'Thumbnail', 'type' => 'image'],
        ['name' => 'document_path', 'label' => 'Dokumen', 'type' => 'file'],
    ];
}
