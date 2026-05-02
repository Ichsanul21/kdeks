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
    protected ?string $publicIndexRoute = 'resources.index';
    protected ?string $publicShowRoute = 'resources.show';
    protected array $searchColumns = ['title', 'type', 'summary'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'type', 'label' => 'Tipe'],
        ['key' => 'published_at', 'label' => 'Publikasi'],
        ['key' => 'directorate.title', 'label' => 'Direktorat'],
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

    protected function resolvedFields(): array
    {
        $fields = $this->formFields;
        $user = auth()->user();
        $isSuperAdmin = $user->hasAnyRole(['developer', 'superadmin']);
        $isAdminDirektorat = $user->hasRole('AdminDirektorat');

        $directorates = \App\Models\SectorItem::orderBy('title')->pluck('title', 'id')->toArray();

        // Add directorate field
        $fields[] = [
            'name' => 'sector_item_id',
            'label' => 'Direktorat',
            'type' => 'select',
            'options' => $directorates,
            'required' => false,
            'readonly' => $isAdminDirektorat && !$isSuperAdmin,
        ];

        return $fields;
    }

    public function create(): \Illuminate\View\View
    {
        $item = new $this->modelClass();
        $user = auth()->user();
        if ($user->hasRole('AdminDirektorat') && !$user->hasAnyRole(['developer', 'superadmin'])) {
            $item->sector_item_id = $user->sector_item_id;
        }
        return $this->formView($item, 'create');
    }

    protected function indexQuery()
    {
        return parent::indexQuery()->with('directorate');
    }
}
