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
    protected array $searchColumns = ['title', 'media_type', 'caption'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'media_type', 'label' => 'Jenis'],
        ['key' => 'recorded_at', 'label' => 'Tanggal'],
    ];
    protected array $publicFileFields = ['media_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'media_type', 'label' => 'Jenis Media', 'type' => 'select', 'options' => ['image' => 'Image', 'video' => 'Video']],
        ['name' => 'caption', 'label' => 'Caption', 'type' => 'textarea'],
        ['name' => 'external_video_url', 'label' => 'Video URL', 'type' => 'url'],
        ['name' => 'recorded_at', 'label' => 'Tanggal Dokumentasi', 'type' => 'datetime-local'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'media_path', 'label' => 'File Media', 'type' => 'file'],
    ];
}
