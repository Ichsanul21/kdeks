<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class UserController extends BaseCrudController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function ($request, $next) {
                $user = auth()->user();
                if ($user && ($user->hasRole('admin') || $user->email === 'adminkdeks@kdeks.kaltimprov.go.id')) {
                    abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengelola pengguna.');
                }
                return $next($request);
            },
        ];
    }

    protected string $modelClass = User::class;
    protected string $pageTitle = 'Manajemen Pengguna';
    protected string $routePrefix = 'admin.users';
    protected string $requestClass = UserRequest::class;

    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'role_label', 'label' => 'Peran'],
        ['key' => 'directorate.title', 'label' => 'Direktorat'],
    ];

    protected array $searchColumns = ['name', 'email'];

    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama Lengkap', 'type' => 'text'],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'password', 'label' => 'Kata Sandi', 'type' => 'password'],
        ['name' => 'password_confirmation', 'label' => 'Konfirmasi Kata Sandi', 'type' => 'password'],
        ['name' => 'role', 'label' => 'Peran / Role', 'type' => 'select', 'options' => [], 'id' => 'role-select'],
        ['name' => 'sector_item_id', 'label' => 'Direktorat', 'type' => 'select', 'options' => [], 'id' => 'directorate-container'],
    ];

    protected function indexQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::indexQuery()->with(['directorate', 'roles']);
        $user = auth()->user();

        if (!$user->hasRole('developer')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'developer');
            });
        }

        if ($user->isAdminDirektorat() && !$user->hasAnyRole(['developer', 'superadmin'])) {
            $query->where('sector_item_id', $user->sector_item_id);
        }

        return $query;
    }

    protected function resolvedFields(): array
    {
        $fields = $this->formFields;
        $currentUser = auth()->user();
        $isAdminDirektorat = $currentUser && $currentUser->isAdminDirektorat() && !$currentUser->hasAnyRole(['developer', 'superadmin']);

        if ($isAdminDirektorat) {
            $roles = ['AdminDirektorat' => 'Admin Direktorat'];
            $directorateTitle = $currentUser->assigned_directorate?->title ?? 'Direktorat Saya';
            $sectors = [$currentUser->sector_item_id => $directorateTitle];
        } else {
            $rolesQuery = Role::query();
            if (!$currentUser->hasRole('developer')) {
                $rolesQuery->where('name', '!=', 'developer');
            }
            $roles = $rolesQuery->pluck('name', 'name')->toArray();
            $sectors = \App\Models\SectorItem::where('is_active', true)->orderBy('sort_order')->pluck('title', 'id')->toArray();
        }

        foreach ($fields as &$field) {
            if ($field['name'] === 'role') {
                $field['options'] = $roles;
                if ($isAdminDirektorat) {
                    $field['readonly'] = true;
                }
            }
            if ($field['name'] === 'sector_item_id') {
                $field['options'] = $sectors;
                if ($isAdminDirektorat) {
                    $field['readonly'] = true;
                }
            }
        }

        return $fields;
    }

    public function create(): \Illuminate\View\View
    {
        $item = new $this->modelClass();
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->isAdminDirektorat() && !$currentUser->hasAnyRole(['developer', 'superadmin'])) {
            $item->sector_item_id = $currentUser->sector_item_id;
            $item->role = 'AdminDirektorat';
        }
        return $this->formView($item, 'create');
    }

    protected function findModel(string $id): Model
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();

        if ($currentUser && $currentUser->isAdminDirektorat() && !$currentUser->hasAnyRole(['developer', 'superadmin'])) {
            if ((int) $user->sector_item_id !== (int) $currentUser->sector_item_id) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses pengguna direktorat lain.');
            }
        }

        return $user;
    }

    public function store(): RedirectResponse
    {
        $validated = $this->validatedData();
        $currentUser = auth()->user();
        $isAdminDirektorat = $currentUser && $currentUser->isAdminDirektorat() && !$currentUser->hasAnyRole(['developer', 'superadmin']);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        if ($isAdminDirektorat) {
            $user->sector_item_id = $currentUser->sector_item_id;
            $roleToSync = 'AdminDirektorat';
        } else {
            $user->sector_item_id = $validated['role'] === 'AdminDirektorat' ? ($validated['sector_item_id'] ?? null) : null;
            $roleToSync = $validated['role'];
        }

        $user->save();
        $user->syncRoles([$roleToSync]);

        return redirect()->route("{$this->routePrefix}.index")->with('status', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function update(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $user = $this->findModel($id);
        $currentUser = auth()->user();
        $isAdminDirektorat = $currentUser && $currentUser->isAdminDirektorat() && !$currentUser->hasAnyRole(['developer', 'superadmin']);

        $validated = $this->validatedData($user);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($isAdminDirektorat) {
            $user->sector_item_id = $currentUser->sector_item_id;
            $roleToSync = 'AdminDirektorat';
        } else {
            $user->sector_item_id = $validated['role'] === 'AdminDirektorat' ? ($validated['sector_item_id'] ?? null) : null;
            $roleToSync = $validated['role'];
        }

        $user->save();
        $user->syncRoles([$roleToSync]);

        return redirect()->route("{$this->routePrefix}.index")->with('status', "Pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = $this->findModel($id);

        if ((int) $id === (int) auth()->id()) {
            return redirect()->route("{$this->routePrefix}.index")->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route("{$this->routePrefix}.index")->with('status', "Pengguna {$user->name} berhasil dihapus.");
    }

    protected function formView(Model $item, string $mode): \Illuminate\View\View
    {
        // Add existing role to item for the select field
        if ($mode === 'edit') {
            $item->role = $item->getRoleNames()->first();
        }

        return parent::formView($item, $mode);
    }
}
