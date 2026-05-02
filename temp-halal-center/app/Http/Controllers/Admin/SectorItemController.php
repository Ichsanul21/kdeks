<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SectorItemRequest;
use App\Models\SectorItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectorItemController extends BaseCrudController
{
    protected string $modelClass = SectorItem::class;
    protected string $pageTitle = 'Manajemen Direktorat';
    protected string $routePrefix = 'admin.sector-items';
    protected string $requestClass = SectorItemRequest::class;
    protected array $searchColumns = ['title', 'summary', 'icon_key'];
    protected array $tableColumns = [
        ['key' => 'title', 'label' => 'Judul'],
        ['key' => 'icon_key', 'label' => 'Ikon'],
        ['key' => 'sort_order', 'label' => 'Urutan'],
    ];
    protected array $formFields = [
        ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
        ['name' => 'icon_key', 'label' => 'Kunci Ikon Lucide', 'type' => 'text', 'required' => true],
        ['name' => 'summary', 'label' => 'Ringkasan di Kartu', 'type' => 'textarea'],
        ['name' => 'content', 'label' => 'Konten Detail (Detail Page)', 'type' => 'richtext'],
        ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true],
        ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox'],
    ];

    protected ?string $publicShowRoute = 'direktorat.show';

    public function index(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        // AdminDirektorat: redirect langsung ke edit halaman direktorat mereka
        if ($user && $user->hasRole('AdminDirektorat') && $user->sector_item_id) {
            return redirect()->route('admin.sector-items.edit', $user->sector_item_id);
        }

        return parent::index($request);
    }


}
