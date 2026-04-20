<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SektorSyariahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            [
                'title' => 'Industri Produk Halal',
                'slug' => 'industri-produk-halal',
                'icon_key' => 'package',
                'summary' => 'Pengembangan ekosistem industri produk halal daerah.',
                'content' => '<p>Direktorat Industri Produk Halal mempunyai tugas melaksanakan penyusunan rekomendasi, penyiapan, perumusan dan pelaksanaan koordinasi, serta pelaksanaan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang industri produk halal.</p>
                             <p>Dalam melaksanakan tugas, Direktorat Industri Produk Halal menyelenggarakan fungsi:</p>
                             <ol>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pengembangan halal assurance system;</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang infrastruktur industri halal; dan</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang rantai nilai produk dan jasa halal.</li>
                             </ol>
                             <p>Direktorat Industri Produk Halal terdiri atas:</p>
                             <ul>
                                <li><strong>Divisi Infrastruktur Industri Halal</strong></li>
                                <li><strong>Divisi Pengembangan Halal Assurance System</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pengembangan halal assurance system.</li>
                                <li><strong>Divisi Rantai Nilai Produk Halal</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang rantai nilai produk dan jasa halal.</li>
                             </ul>',
                'sort_order' => 1,
            ],
            [
                'title' => 'Jasa Keuangan Syariah',
                'slug' => 'jasa-keuangan-syariah',
                'icon_key' => 'landmark',
                'summary' => 'Akselerasi pertumbuhan sektor keuangan syariah.',
                'content' => '<p>Direktorat Jasa Keuangan Syariah mempunyai tugas melaksanakan penyusunan rekomendasi, penyiapan, perumusan dan pelaksanaan koordinasi, serta pelaksanaan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang jasa keuangan syariah.</p>
                             <p>Dalam melaksanakan tugas, Direktorat Jasa Keuangan Syariah menyelenggarakan fungsi:</p>
                             <ol>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang perbankan syariah;</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang jasa keuangan non-bank syariah; dan</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pasar modal syariah.</li>
                             </ol>
                             <p>Direktorat Jasa Keuangan Syariah terdiri atas:</p>
                             <ul>
                                <li><strong>Divisi Perbankan Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang perbankan syariah.</li>
                                <li><strong>Divisi Jasa Keuangan Non-Bank Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang jasa keuangan non-bank syariah.</li>
                                <li><strong>Divisi Pasar Modal Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pasar modal syariah.</li>
                             </ul>',
                'sort_order' => 2,
            ],
            [
                'title' => 'Keuangan Sosial Syariah',
                'slug' => 'keuangan-sosial-syariah',
                'icon_key' => 'heart',
                'summary' => 'Optimalisasi dana Ziswaf untuk kesejahteraan umat.',
                'content' => '<p>Direktorat Keuangan Sosial Syariah mempunyai tugas melaksanakan penyusunan rekomendasi, penyiapan, perumusan dan pelaksanaan koordinasi, serta pelaksanaan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang keuangan sosial syariah.</p>
                             <p>Dalam melaksanakan tugas, Direktorat Keuangan Sosial Syariah menyelenggarakan fungsi:</p>
                             <ol>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pengembangan instrumen Zakat, Infaq, Sedekah (ZIS) dan Dana Sosial Keagamaan Lainnya (DSKL);</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pengembangan wakaf; dan</li>
                                <li>Pemberian bimbingan teknis dan supervisi di bidang pengembangan keuangan sosial syariah.</li>
                             </ol>
                             <p>Direktorat Keuangan Sosial Syariah terdiri atas:</p>
                             <ul>
                                <li><strong>Divisi Pengembangan ZIS-DSKL</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang penguatan tata kelola, inovasi instrumen, digitalisasi serta optimalisasi pendayagunaan zakat, infaq, sedekah dan dana sosial keagamaan lainnya untuk pengentasan kemiskinan dan kesejahteraan masyarakat.</li>
                                <li><strong>Divisi Pengembangan Wakaf</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang transformasi wakaf uang, optimalisasi aset wakaf produktif, peningkatan transparansi dan akuntabilitas pengelolaan wakaf serta pengembangan instrumen wakaf untuk pembangunan berkelanjutan.</li>
                             </ul>',
                'sort_order' => 3,
            ],
            [
                'title' => 'Bisnis dan Kewirausahaan Syariah',
                'slug' => 'bisnis-kewirausahaan-syariah',
                'icon_key' => 'store',
                'summary' => 'Pembinaan usaha dan penguatan wirausaha halal yang kompetitif.',
                'content' => '<p>Direktorat Bisnis dan Kewirausahaan Syariah mempunyai tugas melaksanakan penyusunan rekomendasi, penyiapan, perumusan dan pelaksanaan koordinasi, serta pelaksanaan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang bisnis dan kewirausahaan syariah dan pengelolaan data nasional ekonomi dan keuangan syariah.</p>
                             <p>Dalam melaksanakan tugas, Direktorat Bisnis dan Kewirausahaan Syariah menyelenggarakan fungsi:</p>
                             <ol>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang kemitraan dan akselerasi usaha syariah;</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang inkubasi bisnis Syariah;</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang bisnis digital syariah; dan</li>
                                <li>Pengelolaan data nasional ekonomi dan keuangan syariah.</li>
                             </ol>
                             <p>Direktorat Bisnis dan Kewirausahaan Syariah terdiri atas:</p>
                             <ul>
                                <li><strong>Divisi Inkubasi Bisnis Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang inkubasi bisnis syariah.</li>
                                <li><strong>Divisi Kemitraan dan Akselerasi Usaha Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang kemitraan dan akselerasi usaha syariah.</li>
                                <li><strong>Divisi Bisnis Digital dan Pusat Data Ekonomi Syariah</strong>, yang mempunyai tugas melakukan koordinasi, pengembangan, sinkronisasi, integrasi kebijakan, program dan ekosistem bisnis digital syariah serta pengelolaan data nasional ekonomi syariah.</li>
                             </ul>',
                'sort_order' => 4,
            ],
            [
                'title' => 'Infrastruktur Ekosistem Syariah',
                'slug' => 'infrastruktur-ekosistem-syariah',
                'icon_key' => 'network',
                'summary' => 'Penyediaan sarana pendukung ekosistem halal.',
                'content' => '<p>Direktorat Infrastruktur Ekosistem Syariah mempunyai tugas melaksanakan penyusunan rekomendasi, penyiapan, perumusan dan pelaksanaan koordinasi, serta pelaksanaan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang infrastruktur ekosistem syariah.</p>
                             <p>Dalam melaksanakan tugas, Direktorat Infrastruktur Ekosistem Syariah menyelenggarakan fungsi:</p>
                             <ol>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang hukum pengembangan ekonomi dan keuangan syariah.</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang promosi dan kerja sama strategis ekonomi dan keuangan syariah;</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pengembangan sumber daya manusia ekonomi dan keuangan syariah; dan</li>
                                <li>Penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang riset ekonomi dan keuangan syariah.</li>
                             </ol>
                             <p>Direktorat Infrastruktur Ekosistem Syariah terdiri atas:</p>
                             <ul>
                                <li><strong>Divisi Pengembangan Hukum Ekonomi Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang hukum pengembangan ekonomi dan keuangan syariah.</li>
                                <li><strong>Divisi Promosi dan Kerja Sama Strategis</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang promosi dan kerja sama strategis ekonomi dan keuangan syariah.</li>
                                <li><strong>Divisi Riset Ekonomi Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang riset ekonomi dan keuangan syariah.</li>
                                <li><strong>Divisi Pengembangan SDM Ekonomi Syariah</strong>, yang mempunyai tugas melakukan penyiapan bahan rumusan rekomendasi, bahan koordinasi perumusan dan pelaksanaan, serta bahan pemantauan dan evaluasi arah kebijakan dan program strategis di bidang pengembangan sumber daya manusia ekonomi.</li>
                             </ul>',
                'sort_order' => 5,
            ],
        ];

        foreach ($sectors as $sector) {
            \App\Models\SectorItem::updateOrCreate(
                ['slug' => $sector['slug']],
                $sector + ['is_active' => true]
            );
        }
    }
}
