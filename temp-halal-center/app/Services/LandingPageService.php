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
                'certificates_total' => Umkm::count(),
                'products_total' => UmkmProduk::count(),
                'assistants_total' => LphPartner::where('is_active', true)->count(),
            ],
            'dashboard_data' => [
                'farmasi_total' => \App\Models\HalalProduct::where('category', 'like', '%Farmasi%')->count() ?: 44634,
                'rs_syariah_total' => \App\Models\HalalLocation::where('category', 'like', '%Rumah Sakit%')->count() ?: 38,
                'klinik_total' => \App\Models\HalalLocation::where('category', 'like', '%Klinik%')->count() ?: 1,
                'lab_medis_total' => \App\Models\HalalLocation::where('category', 'like', '%Lab%')->count() ?: 1,
                'rphr_total' => \App\Models\HalalLocation::where('category', 'RPHR')->count() ?: 594,
                'rphu_total' => \App\Models\HalalLocation::where('category', 'RPHU')->count() ?: 362,
                'umkm_ih_total' => \App\Models\Umkm::where('kategori', 'Industri Halal')->count() ?: 14114,
            ],
            'latestSehatiRegistrations' => SehatiRegistration::query()->latest()->limit(5)->get(),
        ];
    }

    public function getStatisticsData()
    {
        $years = collect(range(now()->year - 5, now()->year));

        // Quick Stats
        $sh_terbit = UmkmProduk::count() + Umkm::count();
        $umkm_count = Umkm::count();
        $produk_count = UmkmProduk::count();
        $assistants_count = LphPartner::count();

        // Indikator Ekonomi Islam (Sorted Desc)
        $ekonomi_islam_raw = collect([
            'Makanan Halal' => Umkm::where('kategori', 'like', '%Makanan%')->count(),
            'Wisata Ramah Muslim' => Umkm::where('kategori', 'like', '%Wisata%')->count(),
            'Pondok Pesantren' => Umkm::where('kategori', 'like', '%Ponpes%')->count(),
            'Minuman Halal' => Umkm::where('kategori', 'like', '%Minuman%')->count(),
            'Perbankan Syariah' => Umkm::where('kategori', 'like', '%Perbankan%')->count(),
            'Produk Halal Lainnya' => Umkm::where('kategori', 'like', '%Produk Halal%')->count(),
        ])->sortDesc();

        $ekonomi_islam = [
            'labels' => $ekonomi_islam_raw->keys()->toArray(),
            'short' => $ekonomi_islam_raw->keys()->map(fn($l) => explode(' ', $l)[0])->toArray(),
            'data' => $ekonomi_islam_raw->values()->toArray()
        ];

        // Industri Produk Halal (SH Terbit over years)
        $sh_growth = $years->map(fn($y) => UmkmProduk::where('tahun_terbit', $y)->count() + Umkm::whereYear('created_at', $y)->count());

        // Regional Data (from Region table)
        $regions = Region::orderBy('name')->get();
        $pariwisata_per_kota = [
            'labels' => $regions->pluck('name')->toArray(),
            'data' => $regions->pluck('issued_certificate_count')->toArray()
        ];

        // Sebaran UMKM Halal (Pie Chart - per city from Region table)
        $umkm_sebaran = $regions->map(fn($r) => ['name' => $r->name, 'value' => $r->halal_msmes_count]);

        // LPH & Auditor Perkembangan (Auditor from Mentor table)
        $lph_growth = $years->map(fn($y) => LphPartner::whereYear('created_at', '<=', $y)->count());
        $auditor_growth = $years->map(fn($y) => Mentor::whereYear('created_at', '<=', $y)->count());

        // Komposisi Jenis LPH (LPH vs LP3H)
        $lph_komposisi = [
            'labels' => ['LPH', 'LP3H'],
            'data' => [
                LphPartner::where('partner_type', 'lph')->count(),
                LphPartner::where('partner_type', 'lp3h')->count(),
            ]
        ];

        // RPH Growth (HalalLocation + Umkm with RPH category)
        $rph_growth = $years->map(function($y) {
            $locs = HalalLocation::where('category', 'like', '%Rumah Potong%')->whereYear('created_at', '<=', $y)->count();
            $umkms = Umkm::where('kategori', 'like', '%Rumah Potong%')->whereYear('created_at', '<=', $y)->count();
            return $locs + $umkms;
        });

        // Ponpes Growth
        $ponpes_growth = $years->map(fn($y) => Umkm::where('kategori', 'like', '%Ponpes%')->whereYear('created_at', '<=', $y)->count());

        // Perkembangan Sertifikasi Halal UMKM per Tahun
        $umkm_growth = $years->map(fn($y) => Umkm::whereYear('created_at', '<=', $y)->count());

        return [
            'stats' => [$sh_terbit, $umkm_count, $produk_count, $assistants_count],
            'ekonomi_islam' => $ekonomi_islam,
            'sh_growth' => [
                'years' => $years->toArray(),
                'values' => $sh_growth->toArray(),
            ],
            'pariwisata' => $pariwisata_per_kota,
            'umkm_sebaran' => $umkm_sebaran->toArray(),
            'lph_auditor' => [
                'years' => $years->toArray(),
                'lph' => $lph_growth->toArray(),
                'auditor' => $auditor_growth->toArray(),
            ],
            'lph_komposisi' => $lph_komposisi,
            'rph_growth' => [
                'years' => $years->toArray(),
                'values' => $rph_growth->toArray(),
            ],
            'ponpes_growth' => [
                'years' => $years->toArray(),
                'values' => $ponpes_growth->toArray(),
            ],
            'umkm_perkembangan' => [
                'years' => $years->toArray(),
                'values' => $umkm_growth->toArray(),
            ],
            'umk_info' => [
                $assistants_count,
                LphPartner::where('partner_type', 'lp3h')->count(),
                '100%' // Placeholder for percentage
            ]
        ];
    }
}
