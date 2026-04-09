<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
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
use App\Http\Controllers\Admin\WatermarkSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController as UserProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/dashboard', '/admin/dashboard')->name('dashboard');
Route::post('/consultation', [HomeController::class, 'storeConsultation'])->name('consultation.store');
Route::post('/sehati-registration', [HomeController::class, 'storeSehatiRegistration'])->name('sehati.store');
Route::get('/search', SearchController::class)->name('search');
Route::get('/documents/{type}/{id}', [HomeController::class, 'downloadDocument'])->name('documents.download');
Route::get('/locale/{locale}', [HomeController::class, 'switchLocale'])->name('locale.switch');

Route::middleware(['auth', 'role:admin|editor|developer'])->prefix('admin')->as('admin.')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('site-settings', SiteSettingController::class)->except(['show']);
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
    Route::resource('consultation-requests', AdminConsultationRequestController::class)->except(['show']);
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
