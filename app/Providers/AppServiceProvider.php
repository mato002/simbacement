<?php

namespace App\Providers;

use App\Models\Setting;
use App\Support\DatabaseBootstrapper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        DatabaseBootstrapper::ensureReady();

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        View::composer(['layouts.public', 'partials.public.footer', 'public.home'], function ($view) {
            $view->with('siteCompany', Setting::group('company'));
            $view->with('siteSocial', Setting::group('social'));
            $view->with('siteSeo', Setting::group('seo'));
            $view->with('siteStats', Setting::group('stats'));
        });
    }
}
