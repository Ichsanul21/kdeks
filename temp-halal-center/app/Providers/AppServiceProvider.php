<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\SehatiRegistration;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $view->with('locale', session('site_locale', 'id'));
            $view->with('setting', Schema::hasTable('site_settings') ? SiteSetting::query()->first() : null);
            $view->with('lphPartners', Schema::hasTable('lph_partners') ? \App\Models\LphPartner::where('is_active', true)->orderBy('sort_order')->get() : collect());
            $view->with(
                'adminNewSehatiCount',
                Schema::hasTable('sehati_registrations')
                    ? SehatiRegistration::query()->where('status', 'new')->count()
                    : 0
            );
        });
    }
}
