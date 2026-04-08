<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MentorRequest;
use App\Models\Mentor;
use App\Models\Region;

class MentorController extends BaseCrudController
{
    protected string $modelClass = Mentor::class;
    protected string $pageTitle = 'Pendamping PPH';
    protected string $routePrefix = 'admin.mentors';
    protected string $requestClass = MentorRequest::class;
    protected array $searchColumns = ['name', 'email', 'phone', 'district_coverage'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'region.name', 'label' => 'Wilayah'],
        ['key' => 'phone', 'label' => 'Kontak'],
        ['key' => 'is_active', 'label' => 'Aktif'],
    ];
    protected array $publicFileFields = ['photo_path'];

    protected function resolvedFields(): array
    {
        $regions = Region::query()->orderBy('name')->pluck('name', 'id')->all();

        return [
            ['name' => 'region_id', 'label' => 'Wilayah', 'type' => 'select', 'options' => $regions],
            ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['name' => 'expertise', 'label' => 'Keahlian', 'type' => 'text'],
            ['name' => 'district_coverage', 'label' => 'Cakupan', 'type' => 'text'],
            ['name' => 'bio', 'label' => 'Bio', 'type' => 'richtext'],
            ['name' => 'certification_number', 'label' => 'No. Sertifikat', 'type' => 'text'],
            ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox'],
            ['name' => 'photo_path', 'label' => 'Foto', 'type' => 'image'],
        ];
    }
}
