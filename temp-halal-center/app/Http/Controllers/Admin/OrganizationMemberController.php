<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrganizationMemberRequest;
use App\Models\OrganizationMember;

class OrganizationMemberController extends BaseCrudController
{
    protected string $modelClass = OrganizationMember::class;
    protected string $pageTitle = 'Struktur Organisasi';
    protected string $routePrefix = 'admin.organization-members';
    protected string $requestClass = OrganizationMemberRequest::class;
    protected array $searchColumns = ['name', 'role_title', 'expertise'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'role_title', 'label' => 'Jabatan'],
        ['key' => 'expertise', 'label' => 'Keahlian'],
    ];
    protected array $publicFileFields = ['photo_path'];

    protected function resolvedFields(): array
    {
        $members = OrganizationMember::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();

        return [
            ['name' => 'category', 'label' => 'Kategori', 'type' => 'select', 'options' => ['kneks' => 'KNEKS', 'kdeks' => 'KDEKS']],
            ['name' => 'parent_id', 'label' => 'Atasan (Parent)', 'type' => 'select', 'options' => $members],
            ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'role_title', 'label' => 'Jabatan', 'type' => 'text', 'required' => true],
            ['name' => 'bio', 'label' => 'Bio', 'type' => 'richtext'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
            ['name' => 'expertise', 'label' => 'Keahlian', 'type' => 'text'],
            ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
            ['name' => 'is_board_member', 'label' => 'Dewan Pakar', 'type' => 'checkbox'],
            ['name' => 'photo_path', 'label' => 'Foto', 'type' => 'image'],
        ];
    }
}
