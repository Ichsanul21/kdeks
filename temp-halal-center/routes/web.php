<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CertificationPathController;
use App\Http\Controllers\Admin\ConsultationRequestController as AdminConsultationRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FrequentlyAskedQuestionController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\HalalLocationController;
use App\Http\Controllers\Admin\HalalProductController;
use App\Http\Controllers\Admin\KnowledgeResourceController;
use App\Http\Controllers\Admin\LphPartnerController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\OrganizationMemberController;
use App\Http\Controllers\Admin\PotentialItemController;
use App\Http\Controllers\Admin\ProgramSlideController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RegulationController;
use App\Http\Controllers\Admin\SectorItemController;
use App\Http\Controllers\Admin\SehatiRegistrationController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\UmkmProdukController;
use App\Http\Controllers\Admin\WatermarkSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController as UserProfileController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/dashboard', '/admin/dashboard')->name('dashboard');
Route::post('/consultation', [HomeController::class, 'storeConsultation'])->name('consultation.store');
Route::post('/sehati-registration', [HomeController::class, 'storeSehatiRegistration'])->name('sehati.store');
Route::get('/search', SearchController::class)->name('search');
Route::get('/documents/{type}/{id}', [HomeController::class, 'downloadDocument'])->name('documents.download');
Route::get('/documents/{type}/{id}/view', [HomeController::class, 'viewDocument'])->name('documents.view');
Route::get('/locale/{locale}', [HomeController::class, 'switchLocale'])->name('locale.switch');
Route::get('/articles', [PublicContentController::class, 'articlesIndex'])->name('articles.index');
Route::get('/articles/{slug}', [PublicContentController::class, 'articleShow'])->name('articles.show');
Route::get('/gallery', [PublicContentController::class, 'galleryIndex'])->name('gallery.index');
Route::get('/products', [PublicContentController::class, 'productsIndex'])->name('products.index');
Route::get('/products/{slug}', [PublicContentController::class, 'productShow'])->name('products.show');
Route::get('/data', [PublicContentController::class, 'dataIndex'])->name('data.index');
Route::get('/locations/{slug}', [PublicContentController::class, 'locationShow'])->name('locations.show');
Route::get('/resources', [PublicContentController::class, 'resourcesIndex'])->name('resources.index');
Route::get('/resources/{slug}', [PublicContentController::class, 'resourceShow'])->name('resources.show');
Route::get('/regulations', [PublicContentController::class, 'regulationsIndex'])->name('regulations.index');
Route::get('/regulations/{slug}', [PublicContentController::class, 'regulationShow'])->name('regulations.show');
Route::get('/events', [PublicContentController::class, 'eventsIndex'])->name('events.index');
Route::get('/events/{slug}', [PublicContentController::class, 'eventShow'])->name('events.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/direktorat/{slug}', [PublicContentController::class, 'direktoratShow'])->name('direktorat.show');

Route::get('/siaran-pers', function () {
    return view('public.siaran-pers-placeholder');
})->name('siaran-pers');

Route::get('/profile/tentang-kami', [PublicContentController::class, 'about'])->name('about');

Route::get('/profile/struktur-organisasi', [PublicContentController::class, 'organizationStructure'])->name('profile.organization');
Route::get('/profile/anggota/{id}', [PublicContentController::class, 'memberShow'])->name('profile.member');

Route::get('/halaman/{slug}', function ($slug) {
    $titles = [
        'struktur-organisasi' => 'Struktur Organisasi',
        'industri-produk-halal' => 'Industri Produk Halal',
        'jasa-keuangan-syariah' => 'Jasa Keuangan Syariah',
        'keuangan-sosial-syariah' => 'Keuangan Sosial Syariah',
        'bisnis-kewirausahaan-syariah' => 'Bisnis dan Kewirausahaan Syariah',
        'infrastruktur-ekosistem-syariah' => 'Infrastruktur Ekosistem Syariah',
    ];
    $title = $titles[$slug] ?? ucwords(str_replace('-', ' ', $slug));
    return view('public.placeholder', compact('title'));
})->name('page.placeholder');

Route::middleware(['auth', 'role:admin|editor|developer'])->prefix('admin')->as('admin.')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('site-settings', SiteSettingController::class)->except(['show']);
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::resource('regions', RegionController::class)->except(['show']);
    Route::resource('lph-partners', LphPartnerController::class)->except(['show']);
    Route::resource('program-slides', ProgramSlideController::class)->except(['show']);
    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::resource('halal-locations', HalalLocationController::class)->except(['show']);
    Route::resource('potential-items', PotentialItemController::class)->except(['show']);
    Route::resource('sector-items', SectorItemController::class)->except(['show']);
    Route::resource('organization-members', OrganizationMemberController::class)->except(['show']);
    Route::resource('certification-paths', CertificationPathController::class)->except(['show']);
    Route::resource('halal-products', HalalProductController::class)->except(['show']);
    Route::resource('mentors', MentorController::class)->except(['show']);
    Route::resource('knowledge-resources', KnowledgeResourceController::class)->except(['show']);
    Route::resource('regulations', RegulationController::class)->except(['show']);
    Route::resource('events', AdminEventController::class)->except(['show']);
    Route::resource('gallery-items', GalleryItemController::class)->except(['show']);
    Route::resource('frequently-asked-questions', FrequentlyAskedQuestionController::class)->except(['show']);
    Route::resource('sehati-registrations', SehatiRegistrationController::class)->except(['show']);
    Route::get('about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'edit'])->name('about-us.index');
    Route::put('about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'update'])->name('about-us.update');
    Route::resource('milestones', \App\Http\Controllers\Admin\MilestoneController::class)->except(['show']);
    Route::post('sehati-registrations/{sehati_registration}/approve', [SehatiRegistrationController::class, 'approve'])->name('sehati-registrations.approve');
    Route::resource('consultation-requests', AdminConsultationRequestController::class)->except(['show']);
    Route::post('umkms/import', [UmkmController::class, 'import'])->name('umkms.import');
    Route::get('umkms/export', [UmkmController::class, 'export'])->name('umkms.export');
    Route::resource('umkms', UmkmController::class)->except(['show']);
    Route::get('umkms/{umkm}/produks/create', [UmkmProdukController::class, 'create'])->name('umkms.produks.create');
    Route::post('umkms/{umkm}/produks', [UmkmProdukController::class, 'store'])->name('umkms.produks.store');
    Route::get('umkms/{umkm}/produks/{produk}/edit', [UmkmProdukController::class, 'edit'])->name('umkms.produks.edit');
    Route::put('umkms/{umkm}/produks/{produk}', [UmkmProdukController::class, 'update'])->name('umkms.produks.update');
    Route::delete('umkms/{umkm}/produks/{produk}', [UmkmProdukController::class, 'destroy'])->name('umkms.produks.destroy');
});

Route::middleware(['auth', 'role:developer'])->prefix('admin')->as('admin.')->group(function (): void {
    Route::get('/watermark-settings', [WatermarkSettingController::class, 'edit'])->name('watermark-settings.edit');
    Route::put('/watermark-settings', [WatermarkSettingController::class, 'update'])->name('watermark-settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
