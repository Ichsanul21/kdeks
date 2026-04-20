<?php

namespace App\Http\Controllers\Admin;

use App\Models\GuestMessage;

class ConsultationRequestController extends BaseCrudController
{
    protected string $modelClass = GuestMessage::class;
    protected string $pageTitle = 'Buku Tamu (Kontak)';
    protected string $routePrefix = 'admin.consultation-requests';
    protected string $requestClass = \Illuminate\Http\Request::class; // Using base request since validation is simple or can be defined here
    protected array $searchColumns = ['name', 'email', 'phone', 'subject'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'phone', 'label' => 'Telepon'],
        ['key' => 'subject', 'label' => 'Subjek'],
        ['key' => 'created_at', 'label' => 'Tanggal'],
    ];
    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
        ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
        ['name' => 'subject', 'label' => 'Subjek', 'type' => 'text', 'required' => true],
        ['name' => 'message', 'label' => 'Pesan', 'type' => 'richtext', 'required' => true],
    ];

    protected function validatedData(?\Illuminate\Database\Eloquent\Model $model = null): array
    {
        return request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
    }
}
