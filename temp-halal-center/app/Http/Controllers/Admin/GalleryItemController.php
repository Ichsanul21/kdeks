<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GalleryItemRequest;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;

class GalleryItemController extends BaseCrudController
{
    protected string $modelClass = GalleryItem::class;
    protected string $pageTitle = 'Galeri';
    protected string $routePrefix = 'admin.gallery-items';
    protected string $requestClass = GalleryItemRequest::class;
    protected ?string $publicIndexRoute = 'gallery.index';
    protected ?string $publicShowRoute = 'gallery.index';
    protected ?string $publicShowRouteKey = null;
    protected array $searchColumns = ['title', 'media_type', 'caption'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'photo_count_label', 'label' => 'Jumlah Foto'],
        ['key' => 'directorate.title', 'label' => 'Direktorat'],
        ['key' => 'recorded_at', 'label' => 'Tanggal'],
    ];
    protected array $publicFileFields = ['media_path'];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'media_type', 'type' => 'hidden'],
        ['name' => 'caption', 'label' => 'Caption', 'type' => 'textarea'],
        ['name' => 'recorded_at', 'label' => 'Tanggal Dokumentasi', 'type' => 'datetime-local'],
        ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'checkbox'],
        ['name' => 'media_paths', 'label' => 'Foto Galeri (1-5 Foto)', 'type' => 'gallery-photos'],
    ];

    protected function resolvedFields(): array
    {
        $fields = $this->formFields;
        $user = auth()->user();
        $isSuperAdmin = $user->hasAnyRole(['developer', 'superadmin', 'admin']);
        $isAdminDirektorat = $user->isAdminDirektorat();

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
        $item->media_type = 'image';
        $user = auth()->user();
        if ($user->isAdminDirektorat() && !$user->hasAnyRole(['developer', 'superadmin', 'admin'])) {
            $item->sector_item_id = $user->sector_item_id;
        }
        return $this->formView($item, 'create');
    }

    public function store(): RedirectResponse
    {
        $validated = $this->validatedData();

        $files = request()->file('photos', []);
        if (empty($files) && request()->hasFile('media_path')) {
            $files = [request()->file('media_path')];
        }

        $files = array_values(array_filter($files, function ($file) {
            return $file instanceof \Illuminate\Http\UploadedFile && $file->isValid();
        }));

        if (empty($files)) {
            return redirect()->back()
                ->withErrors(['photos' => 'Minimal 1 foto wajib diunggah.'])
                ->withInput();
        }

        if (count($files) > 5) {
            return redirect()->back()
                ->withErrors(['photos' => 'Maksimal 5 foto yang dapat diunggah.'])
                ->withInput();
        }

        $storedPaths = [];
        foreach ($files as $file) {
            $storedPaths[] = $this->mediaStorageService->replace(
                null,
                $file,
                'public',
                $this->storageDirectory()
            );
        }

        $item = new GalleryItem();
        $item->fill($validated);
        $item->media_type = 'image';
        $item->media_path = $storedPaths[0] ?? null;
        $item->media_paths = $storedPaths;
        $item->save();

        return redirect()->route("{$this->routePrefix}.index")
            ->with('status', "Galeri '{$item->title}' (" . count($storedPaths) . " foto) berhasil ditambahkan.");
    }

    public function update(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $item = $this->findModel($id);
        $validated = $this->validatedData($item);

        $item->fill($validated);

        $files = request()->file('photos', []);
        if (empty($files) && request()->hasFile('media_path')) {
            $files = [request()->file('media_path')];
        }

        $files = array_values(array_filter($files, function ($file) {
            return $file instanceof \Illuminate\Http\UploadedFile && $file->isValid();
        }));

        if (!empty($files)) {
            if (count($files) > 5) {
                return redirect()->back()
                    ->withErrors(['photos' => 'Maksimal 5 foto yang dapat diunggah.'])
                    ->withInput();
            }

            // Delete old files
            foreach ($item->all_media_paths as $oldPath) {
                $this->mediaStorageService->delete($oldPath, 'public');
            }

            $storedPaths = [];
            foreach ($files as $file) {
                $storedPaths[] = $this->mediaStorageService->replace(
                    null,
                    $file,
                    'public',
                    $this->storageDirectory()
                );
            }

            $item->media_path = $storedPaths[0] ?? null;
            $item->media_paths = $storedPaths;
        }

        $item->save();

        return redirect()->route("{$this->routePrefix}.index")
            ->with('status', "{$this->pageTitle} berhasil diperbarui.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $item = $this->findModel($id);

        foreach ($item->all_media_paths as $path) {
            $this->mediaStorageService->delete($path, 'public');
        }

        $item->delete();

        return redirect()->route("{$this->routePrefix}.index")
            ->with('status', "{$this->pageTitle} berhasil dihapus.");
    }

    protected function indexQuery()
    {
        return parent::indexQuery()->with('directorate');
    }
}
