<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ConsultationRequestStoreRequest;
use App\Models\ConsultationRequest;

class ConsultationRequestController extends BaseCrudController
{
    protected string $modelClass = ConsultationRequest::class;
    protected string $pageTitle = 'Permintaan Konsultasi';
    protected string $routePrefix = 'admin.consultation-requests';
    protected string $requestClass = ConsultationRequestStoreRequest::class;
    protected array $searchColumns = ['name', 'email', 'phone', 'subject', 'status'];
    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'subject', 'label' => 'Subjek'],
        ['key' => 'preferred_language', 'label' => 'Bahasa'],
        ['key' => 'status', 'label' => 'Status'],
    ];
    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
        ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
        ['name' => 'organization_name', 'label' => 'Organisasi', 'type' => 'text'],
        ['name' => 'subject', 'label' => 'Subjek', 'type' => 'text', 'required' => true],
        ['name' => 'message', 'label' => 'Pesan', 'type' => 'richtext', 'required' => true],
        ['name' => 'preferred_language', 'label' => 'Bahasa', 'type' => 'select', 'options' => ['id' => 'Indonesia', 'en' => 'English']],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['new' => 'New', 'in_review' => 'In Review', 'completed' => 'Completed']],
    ];
}
