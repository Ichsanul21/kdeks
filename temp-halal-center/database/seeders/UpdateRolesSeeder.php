<?php

namespace Database\Seeders;

use App\Models\SectorItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

        // 2. Ensure standard roles exist
        Role::firstOrCreate(['name' => 'developer']);
        Role::firstOrCreate(['name' => 'editor']);
        Role::firstOrCreate(['name' => 'AdminDirektorat']);

        // 3. Ensure 5 directorate-specific roles exist
        $directorateRoles = [
            'industri-produk-halal',
            'jasa-keuangan-syariah',
            'keuangan-sosial-syariah',
            'bisnis-kewirausahaan-syariah',
            'infrastruktur-ekosistem-syariah',
        ];
        foreach ($directorateRoles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 4. Create specific Super Admin account
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@kdeks.kaltimprov.go.id'],
            [
                'name' => 'Super Admin KDEKS',
                'password' => Hash::make('KDEKSAdmin@2026'),
            ]
        );
        $superAdmin->syncRoles(['superadmin']);

        // 5. Ensure admin accounts for all 5 directorates exist
        $directorates = [
            [
                'slug' => 'industri-produk-halal',
                'name' => 'Admin Industri Produk Halal',
                'email' => 'adminiph@halalcenter.test',
            ],
            [
                'slug' => 'jasa-keuangan-syariah',
                'name' => 'Admin Jasa Keuangan Syariah',
                'email' => 'adminjks@halalcenter.test',
            ],
            [
                'slug' => 'keuangan-sosial-syariah',
                'name' => 'Admin Keuangan Sosial Syariah',
                'email' => 'adminkss@halalcenter.test',
            ],
            [
                'slug' => 'bisnis-kewirausahaan-syariah',
                'name' => 'Admin Bisnis dan Kewirausahaan Syariah',
                'email' => 'adminbks@halalcenter.test',
            ],
            [
                'slug' => 'infrastruktur-ekosistem-syariah',
                'name' => 'Admin Infrastruktur Ekosistem Syariah',
                'email' => 'adminies@halalcenter.test',
            ],
        ];

        foreach ($directorates as $dir) {
            $sector = SectorItem::where('slug', $dir['slug'])->first();
            $sectorId = $sector?->id;

            $adminUser = User::updateOrCreate(
                ['email' => $dir['email']],
                [
                    'name' => $dir['name'],
                    'password' => Hash::make('password'),
                    'sector_item_id' => $sectorId,
                ]
            );
            $adminUser->syncRoles(['AdminDirektorat', $dir['slug']]);
        }
    }
}
