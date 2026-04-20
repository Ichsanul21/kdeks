<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProgramSlideRequest;
use App\Models\ProgramSlide;

class ProgramSlideController extends BaseCrudController
{
    protected string $modelClass = ProgramSlide::class;
    protected string $pageTitle = 'Program Unggulan';
    protected string $routePrefix = 'admin.program-slides';
    protected string $requestClass = ProgramSlideRequest::class;
    protected array $searchColumns = ['title', 'subtitle', 'status'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'published_at', 'label' => 'Publikasi'],
    ];
    protected array $publicFileFields = ['image_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'title_en', 'label' => 'Judul EN', 'type' => 'text'],
        ['name' => 'subtitle', 'label' => 'Subjudul', 'type' => 'text'],
        ['name' => 'subtitle_en', 'label' => 'Subjudul EN', 'type' => 'text'],
        ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'richtext'],
        ['name' => 'description_en', 'label' => 'Deskripsi EN', 'type' => 'richtext'],
        ['name' => 'cta_label', 'label' => 'Label Tombol', 'type' => 'text'],
        ['name' => 'cta_url', 'label' => 'URL Tombol', 'type' => 'url'],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
        ['name' => 'starts_at', 'label' => 'Mulai Tayang', 'type' => 'datetime-local'],
        ['name' => 'ends_at', 'label' => 'Selesai Tayang', 'type' => 'datetime-local'],
        ['name' => 'published_at', 'label' => 'Tanggal Publikasi', 'type' => 'datetime-local'],
        ['name' => 'image_path', 'label' => 'Gambar Banner', 'type' => 'image'],
    ];
}
