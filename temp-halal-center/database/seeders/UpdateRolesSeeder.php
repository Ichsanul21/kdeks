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
        // 1. Ensure standard roles exist
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);
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

        // 4b. Create Admin KDEKS account (access to all modules except user management)
        $adminKdeks = User::updateOrCreate(
            ['email' => 'adminkdeks@kdeks.kaltimprov.go.id'],
            [
                'name' => 'Admin KDEKS',
                'password' => Hash::make('KDEKS@2026'),
                'sector_item_id' => null,
            ]
        );
        $adminKdeks->syncRoles(['admin']);

        // 5. Ensure admin accounts for all 5 directorates exist
        $directorates = [
            [
                'slug' => 'industri-produk-halal',
                'name' => 'Admin Industri Produk Halal',
                'emails' => ['adminiph@kdeks.kaltimprov.go.id', 'adminiph@halalcenter.test'],
            ],
            [
                'slug' => 'jasa-keuangan-syariah',
                'name' => 'Admin Jasa Keuangan Syariah',
                'emails' => ['adminjks@kdeks.kaltimprov.go.id', 'adminjks@halalcenter.test'],
            ],
            [
                'slug' => 'keuangan-sosial-syariah',
                'name' => 'Admin Keuangan Sosial Syariah',
                'emails' => ['adminkss@kdeks.kaltimprov.go.id', 'adminkss@halalcenter.test'],
            ],
            [
                'slug' => 'bisnis-kewirausahaan-syariah',
                'name' => 'Admin Bisnis dan Kewirausahaan Syariah',
                'emails' => ['adminbks@kdeks.kaltimprov.go.id', 'adminbks@halalcenter.test'],
            ],
            [
                'slug' => 'infrastruktur-ekosistem-syariah',
                'name' => 'Admin Infrastruktur Ekosistem Syariah',
                'emails' => ['adminies@kdeks.kaltimprov.go.id', 'adminies@halalcenter.test'],
            ],
        ];

        foreach ($directorates as $dir) {
            $sector = SectorItem::where('slug', $dir['slug'])->first();
            $sectorId = $sector?->id;

            foreach ($dir['emails'] as $email) {
                $adminUser = User::updateOrCreate(
                    ['email' => $email],
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
}
