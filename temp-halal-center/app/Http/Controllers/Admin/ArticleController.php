<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;

class ArticleController extends BaseCrudController
{
    protected string $modelClass = Article::class;
    protected string $pageTitle = 'Artikel & Publikasi';
    protected string $routePrefix = 'admin.articles';
    protected string $requestClass = ArticleRequest::class;
    protected ?string $publicIndexRoute = 'articles.index';
    protected ?string $publicShowRoute = 'articles.show';
    protected array $searchColumns = ['title', 'author_name', 'type', 'status'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'type', 'label' => 'Tipe'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'published_at', 'label' => 'Tanggal'],
        ['key' => 'directorate.title', 'label' => 'Direktorat'],
    ];
    protected array $publicFileFields = ['cover_image_path'];
    protected array $privateFileFields = ['document_path'];
    protected array $formFields = [
        ['name' => 'type', 'label' => 'Tipe Konten', 'type' => 'select', 'options' => ['news' => 'Berita', 'publication' => 'Publikasi', 'research' => 'Riset']],
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'excerpt', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'body', 'label' => 'Isi Konten', 'type' => 'richtext', 'required' => true],
        ['name' => 'author_name', 'label' => 'Penulis', 'type' => 'text', 'readonly' => true],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'published_at', 'label' => 'Tanggal Publikasi', 'type' => 'datetime-local'],
        ['name' => 'cover_image_path', 'label' => 'Cover', 'type' => 'image'],
        ['name' => 'document_path', 'label' => 'Dokumen Pendukung', 'type' => 'file'],
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
        $user = auth()->user();
        $item->author_name = $user->name;
        if ($user->hasRole('AdminDirektorat') && !$user->hasAnyRole(['developer', 'superadmin'])) {
            $item->sector_item_id = $user->sector_item_id;
        }
        return $this->formView($item, 'create');
    }

    public function edit(string $id): \Illuminate\View\View
    {
        $item = $this->findModel($id);
        // Don't overwrite author_name if editing, but keep it for display
        return $this->formView($item, 'edit');
    }

    protected function validatedData(?\Illuminate\Database\Eloquent\Model $model = null): array
    {
        $validated = parent::validatedData($model);
        if (!$model) {
            $validated['author_name'] = auth()->user()->name;
        }
        return $validated;
    }

    protected function indexQuery()
    {
        return parent::indexQuery()->with('directorate');
    }
}
