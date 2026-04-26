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
}
