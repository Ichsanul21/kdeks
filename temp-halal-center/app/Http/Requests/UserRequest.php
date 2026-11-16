<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $currentUser = $this->user();
        if (!$currentUser) {
            return false;
        }

        if ($currentUser->hasAnyRole(['developer', 'superadmin'])) {
            return true;
        }

        if ($currentUser->isAdminDirektorat()) {
            $userId = $this->route('user') ?: $this->input('current_model_id');
            if ($userId) {
                $targetUser = \App\Models\User::find($userId);
                if ($targetUser && (int) $targetUser->sector_item_id !== (int) $currentUser->sector_item_id) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ?: $this->input('current_model_id');
        $currentUser = $this->user();
        $isAdminDirektorat = $currentUser && $currentUser->isAdminDirektorat() && !$currentUser->hasAnyRole(['developer', 'superadmin']);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role' => [$isAdminDirektorat ? 'nullable' : 'required', 'string', 'exists:roles,name'],
            'sector_item_id' => [($isAdminDirektorat ? 'nullable' : 'required_if:role,AdminDirektorat'), 'nullable', 'exists:sector_items,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Lengkap',
            'email' => 'Alamat Email',
            'password' => 'Kata Sandi',
            'role' => 'Peran / Hak Akses',
        ];
    }
}
