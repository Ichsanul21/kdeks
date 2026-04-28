<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use App\Models\Milestone;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    public function run(): void
    {
        AboutUs::updateOrCreate(
            ['id' => 1],
            [
                'description' => '<p>Komite Daerah Ekonomi dan Keuangan Syariah (KDEKS) merupakan bagian dari upaya pemerintah Indonesia untuk memperkuat ekonomi syariah di tingkat nasional dan daerah. Pembentukan KDEKS ini terkait erat dengan perkembangan ekonomi syariah yang semakin penting di Indonesia, serta dorongan untuk memperluas implementasi ekonomi syariah ke berbagai daerah di seluruh Indonesia.</p>
                <p>KDEKS Kalimantan Timur dibentuk berdasarkan Keputusan Gubernur Kalimantan Timur. Pembentukan KDEKS merupakan salah satu langkah strategis untuk memastikan bahwa kebijakan dan inisiatif ekonomi syariah yang digagas oleh KNEKS dapat diterapkan secara efektif di daerah-daerah, dengan melibatkan pemerintah daerah, lembaga keuangan syariah, industri halal, serta masyarakat lokal.</p>
                <p>Latar belakang pembentukan KDEKS merupakan sebuah kebijakan untuk memperkuat ekonomi syariah di Indonesia dimulai dengan pembentukan Komite Nasional Keuangan Syariah (KNKS) pada tahun 2016, yang kemudian bertransformasi menjadi KNEKS pada tahun 2020. Selanjutnya, untuk mendorong perkembangan ekonomi syariah secara lebih luas dan menyeluruh, baik di sektor keuangan maupun industri halal, perlu adanya koordinasi di tingkat daerah.</p>
                <p>Oleh karena itu, KDEKS dibentuk sebagai lembaga yang memiliki peran dalam mendukung implementasi strategi ekonomi syariah di daerah.</p>',
                'focus' => "Penguatan koordinasi antar pemangku kepentingan di daerah.\nImplementasi inisiatif ekonomi syariah di sektor industri halal.\nPengembangan lembaga keuangan syariah di Kalimantan Timur.",
            ]
        );

        $milestones = [
            [
                'year' => '2016',
                'sub_title'  => '',
                'title'=> 'Pendirian KNKS',
                'color'=> 'emerald',
                'icon' => 'landmark',
                'items'=> [
                    'Peraturan Presiden No. 91 Tahun 2016 Tgl. 08/11/2016 tentang Komite Nasional Keuangan Syariah',
                    'KNKS bertujuan mendukung pembangunan ekonomi nasional & mendorong percepatan pengembangan sektor keuangan syariah, perlu memperkuat koordinasi, sinkronisasi & sinergi antara otoritas, kementerian/lembaga, & pemangku kepentingan lain di sektor keuangan syariah',
                ],
                'sort_order' => 1,
            ],
            [
                'year' => '2020',
                'sub_title'  => '',
                'title'=> 'Transformasi Menjadi KNEKS',
                'color'=> 'cyan',
                'icon' => 'refresh-cw',
                'items'=> [
                    'Peraturan Presiden No. 28 Tahun 2020 Tgl. 07/02/2020 tentang Komite Nasional Ekonomi & Keuangan Syariah',
                    'KNEKS adalah lembaga nonstruktural yang dipimpin oleh Presiden sebagai ketua dan Wakil Presiden sebagai Ketua Harian yang bertujuan meningkatkan pembangunan ekosistem ekonomi & keuangan syariah guna mendukung pembangunan ekonomi nasional',
                ],
                'sort_order' => 2,
            ],
            [
                'year' => '2021',
                'sub_title'  => '30 November',
                'title'=> 'Rapat Pleno I KNEKS',
                'color'=> 'blue',
                'icon' => 'users',
                'items'=> [
                    'Komite Daerah Ekonomi & Keuangan Syariah (KDEKS) ditetapkan sebagai salah satu dari 13 Program Prioritas oleh Ketua Harian KNEKS',
                ],
                'sort_order' => 3,
            ],
            [
                'year' => '2022',
                'sub_title'  => '30 Mei',
                'title'=> 'Rapat Pleno II KNEKS',
                'color'=> 'violet',
                'icon' => 'mic',
                'items'=> [
                    '"Kita akan membangun kelembagaannya (KNEKS) sampai ke daerah dengan membangun KDEKS di semua provinsi" Arahan Wakil Presiden RI selaku Ketua Harian KNEKS',
                ],
                'sort_order' => 4,
            ],
            [
                'year' => '2022',
                'sub_title'  => '20 Desember',
                'title'=> 'Rapat Pleno III KNEKS',
                'color'=> 'violet',
                'icon' => 'file-text',
                'items'=> [
                    '"Pengintegrasian rencana pengembangan ekonomi & keuangan syariah ke dalam rencana pembangunan nasional dan daerah, termasuk penyusunan Masterplan Ekonomi dan Keuangan Syariah Indonesia (MEKSI), sebagai kelanjutan Masterplan sebelumnya" Arahan Wakil Presiden RI selaku Ketua Harian KNEKS',
                ],
                'sort_order' => 5,
            ],
            [
                'year' => '2023',
                'sub_title'  => '04 Agustus',
                'title'=> 'Pengukuhan KDEKS Prov. Kaltim Periode 2023–2025',
                'color'=> 'emerald',
                'icon' => 'award',
                'items'=> [],
                'sort_order' => 6,
            ],
            [
                'year' => '2023',
                'sub_title'  => '25 September',
                'title'=> 'Dukungan Kemendagri – Ekonomi Syariah',
                'color'=> 'amber',
                'icon' => 'book-open',
                'items'=> [
                    'Peraturan Menteri Dalam Negeri No. 15 Tahun 2023 Tgl. 25/09/2023 tentang Pedoman Penyusunan Anggaran Pendapatan & Belanja Daerah Tahun Anggaran 2024',
                ],
                'sort_order' => 7,
            ],
            [
                'year' => '2024',
                'sub_title'  => '20 September',
                'title'=> 'Dukungan Kemendagri – Ekonomi Syariah',
                'color'=> 'amber',
                'icon' => 'book-open',
                'items'=> [
                    'Peraturan Menteri Dalam Negeri No. 15 Tahun 2023 Tgl. 20/09/2024 tentang Pedoman Penyusunan Anggaran Pendapatan & Belanja Daerah Tahun Anggaran 2025',
                ],
                'sort_order' => 8,
            ],
            [
                'year' => '2024',
                'sub_title'  => '',
                'title'=> 'Agenda Strategis Wapres Gibran',
                'color'=> 'rose',
                'icon' => 'book-marked',
                'items'=> [
                    '"Dalam buku setebal sekitar seratus halaman tersebut, dijelaskan berbagai agenda, termasuk penurunan prevalensi stunting, akselerasi pertumbuhan ekonomi dan keuangan syariah, pemberdayaan ekonomi pesantren, reformasi birokrasi, serta percepatan pembangunan dan peningkatan kesejahteraan di Papua. Buku ini menjadi panduan bagi Wapres Gibran untuk melanjutkan program-program strategis yang telah dicanangkan, dengan fokus pada perbaikan di berbagai sektor."',
                ],
                'sort_order' => 9,
            ],
        ];

        foreach ($milestones as $milestone) {
            Milestone::updateOrCreate(
                ['title' => $milestone['title'], 'year' => $milestone['year']],
                $milestone
            );
        }
    }
}
