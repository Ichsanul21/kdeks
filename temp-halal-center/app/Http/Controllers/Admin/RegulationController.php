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
    protected ?string $publicIndexRoute = 'regulations.index';
    protected ?string $publicShowRoute = 'regulations.show';
    protected array $searchColumns = ['title', 'regulation_type', 'regulation_number'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'regulation_type', 'label' => 'Jenis'],
        ['key' => 'regulation_number', 'label' => 'Nomor'],
        ['key' => 'issued_at', 'label' => 'Tanggal'],
        ['key' => 'directorate.title', 'label' => 'Direktorat'],
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

    protected function resolvedFields(): array
    {
        $fields = $this->formFields;
        $user = auth()->user();
        $isSuperAdmin = $user->hasAnyRole(['developer', 'superadmin']);
        $isAdminDirektorat = $user->hasRole('AdminDirektorat');

        $directorates = \App\Models\SectorItem::orderBy('title')->pluck('title', 'id')->toArray();

        // Add directorate field at the beginning or end
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
