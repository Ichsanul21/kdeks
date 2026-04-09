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
    ];
    protected array $publicFileFields = ['cover_image_path'];
    protected array $privateFileFields = ['document_path'];
    protected array $formFields = [
        ['name' => 'type', 'label' => 'Tipe Konten', 'type' => 'select', 'options' => ['news' => 'Berita', 'publication' => 'Publikasi', 'research' => 'Riset']],
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'title_en', 'label' => 'Judul EN', 'type' => 'text'],
        ['name' => 'excerpt', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'excerpt_en', 'label' => 'Ringkasan EN', 'type' => 'textarea'],
        ['name' => 'body', 'label' => 'Isi Konten', 'type' => 'richtext', 'required' => true],
        ['name' => 'body_en', 'label' => 'Isi Konten EN', 'type' => 'richtext'],
        ['name' => 'author_name', 'label' => 'Penulis', 'type' => 'text'],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'published_at', 'label' => 'Tanggal Publikasi', 'type' => 'datetime-local'],
        ['name' => 'cover_image_path', 'label' => 'Cover', 'type' => 'image'],
        ['name' => 'document_path', 'label' => 'Dokumen Pendukung', 'type' => 'file'],
    ];
}
