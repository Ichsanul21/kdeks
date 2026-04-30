<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends BaseCrudController
{
    protected string $modelClass = User::class;
    protected string $pageTitle = 'Manajemen Pengguna';
    protected string $routePrefix = 'admin.users';
    protected string $requestClass = UserRequest::class;

    protected array $tableColumns = [
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'email', 'label' => 'Email'],
    ];


    protected array $searchColumns = ['name', 'email'];

    protected array $formFields = [
        ['name' => 'name', 'label' => 'Nama Lengkap', 'type' => 'text'],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'password', 'label' => 'Kata Sandi', 'type' => 'password'],
        ['name' => 'password_confirmation', 'label' => 'Konfirmasi Kata Sandi', 'type' => 'password'],
        ['name' => 'role', 'label' => 'Peran / Role', 'type' => 'select', 'options' => []],
    ];

    protected function resolvedFields(): array
    {
        $fields = $this->formFields;
        $roles = Role::all()->pluck('name', 'name')->toArray();

        foreach ($fields as &$field) {
            if ($field['name'] === 'role') {
                $field['options'] = $roles;
            }
        }

        return $fields;
    }

    public function store(): RedirectResponse
    {
        $validated = $this->validatedData();
        
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()->route("{$this->routePrefix}.index")->with('status', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function update(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $user = User::findOrFail($id);
        $validated = $this->validatedData($user);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()->route("{$this->routePrefix}.index")->with('status', "Pengguna {$user->name} berhasil diperbarui.");
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
