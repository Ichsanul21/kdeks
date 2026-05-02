<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GalleryItemRequest;
use App\Models\GalleryItem;

class GalleryItemController extends BaseCrudController
{
    protected string $modelClass = GalleryItem::class;
    protected string $pageTitle = 'Galeri';
    protected string $routePrefix = 'admin.gallery-items';
    protected string $requestClass = GalleryItemRequest::class;
    protected ?string $publicIndexRoute = 'gallery.index';
    protected ?string $publicShowRoute = 'gallery.index';
    protected ?string $publicShowRouteKey = null;
    protected array $searchColumns = ['title', 'media_type', 'caption'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'directorate.title', 'label' => 'Direktorat'],
        ['key' => 'media_type', 'label' => 'Jenis'],
        ['key' => 'recorded_at', 'label' => 'Tanggal'],
    ];
    protected array $publicFileFields = ['media_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'media_type', 'type' => 'hidden'],
        ['name' => 'caption', 'label' => 'Caption', 'type' => 'textarea'],
        ['name' => 'recorded_at', 'label' => 'Tanggal Dokumentasi', 'type' => 'datetime-local'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'media_path', 'label' => 'File Media', 'type' => 'file'],
    ];

    protected function resolvedFields(): array
    {
        $fields = $this->formFields;
        $user = auth()->user();
        $isSuperAdmin = $user->hasAnyRole(['developer', 'superadmin']);
        $isAdminDirektorat = $user->hasRole('AdminDirektorat');

        $directorates = \App\Models\SectorItem::orderBy('title')->pluck('title', 'id')->toArray();

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
        $item->media_type = 'image';
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
