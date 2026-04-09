<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventController extends BaseCrudController
{
    protected string $modelClass = Event::class;
    protected string $pageTitle = 'Agenda';
    protected string $routePrefix = 'admin.events';
    protected string $requestClass = EventRequest::class;
    protected ?string $publicIndexRoute = 'events.index';
    protected ?string $publicShowRoute = 'events.show';
    protected array $searchColumns = ['title', 'location_name', 'status'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Agenda'],
        ['key' => 'location_name', 'label' => 'Lokasi'],
        ['key' => 'starts_at', 'label' => 'Mulai'],
        ['key' => 'status', 'label' => 'Status'],
    ];
    protected array $publicFileFields = ['banner_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul Agenda', 'type' => 'text', 'required' => true],
        ['name' => 'summary', 'label' => 'Ringkasan', 'type' => 'textarea'],
        ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'richtext'],
        ['name' => 'starts_at', 'label' => 'Mulai', 'type' => 'datetime-local', 'required' => true],
        ['name' => 'ends_at', 'label' => 'Selesai', 'type' => 'datetime-local'],
        ['name' => 'location_name', 'label' => 'Lokasi', 'type' => 'text'],
        ['name' => 'meeting_url', 'label' => 'Meeting URL', 'type' => 'url'],
        ['name' => 'registration_url', 'label' => 'Registrasi URL', 'type' => 'url'],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['published' => 'Published', 'draft' => 'Draft']],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'banner_path', 'label' => 'Banner', 'type' => 'image'],
    ];
}
