<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MilestoneRequest;
use App\Models\Milestone;

class MilestoneController extends BaseCrudController
{
    protected string $modelClass = Milestone::class;
    protected string $pageTitle = 'Timeline / Tonggak Perjalanan';
    protected string $routePrefix = 'admin.milestones';
    protected string $requestClass = MilestoneRequest::class;
    protected array $searchColumns = ['year', 'title', 'sub_title'];
    protected array $tableColumns = [
        ['key' => 'year', 'label' => 'Tahun'],
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
    ];
    protected array $formFields = [
        ['name' => 'year', 'label' => 'Tahun', 'type' => 'text', 'required' => true],
        ['name' => 'sub_title', 'label' => 'Sub Judul (mis. Tanggal)', 'type' => 'text'],
        ['name' => 'title', 'label' => 'Judul Milestone', 'type' => 'text', 'required' => true],
        ['name' => 'color', 'label' => 'Warna', 'type' => 'select', 'options' => [
            'emerald' => 'Emerald (Hijau)',
            'cyan' => 'Cyan (Biru Muda)',
            'blue' => 'Blue (Biru)',
            'violet' => 'Violet (Ungu)',
            'amber' => 'Amber (Kuning)',
            'rose' => 'Rose (Merah Muda)',
        ]],
        ['name' => 'icon', 'label' => 'Icon (Lucide Name)', 'type' => 'text', 'required' => true],
        ['name' => 'items', 'label' => 'Poin-poin Detail (JSON atau List)', 'type' => 'textarea', 'placeholder' => '["Poin 1", "Poin 2"]'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
        ['name' => 'image_path', 'label' => 'Gambar (Opsional)', 'type' => 'image'],
    ];

    protected array $publicFileFields = ['image_path'];

    protected function validatedData(?\Illuminate\Database\Eloquent\Model $model = null): array
    {
        $data = parent::validatedData($model);
        
        // Handle items as JSON
        if (is_string($data['items'] ?? null)) {
            $decoded = json_decode($data['items'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['items'] = $decoded;
            } else {
                // If not valid JSON, treat as newline separated
                $data['items'] = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $data['items']))));
            }
        }
        
        return $data;
    }
    protected function formView(\Illuminate\Database\Eloquent\Model $item, string $mode): \Illuminate\View\View
    {
        if (is_array($item->items)) {
            $item->items = implode("\n", $item->items);
        }

        return parent::formView($item, $mode);
    }
}
