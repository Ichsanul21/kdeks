<?php

namespace Database\Seeders;

use App\Models\OrganizationMember;
use Illuminate\Database\Seeder;

class OrganizationStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationMember::truncate();

        // ===== KNEKS =====
        $kneksExec = OrganizationMember::create([
            'name' => 'Sholahudin Al Aiyub',
            'role_title' => 'Direktur Eksekutif',
            'category' => 'kneks',
            'sort_order' => 1,
        ]);

        $kneksData = [
            [
                'title' => 'Industri Produk Halal',
                'name' => null, // Vacant
                'subs' => [
                    ['title' => 'Deputi Infrastruktur Industri Halal / Plt. Deputi Pengembangan HAS', 'name' => 'Binsar Agung Hartanto Sitompul'],
                    ['title' => 'Deputi Rantai Nilai Produk Halal', 'name' => 'Umar Aditiawarman, Ph.D.'],
                ],
            ],
            [
                'title' => 'Jasa Keuangan Syariah',
                'name' => null, // Vacant
                'subs' => [],
            ],
            [
                'title' => 'Keuangan Sosial Syariah',
                'name' => 'Dr. Dwi Irianti Hadiningdyah, S.H., M.A.',
                'subs' => [
                    ['title' => 'Deputi Inklusi Keuangan Syariah / Plt. Deputi Perbankan Syariah', 'name' => 'Eka Jati Rahayu Firmansyah, S.H.I., M.E.I.'],
                    ['title' => 'Deputi LKMS / Plt. Deputi Dana Sosial Syariah', 'name' => 'Bagus Aryo, Ph.D'],
                ],
            ],
            [
                'title' => 'Bisnis & Kewirausahaan Syariah',
                'name' => 'Ir. H. Putu Rahwidhiyasa, MBA',
                'subs' => [
                    ['title' => 'Deputi Kemitraan dan Akselerasi Usaha Syariah', 'name' => 'Achmad Iqbal, SP., M.E.'],
                    ['title' => 'Deputi Bisnis Digital dan Pusat Data Ekonomi Syariah', 'name' => 'Dedi Wibowo, S.E., M.M., Ph.D., CCRM.'],
                    ['title' => 'Deputi Inkubasi Bisnis Syariah', 'name' => 'Helma Agustiawan'],
                ],
            ],
            [
                'title' => 'Infrastruktur Ekosistem Syariah',
                'name' => 'Sutan Emir Hidayat, M.B.A., Ph.D.',
                'subs' => [
                    ['title' => 'Deputi Hukum Pengembangan Ekonomi Syariah', 'name' => 'Dr. Dece Kurniadi, SH., MM.'],
                    ['title' => 'Deputi Pengembangan SDM Ekonomi Syariah / Plt. Deputi Riset Ekonomi Syariah', 'name' => 'Mohamad Soleh Nurzaman, Ph.D'],
                    ['title' => 'Deputi Promosi dan Kerja Sama Strategis', 'name' => 'Drs. Inza Putra, MM'],
                ],
            ],
        ];

        foreach ($kneksData as $i => $dir) {
            $parent = OrganizationMember::create([
                'name' => $dir['name'] ?? 'Belum Ditentukan',
                'role_title' => 'Direktur ' . $dir['title'],
                'category' => 'kneks',
                'parent_id' => $kneksExec->id,
                'sort_order' => $i + 1,
            ]);

            foreach ($dir['subs'] as $j => $sub) {
                OrganizationMember::create([
                    'name' => $sub['name'],
                    'role_title' => $sub['title'],
                    'category' => 'kneks',
                    'parent_id' => $parent->id,
                    'sort_order' => $j + 1,
                ]);
            }
        }

        // ===== KDEKS =====
        $kdeksExec = OrganizationMember::create([
            'name' => 'Muhammad Edwin, S.Kom, MM',
            'role_title' => 'Direktur Eksekutif',
            'category' => 'kdeks',
            'sort_order' => 1,
        ]);

        $kdeksData = [
            [
                'title' => 'Industri Produk Halal',
                'name' => 'drh. H. Marsongko',
                'subs' => [
                    ['title' => 'Kepala Divisi Pengembangan Halal Assurance System', 'name' => 'DR. Aswita'],
                    ['title' => 'Kepala Divisi Infrastruktur Industri Halal', 'name' => 'drh. Siti Saniatun Saadah, M.Si.'],
                    ['title' => 'Kepala Divisi Rantai Nilai Produk Halal', 'name' => 'Fitria Rahmah, S.E.I., M.A'],
                ],
            ],
            [
                'title' => 'Jasa Keuangan Syariah',
                'name' => 'Denny Irfani, SE',
                'subs' => [
                    ['title' => 'Kepala Divisi Perbankan Syariah', 'name' => 'Bagus Sulistyo'],
                    ['title' => 'Kepala Divisi Jasa Keuangan Non-Bank Syariah', 'name' => 'Andika Dwi Prasetyo'],
                    ['title' => 'Kepala Divisi Pasar Modal Syariah', 'name' => 'Isna Yuningsih, SE, MM.'],
                ],
            ],
            [
                'title' => 'Keuangan Sosial Syariah',
                'name' => 'Sumadi Buton, S.Hut, ME.',
                'subs' => [
                    ['title' => 'Kepala Divisi Dana Sosial Syariah & LKMS', 'name' => 'Muhammad Iswadi, MSI'],
                    ['title' => 'Kepala Divisi Inklusi Keuangan Sosial Syariah', 'name' => 'Dr. Hj. Sri Wahyuni, SE,. M.Si'],
                ],
            ],
            [
                'title' => 'Bisnis & Kewirausahaan Syariah',
                'name' => 'Roni Suhendar, ST.',
                'subs' => [
                    ['title' => "Kepala Divisi Kemitraan, Akselerasi Usaha & Inkubasi Bisnis Syariah", 'name' => "Naf'an"],
                    ['title' => 'Kepala Divisi Bisnis Digital & Pusat Data Ekonomi Syariah', 'name' => 'Ike Purnamasari, SE., M.M., Ph.D.'],
                ],
            ],
            [
                'title' => 'Infrastruktur Ekosistem Syariah',
                'name' => 'Prof. DR. Bambang Iswanto, S.Ag., MH.',
                'subs' => [
                    ['title' => 'Kepala Divisi Hukum Pengembangan Ekonomi Syariah', 'name' => 'Akhmad Nur Zaroni, M.Ag.'],
                    ['title' => 'Kepala Divisi Promosi & Kerjasama Strategis', 'name' => 'Deni Dwi Arifendi'],
                    ['title' => 'Kepala Divisi Pengembangan SDM Ekonomi Syariah & Riset Ekonomi Syariah', 'name' => 'Dharma Yanti, SE., M.Si.'],
                ],
            ],
        ];

        foreach ($kdeksData as $i => $dir) {
            $parent = OrganizationMember::create([
                'name' => $dir['name'],
                'role_title' => 'Direktur ' . $dir['title'],
                'category' => 'kdeks',
                'parent_id' => $kdeksExec->id,
                'sort_order' => $i + 1,
            ]);

            foreach ($dir['subs'] as $j => $sub) {
                OrganizationMember::create([
                    'name' => $sub['name'],
                    'role_title' => $sub['title'],
                    'category' => 'kdeks',
                    'parent_id' => $parent->id,
                    'sort_order' => $j + 1,
                ]);
            }
        }
    }
}
