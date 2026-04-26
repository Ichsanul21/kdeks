<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultationRequestStoreRequest;
use App\Http\Requests\SehatiRegistrationStoreRequest;
use App\Models\Article;
use App\Models\ConsultationRequest;
use App\Models\KnowledgeResource;
use App\Models\Regulation;
use App\Models\SehatiRegistration;
use App\Services\LandingPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected LandingPageService $landingPageService)
    {
    }

    public function index(): View
    {
        $data = $this->landingPageService->getHomepageData();

        return view('home.index', array_merge($data, [
            'locale' => session('site_locale', $data['setting']?->default_locale ?? 'id'),
        ]));
    }

    public function switchLocale(string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['id', 'en'], true), 404);

        session(['site_locale' => $locale]);

        return back();
    }

    public function storeConsultation(ConsultationRequestStoreRequest $request): RedirectResponse
    {
        ConsultationRequest::create($request->validated() + [
            'status' => $request->validated()['status'] ?? 'new',
        ]);

        return redirect()->route('home')->with('status', 'Permintaan konsultasi berhasil dikirim.');
    }

    public function storeSehatiRegistration(SehatiRegistrationStoreRequest $request): RedirectResponse
    {
        SehatiRegistration::create($request->validated() + [
            'status' => 'new',
        ]);

        return redirect()->route('home')->with('status', 'Pendaftaran SEHATI berhasil dikirim.');
    }

    public function downloadDocument(string $type, int $id)
    {
        $map = [
            'resource' => KnowledgeResource::class,
            'regulation' => Regulation::class,
            'article' => Article::class,
        ];

        abort_unless(isset($map[$type]), 404);

        $record = $map[$type]::query()->findOrFail($id);
        abort_unless($record->document_path, 404);

        return Storage::disk('private')->download($record->document_path);
    }

    public function viewDocument(string $type, int $id)
    {
        $map = [
            'resource' => KnowledgeResource::class,
            'regulation' => Regulation::class,
            'article' => Article::class,
        ];

        abort_unless(isset($map[$type]), 404);

        $record = $map[$type]::query()->findOrFail($id);
        abort_unless($record->document_path, 404);

        if (!Storage::disk('private')->exists($record->document_path)) {
            abort(404);
        }

        return Storage::disk('private')->response($record->document_path);
    }
}
