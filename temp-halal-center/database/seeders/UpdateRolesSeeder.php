<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UpdateRolesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Rename 'admin' role to 'superadmin' if it exists
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->name = 'superadmin';
            $adminRole->save();
        } else {
            Role::firstOrCreate(['name' => 'superadmin']);
        }

        // 2. Ensure other roles exist
        Role::firstOrCreate(['name' => 'developer']);
        Role::firstOrCreate(['name' => 'editor']);

        // 3. Create specific Super Admin account
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@kdeks.kaltimprov.go.id'],
            [
                'name' => 'Super Admin KDEKS',
                'password' => \Illuminate\Support\Facades\Hash::make('KDEKSAdmin@2026'),
            ]
        );
        $superAdmin->syncRoles(['superadmin']);

        // 4. Update existing users who might have the old 'admin' role name in cache or if role sync is needed

    }
}
