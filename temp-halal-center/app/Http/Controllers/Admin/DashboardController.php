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
        $newSehatiRegistrations = SehatiRegistration::query()
            ->with('lphPartner')
            ->where('status', 'new')
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard.index', [
            'stats' => [
                'slides' => ProgramSlide::count(),
                'articles' => Article::count(),
                'regions' => Region::count(),
                'locations' => Umkm::count(),
                'products' => UmkmProduk::count(),
                'mentors' => Mentor::count(),
                'lph_partners' => LphPartner::count(),
                'potential_items' => PotentialItem::count(),
                'sector_items' => SectorItem::count(),
                'resources' => KnowledgeResource::count(),
                'regulations' => Regulation::count(),
                'events' => Event::count(),
                'sehati' => SehatiRegistration::count(),
                'sehati_pending' => SehatiRegistration::query()->where('status', 'new')->count(),
                'consultations' => ConsultationRequest::count(),
            ],
            'latestSehatiRegistrations' => $newSehatiRegistrations,
            'latestConsultations' => ConsultationRequest::latest()->limit(6)->get(),
            'siteSetting' => SiteSetting::first(),
        ]);
    }
}
