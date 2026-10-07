<?php

return [
    'default_instructions' => 'isi mulai baris 7; jangan ubah kolom induk di baris 6; pisahkan indikator volume/frekuensi dari nominal rupiah; sub-item setipe dikonsolidasikan (unified) dalam satu tabel',

    'sub_modules' => [
        'Industri Produk Halal' => [
            'anggota_mukisi' => [
                'title' => 'Jumlah Anggota MUKISI',
                'columns' => ['Tahun', 'Jumlah Anggota MUKISI', 'Keterangan'],
            ],
            'daya_saing_halal' => [
                'title' => 'Daya Saing Industri Produk Halal (%)',
                'columns' => ['Tahun', 'Daya Saing Industri Produk Halal (%)', 'Keterangan'],
            ],
            'faskes_syariah' => [
                'title' => 'Faskes Bersertifikat Syariah (RS/Klinik/Lab)',
                'columns' => ['Tahun', 'Jenis Faskes', 'Jumlah Bersertifikat', 'Keterangan'],
            ],
            'farmasi_halal' => [
                'title' => 'Produk Farmasi Bersertifikat Halal',
                'columns' => ['Tahun', 'Jumlah Produk Farmasi Halal', 'Keterangan'],
            ],
            'halal_value_chain' => [
                'title' => 'Sektor Unggulan Halal Value Chain',
                'columns' => ['Tahun', 'Sektor Unggulan', 'Nilai Output (Rp Miliar)', 'Keterangan'],
            ],
            'hotel_halal' => [
                'title' => 'Hotel Bersertifikat Halal',
                'columns' => ['Tahun', 'Jumlah Hotel Halal', 'Kota/Kabupaten', 'Keterangan'],
            ],
            'industri_halal_sarana' => [
                'title' => 'Industri Halal (RPHR/RPHU/Cold Storage/Kios/Usaha Daging)',
                'columns' => ['Tahun', 'Jenis Sarana', 'Jumlah Unit', 'Keterangan'],
            ],
            'kawasan_industri_halal' => [
                'title' => 'Kawasan Industri Halal',
                'columns' => ['Tahun', 'Nama Kawasan', 'Lokasi', 'Luas (Ha)', 'Status'],
            ],
            'kodifikasi_data_produk' => [
                'title' => 'Kodifikasi Data Industri Produk Halal',
                'columns' => ['Tahun', 'Kode Produk', 'Kategori Produk', 'Jumlah Produk', 'Keterangan'],
            ],
            'lembaga_pemeriksa_halal' => [
                'title' => 'Lembaga Pemeriksa Halal',
                'columns' => ['Tahun', 'Nama LPH', 'Jumlah Auditor', 'Status Akreditasi', 'Keterangan'],
            ],
            'modul_umkm_halal' => [
                'title' => 'Implementasi Modul UMKM Industri Halal',
                'columns' => ['Tahun', 'Nama Modul / Program', 'Jumlah UMKM Terlibat', 'Keterangan'],
            ],
            'pelaku_rph_halal' => [
                'title' => 'RPH Halal - Jumlah Pelaku Usaha',
                'columns' => ['Tahun', 'Kabupaten/Kota', 'Jumlah Pelaku Usaha RPH', 'Keterangan'],
            ],
            'restoran_halal' => [
                'title' => 'Restoran Bersertifikat Halal',
                'columns' => ['Tahun', 'Jumlah Restoran Halal', 'Kota/Kabupaten', 'Keterangan'],
            ],
            'sebaran_rph_halal' => [
                'title' => 'RPH Halal per Kota/Kabupaten',
                'columns' => ['Kota/Kabupaten', 'Jumlah RPH Halal', 'Kapasitas (Ekor/Hari)', 'Keterangan'],
            ],
            'zona_khas' => [
                'title' => 'Zona Khas (Kuliner Halal/Aman/Sehat)',
                'columns' => ['Tahun', 'Nama Zona KHAS', 'Lokasi', 'Jumlah Tenant/Pedagang', 'Keterangan'],
            ],
        ],

        'Jasa Keuangan Syariah' => [
            'aset_perbankan' => [
                'title' => 'Perkembangan Perbankan Prov. Kaltim 5 Tahun Terakhir - Nilai Aset',
                'columns' => ['Tahun', 'Nilai Aset (Rp Miliar)', 'Pertumbuhan (%)', 'Keterangan'],
            ],
            'dpk_perbankan' => [
                'title' => 'Perkembangan Perbankan Prov. Kaltim 5 Tahun Terakhir - Dana Pihak Ketiga',
                'columns' => ['Tahun', 'Dana Pihak Ketiga (Rp Miliar)', 'Pertumbuhan (%)', 'Keterangan'],
            ],
            'entitas_perbankan' => [
                'title' => 'Perkembangan Perbankan Prov. Kaltim 5 Tahun Terakhir - Jumlah Entitas',
                'columns' => ['Tahun', 'Bank Syariah (BUS/UUS)', 'BPRS', 'Total Entitas', 'Keterangan'],
            ],
            'pembiayaan_perbankan' => [
                'title' => 'Perkembangan Perbankan Prov. Kaltim 5 Tahun Terakhir - Kredit/Pembiayaan',
                'columns' => ['Tahun', 'Total Pembiayaan (Rp Miliar)', 'Pertumbuhan (%)', 'Keterangan'],
            ],
            'iknb_syariah' => [
                'title' => 'Perkembangan IKNB Prov. Kaltim 5 Tahun Terakhir',
                'columns' => ['Tahun', 'Asuransi Syariah (Rp Miliar)', 'Penjaminan Syariah (Rp Miliar)', 'LKM Syariah / Pegadaian (Rp Miliar)', 'Keterangan'],
            ],
            'marketshare_aset_keuangan' => [
                'title' => 'Marketshare Aset Keuangan Syariah/PDB (%)',
                'columns' => ['Tahun', 'Aset Keuangan Syariah (Rp Miliar)', 'PDB / PDRB (Rp Miliar)', 'Marketshare (%)'],
            ],
            'pasar_modal_syariah' => [
                'title' => 'Perkembangan Pasar Modal Syariah Prov. Kaltim 5 Tahun Terakhir',
                'columns' => ['Tahun', 'Jumlah Investor Syariah (SID)', 'Nilai Transaksi (Rp Miliar)', 'Jumlah Saham Syariah / Sukuk', 'Keterangan'],
            ],
        ],

        'Keuangan Sosial Syariah' => [
            'aset_wakaf_uang' => [
                'title' => 'Aset Wakaf Uang/PDB (%)',
                'columns' => ['Tahun', 'Aset Wakaf Uang (Rp Miliar)', 'PDB / PDRB (Rp Miliar)', 'Rasio terhadap PDB (%)'],
            ],
            'lembaga_keuangan_syariah_penerima_wakaf_uang' => [
                'title' => 'Wakaf Uang - LKS Penerima Wakaf Uang',
                'columns' => ['Tahun', 'Nama LKS-PWU', 'Jumlah Penghimpunan (Rp Miliar)', 'Keterangan'],
            ],
            'nazhir_wakaf_uang' => [
                'title' => 'Wakaf Uang - Jumlah Nazhir Berizin Resmi',
                'columns' => ['Tahun', 'Nama Nazhir', 'Status Izin BWI', 'Wilayah Kerja', 'Keterangan'],
            ],
            'peruntukan_wakaf_lahan' => [
                'title' => 'Wakaf Tanah - Penggunaan/Peruntukan Lahan Wakaf',
                'columns' => ['Tahun', 'Peruntukan Lahan', 'Luas Lahan (m2)', 'Jumlah Titik', 'Keterangan'],
            ],
            'wakaf_tanah_sebaran' => [
                'title' => 'Wakaf Tanah - Sebaran Lokasi, Titik, dan Total Luas Lahan per Wilayah',
                'columns' => ['Kota/Kabupaten', 'Jumlah Titik Lokasi', 'Total Luas (m2)', 'Keterangan'],
            ],
            'wakaf_tanah_status' => [
                'title' => 'Wakaf Tanah - Jumlah Bidang & Luas Lahan Berdasarkan Status Sertifikat',
                'columns' => ['Status Sertifikat', 'Jumlah Bidang', 'Luas Lahan (m2)', 'Keterangan'],
            ],
            'jaringan_bzm_bwm' => [
                'title' => 'Pendanaan Sosial Syariah - Jaringan BZM & BWM',
                'columns' => ['Tahun', 'Nama BZM / BWM', 'Lokasi / Kabupaten Kota', 'Jumlah Nasabah / Penerima', 'Keterangan'],
            ],
            'pendanaan_umkm_ziswaf' => [
                'title' => 'Pendanaan Sosial Syariah UMKM - Penyaluran Dana & Penerima Manfaat',
                'columns' => ['Tahun', 'Jumlah Penyaluran Dana (Rp)', 'Jumlah UMKM Penerima Manfaat', 'Keterangan'],
            ],
            'rasio_zis' => [
                'title' => 'Rasio ZIS & Dana Sosial Keagamaan Lainnya/PDB (%)',
                'columns' => ['Tahun', 'Pengumpulan ZIS & DSKL (Rp Miliar)', 'PDB / PDRB (Rp Miliar)', 'Rasio (%)'],
            ],
            'statistik_wilayah_zis' => [
                'title' => 'Statistik Wilayah Pengumpulan/Penyaluran/Operasional/Mustahik & Muzzaki (Baznas & LAZ)',
                'columns' => ['Kota/Kabupaten', 'Lembaga (BAZNAS/LAZ)', 'Pengumpulan (Rp)', 'Penyaluran (Rp)', 'Jumlah Muzakki', 'Jumlah Mustahik'],
            ],
        ],

        'Bisnis & Kewirausahaan Syariah' => [
            'kontribusi_per_sektor' => [
                'title' => 'Kontribusi Per Sektor Produk Halal terhadap Ekspor',
                'columns' => ['Tahun', 'Sektor Produk Halal', 'Nilai Ekspor (Rp Miliar)', 'Kontribusi Ekspor (%)'],
            ],
            'logistik_halal' => [
                'title' => 'Logistik Halal/Pelabuhan - Jumlah Sertifikasi Jasa Logistik',
                'columns' => ['Tahun', 'Nama Penyedia Jasa Logistik / Pelabuhan', 'Jumlah Sertifikasi Halal', 'Keterangan'],
            ],
            'rasio_ekspor_halal' => [
                'title' => 'Nilai Ekspor Halal/PDB (%) atau PDRB (%)',
                'columns' => ['Tahun', 'Nilai Ekspor Halal (Rp Miliar)', 'PDB / PDRB (Rp Miliar)', 'Rasio (%)'],
            ],
        ],

        'Infrastruktur Ekosistem Syariah' => [
            'indeks_literasi_dan_inklusi' => [
                'title' => 'Literasi Ekonomi & Keuangan Syariah Daerah',
                'columns' => ['Tahun', 'Indeks Literasi Ekonomi Syariah (%)', 'Indeks Inklusi Keuangan Syariah (%)', 'Indeks Literasi Keuangan Syariah (%)'],
            ],
            'kolaborasi_layanan_agen' => [
                'title' => 'Jumlah Lembaga & Agen Layanan Keuangan Syariah',
                'columns' => ['Tahun', 'Lembaga Keuangan Syariah', 'Jumlah Agen Aktif', 'Keterangan'],
            ],
            'kurikulum_kampus_pks_mou' => [
                'title' => 'SDM Ekonomi Syariah - Implementasi Kurikulum Perguruan Tinggi (PKS & MoU)',
                'columns' => ['Tahun', 'Nama Perguruan Tinggi', 'Bentuk Kerjasama (PKS/MoU)', 'Status Kurikulum', 'Keterangan'],
            ],
            'sekolah_pelopor_eksyar' => [
                'title' => 'SDM Ekonomi Syariah - Lembaga Pendidikan Menengah (SMA/SMK Pelopor)',
                'columns' => ['Tahun', 'Nama Sekolah (SMA/SMK)', 'Kabupaten/Kota', 'Jumlah Siswa/Peserta Program', 'Keterangan'],
            ],
            'pendamping_pph_dan_lph_lp3h' => [
                'title' => 'SDM Sertifikasi - Pendamping PPH Aktif & Lembaga Pendamping (LPH/LP3H)',
                'columns' => ['Tahun', 'Jumlah Pendamping PPH Aktif', 'Jumlah Lembaga Pendamping (LP3H)', 'Jumlah LPH', 'Keterangan'],
            ],
            'transaksi_volume_dan_nominal' => [
                'title' => 'Statistik Transaksi Layanan Keuangan Syariah Kemitraan',
                'columns' => ['Tahun', 'Volume Transaksi (Frekuensi)', 'Nominal Transaksi (Rp Miliar)', 'Keterangan'],
            ],
            'tren_sertifikasi_halal' => [
                'title' => 'Jumlah Produk Tersertifikasi Halal (Total/Reguler/Self-Declare)',
                'columns' => ['Tahun', 'Jalur Reguler', 'Jalur Self-Declare', 'Total Produk Halal'],
            ],
        ],
    ],
];
