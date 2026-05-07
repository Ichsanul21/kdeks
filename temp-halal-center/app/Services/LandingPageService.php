<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Banner;
use App\Models\CertificationPath;
use App\Models\Event;
use App\Models\FrequentlyAskedQuestion;
use App\Models\GalleryItem;
use App\Models\KnowledgeResource;
use App\Models\HalalLocation;
use App\Models\LphPartner;
use App\Models\Mentor;
use App\Models\OrganizationMember;
use App\Models\PotentialItem;
use App\Models\ProgramSlide;
use App\Models\Region;
use App\Models\Regulation;
use App\Models\SectorItem;
use App\Models\SehatiRegistration;
use App\Models\SiteSetting;
use App\Models\Umkm;
use App\Models\UmkmProduk;

class LandingPageService
{
    public function getHomepageData(): array
    {
        $locations = Umkm::query()
            ->with(['region', 'lphPartner'])
            ->where('status', 'published')
            ->get();

        $halalLocations = HalalLocation::query()
            ->with(['region', 'lphPartner'])
            ->where('status', 'published')
            ->get();

        $mapLocations = $locations->concat($halalLocations->map(function ($loc) {
            $u = new Umkm([
                'nama_umkm' => $loc->name,
                'kategori' => $loc->category,
                'kab_kota' => $loc->city_name,
                'kecamatan' => $loc->kecamatan,
                'kelurahan' => $loc->kelurahan,
                'lph_partner_id' => $loc->lph_partner_id,
            ]);
            $u->setRelation('region', $loc->region);
            $u->setRelation('lphPartner', $loc->lphPartner);
            return $u;
        }));

        return [
            'setting' => SiteSetting::query()->first(),
            'banners' => Banner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'slides' => ProgramSlide::query()->where('status', 'published')->orderBy('sort_order')->get(),
            'regions' => Region::query()->orderBy('sort_order')->get(),
            'lphPartners' => LphPartner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'potentialItems' => PotentialItem::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'sectorItems' => SectorItem::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'featuredArticles' => Article::query()->where('status', 'published')->latest('published_at')->limit(4)->get(),
            'featuredProducts' => UmkmProduk::query()->with('umkm')->latest()->limit(6)->get(),
            'mentors' => Mentor::query()->with('region')->where('is_active', true)->limit(6)->get(),
            'paths' => CertificationPath::query()->orderBy('sort_order')->get(),
            'members' => OrganizationMember::query()->orderBy('sort_order')->get(),
            'resources' => KnowledgeResource::query()->latest('published_at')->limit(6)->get(),
            'regulations' => Regulation::query()->latest('issued_at')->limit(6)->get(),
            'events' => Event::query()->where('status', 'published')->orderBy('starts_at')->limit(4)->get(),
            'galleryItems' => GalleryItem::query()->latest('recorded_at')->limit(8)->get(),
            'faqs' => FrequentlyAskedQuestion::query()->orderBy('sort_order')->limit(8)->get(),
            'featuredLocations' => $mapLocations->take(24),
            'mapCities' => $mapLocations
                ->map(fn (Umkm $location) => $location->kab_kota ?: $location->region?->name)
                ->filter()
                ->unique()
                ->sort()
                ->values(),
            'mapCategories' => $mapLocations
                ->pluck('kategori')
                ->filter()
                ->unique()
                ->sort()
                ->values(),
            'mapKecamatans' => $mapLocations
                ->pluck('kecamatan')
                ->filter()
                ->unique()
                ->sort()
                ->values(),
            'mapKelurahans' => $mapLocations
                ->pluck('kelurahan')
                ->filter()
                ->unique()
                ->sort()
                ->values(),
            'statistics' => [
                'certificates_total' => 18490,
                'products_total' => 0,
                'assistants_total' => 5,
            ],
            'dashboard_data' => [
                'farmasi_total' => 44634,
                'rs_syariah_total' => 38,
                'klinik_total' => 1,
                'lab_medis_total' => 1,
                'rphr_total' => 8,
                'rphu_total' => 35,
                'umkm_ih_total' => 14231,
                'halal_industry' => [
                    'certificates' => 18490,
                    'reguler' => 1200,
                    'self_declare' => 17290,
                    'p3h' => 5,
                    'lph_kaltim' => 4,
                    'juru_sembelih' => 111,
                    'rph' => 8,
                    'rpu' => 35
                ],
                'sharia_finance' => [
                    'banks' => 11,
                    'bpr' => 1,
                    'insurance' => 6,
                    'multifinance' => 3,
                    'bmt' => 5,
                    'fintech' => 3,
                    'kspps' => 10
                ],
                'umkm_business' => [
                    'export_value' => 58772861,
                    'export_entities' => 31,
                    'tourism_destinations' => ['Samarinda', 'Balikpapan', 'Bontang'],
                    'incubation_programs' => [
                        'Pusat Inkubasi Bisnis Syariah (PINBAS) MUI',
                        'Talenta Wirausaha BSI Balikpapan',
                        'Pelatihan UMKM Potensial Ekspor Disperindagkop UMKM Kaltim',
                        'Program Kewirausahaan Terpadu (PKT) Disperindagkop UMKM Kaltim',
                        'Mitra Binaan Pupuk Kaltim',
                        'Program Inkubasi Bisnis Pesantren Kemenag RI Kaltim (30 Pesantren)'
                    ],
                    'regional_umkm' => [
                        'Samarinda' => 3319,
                        'Balikpapan' => 4104,
                        'Bontang' => 756,
                        'Kukar' => 3415,
                        'Kutim' => 726,
                        'Kubar' => 176,
                        'Mahulu' => 2,
                        'Berau' => 450,
                        'PPU' => 882,
                        'Paser' => 401
                    ]
                ]
            ],
            'latestSehatiRegistrations' => SehatiRegistration::query()->latest()->limit(5)->get(),
        ];
    }

    public function getStatisticsData()
    {
        $years = collect([2021, 2022, 2023, 2024, 2025]);

        // Quick Stats (Static)
        $sh_terbit = 18490;
        $umkm_count = 14231;
        $produk_count = 0; // Disabled
        $assistants_count = 9; // 4 LPH + 5 LP3H

        // Indikator Ekonomi Islam (Placeholder/Simulated based on context)
        $ekonomi_islam = [
            'labels' => ['UMKM Halal', 'Lembaga Keuangan Syariah', 'KSPPS', 'RPH/RPU Halal', 'Lembaga Pendamping', 'Juru Sembelih Halal'],
            'data' => [14231, 29, 10, 43, 9, 111]
        ];

        // Industri Produk Halal (Static)
        $sh_growth = [
            'years' => [2021, 2022, 2023, 2024, 2025],
            'values' => [224, 1253, 5957, 11836, 18490],
        ];

        // Regional Data (Static based on D.4)
        $regional_umkm = [
            'Samarinda' => 3319,
            'Balikpapan' => 4104,
            'Kukar' => 3415,
            'PPU' => 882,
            'Bontang' => 756,
            'Kutim' => 726,
            'Berau' => 450,
            'Paser' => 401,
            'Kubar' => 176,
            'Mahulu' => 2,
        ];

        $pariwisata_per_kota = [
            'labels' => array_keys($regional_umkm),
            'data' => array_values($regional_umkm)
        ];

        // Sebaran UMKM Halal
        $umkm_sebaran = [];
        foreach ($regional_umkm as $name => $val) {
            $umkm_sebaran[] = ['name' => $name, 'value' => $val];
        }

        // LPH & Auditor Perkembangan (Static based on A.3, A.4)
        $lph_growth = [
            'years' => [2021, 2022, 2023, 2024, 2025],
            'lph' => [1, 2, 3, 4, 4], // LPH Kaltim: 4
            'auditor' => [20, 45, 78, 95, 111] // Juru Sembelih: 111
        ];

        // Komposisi Jenis LPH
        $lph_komposisi = [
            'labels' => ['LPH', 'LP3H'],
            'data' => [4, 5] // LPH: 4, P3H: 5
        ];

        // RPH Growth (Static based on A.5, A.6)
        $rph_growth_data = [
            'years' => [2021, 2022, 2023, 2024, 2025],
            'values' => [12, 18, 25, 38, 43], // 8 RPH + 35 RPU = 43
        ];

        // Ponpes Growth (Static based on D.3)
        $ponpes_growth_data = [
            'years' => [2021, 2022, 2023, 2024, 2025],
            'values' => [10, 15, 22, 28, 30], // 30 Pesantren
        ];

        // Perkembangan Sertifikasi Halal UMKM
        $umkm_growth_data = [
            'years' => [2021, 2022, 2023, 2024, 2025],
            'values' => [4500, 7200, 9800, 12500, 14231],
        ];

        return [
            'stats' => [$sh_terbit, $umkm_count, $produk_count, $assistants_count],
            'ekonomi_islam' => $ekonomi_islam,
            'sh_growth' => $sh_growth,
            'pariwisata' => $pariwisata_per_kota,
            'umkm_sebaran' => $umkm_sebaran,
            'lph_auditor' => $lph_growth,
            'lph_komposisi' => $lph_komposisi,
            'rph_growth' => $rph_growth_data,
            'ponpes_growth' => $ponpes_growth_data,
            'umkm_perkembangan' => $umkm_growth_data,
            'umk_info' => [111, 9, '100%'] // Juru Sembelih, Lembaga Pendamping, 100%
        ];
    }
}
