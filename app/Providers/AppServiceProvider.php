<?php

namespace App\Providers;

use App\Enums\JobApplicationStatus;
use App\Enums\MessageStatus;
use App\Enums\QuoteStatus;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\QuoteRequest;
use App\Models\Setting;
use App\Support\DatabaseBootstrapper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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

        View::composer(['layouts.admin', 'partials.admin.header'], function ($view) {
            $alerts = [
                'new_quotes' => 0,
                'new_messages' => 0,
                'new_applications' => 0,
            ];

            try {
                $alerts = [
                    'new_quotes' => QuoteRequest::query()->where('status', QuoteStatus::New)->count(),
                    'new_messages' => ContactMessage::query()->where('status', MessageStatus::New)->count(),
                    'new_applications' => JobApplication::query()->where('status', JobApplicationStatus::Received)->count(),
                ];
            } catch (Throwable) {
                // Keep zeros if tables are unavailable during bootstrap.
            }

            $view->with('adminAlerts', $alerts);
            $view->with('adminAttentionCount', array_sum($alerts));
        });
    }
}
