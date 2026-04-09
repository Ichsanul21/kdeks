<?php

namespace App\Services;

use App\Models\Article;
use App\Models\CertificationPath;
use App\Models\Event;
use App\Models\FrequentlyAskedQuestion;
use App\Models\GalleryItem;
use App\Models\HalalLocation;
use App\Models\HalalProduct;
use App\Models\KnowledgeResource;
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

class LandingPageService
{
    public function getHomepageData(): array
    {
        $mapLocations = HalalLocation::query()
            ->with(['region', 'lphPartner'])
            ->where('status', 'published')
            ->get();

        return [
            'setting' => SiteSetting::query()->first(),
            'slides' => ProgramSlide::query()->where('status', 'published')->orderBy('sort_order')->get(),
            'regions' => Region::query()->orderBy('sort_order')->get(),
            'lphPartners' => LphPartner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'potentialItems' => PotentialItem::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'sectorItems' => SectorItem::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'featuredArticles' => Article::query()->where('status', 'published')->latest('published_at')->limit(4)->get(),
            'featuredProducts' => HalalProduct::query()->with('region')->where('status', 'published')->latest()->limit(6)->get(),
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
                ->map(fn (HalalLocation $location) => $location->city_name ?: $location->region?->name)
                ->filter()
                ->unique()
                ->sort()
                ->values(),
            'mapCategories' => [
                'Makanan',
                'Minuman',
                'Wisata Ramah',
                'Unit Usaha Ponpes',
                'Produk Halal Lainnya',
                'Rumah Potong',
                'Industri Kreatif',
                'Perbankan Syariah',
                'Lembaga Keuangan',
            ],
            'statistics' => [
                'certificates_total' => HalalLocation::whereNotNull('certificate_number')->count(),
                'products_total' => HalalProduct::where('status', 'published')->count(),
                'assistants_total' => Mentor::where('is_active', true)->count(),
            ],
            'latestSehatiRegistrations' => SehatiRegistration::query()->latest()->limit(5)->get(),
        ];
    }
}
