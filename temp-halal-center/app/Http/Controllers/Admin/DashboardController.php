<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ConsultationRequest;
use App\Models\Event;
use App\Models\KnowledgeResource;
use App\Models\LphPartner;
use App\Models\Mentor;
use App\Models\PotentialItem;
use App\Models\PressRelease;
use App\Models\ProgramSlide;
use App\Models\Region;
use App\Models\Regulation;
use App\Models\SectorItem;
use App\Models\SehatiRegistration;
use App\Models\SiteSetting;
use App\Models\Umkm;
use App\Models\UmkmProduk;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $isEditor = $user->hasRole('editor') && !$user->hasAnyRole(['developer', 'superadmin']);

        $latestSehatiRegistrations = $isEditor ? collect() : SehatiRegistration::query()
            ->with('lphPartner')
            ->latest()
            ->limit(5)
            ->get();

        $latestConsultations = ConsultationRequest::query()
            ->latest()
            ->limit(5)
            ->get();

        $topRegions = $isEditor ? collect() : Region::query()
            ->orderByDesc('halal_msmes_count')
            ->limit(5)
            ->get();

        $stats = [
            'slides' => ProgramSlide::count(),
            'articles' => Article::count(),
            'resources' => KnowledgeResource::count(),
            'regulations' => Regulation::count(),
            'events' => Event::count(),
            'consultations' => ConsultationRequest::count(),
            'banners' => \App\Models\Banner::count(),
            'faqs' => \App\Models\FrequentlyAskedQuestion::count(),
            'press_releases' => PressRelease::count(),
        ];

        if (!$isEditor) {
            $stats['regions'] = Region::count();
            $stats['umkms'] = Umkm::count();
            $stats['products'] = UmkmProduk::count();
            $stats['mentors'] = Mentor::count();
            $stats['lph_partners'] = LphPartner::count();
            $stats['potential_items'] = PotentialItem::count();
            $stats['sector_items'] = SectorItem::count();
            $stats['sehati'] = SehatiRegistration::count();
            $stats['sehati_pending'] = SehatiRegistration::query()->where('status', 'baru')->count();
        }

        return view('admin.dashboard.index', [
            'stats' => $stats,
            'latestSehatiRegistrations' => $latestSehatiRegistrations,
            'latestConsultations' => $latestConsultations,
            'topRegions' => $topRegions,
            'siteSetting' => SiteSetting::first(),
            'isEditor' => $isEditor,
        ]);
    }

}
