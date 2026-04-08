<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FrequentlyAskedQuestionRequest;
use App\Models\FrequentlyAskedQuestion;

class FrequentlyAskedQuestionController extends BaseCrudController
{
    protected string $modelClass = FrequentlyAskedQuestion::class;
    protected string $pageTitle = 'FAQ';
    protected string $routePrefix = 'admin.frequently-asked-questions';
    protected string $requestClass = FrequentlyAskedQuestionRequest::class;
    protected array $searchColumns = ['question', 'answer'];
    protected array $tableColumns = [
        ['key' => 'question', 'label' => 'Pertanyaan'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
        ['key' => 'is_featured', 'label' => 'Tampil'],
    ];
    protected array $formFields = [
        ['name' => 'question', 'label' => 'Pertanyaan', 'type' => 'text', 'required' => true],
        ['name' => 'answer', 'label' => 'Jawaban', 'type' => 'richtext', 'required' => true],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
        ['name' => 'is_featured', 'label' => 'Tampilkan di Beranda', 'type' => 'checkbox'],
    ];
}
