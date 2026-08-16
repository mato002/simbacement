<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobListingController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsArticleController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\QuoteRequestController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SolutionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware(['auth', 'staff'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::redirect('dashboard', '/admin')->name('dashboard.redirect');

        Route::middleware('permission:products.view')->group(function () {
            Route::resource('products', ProductController::class)->except(['show']);
            Route::resource('categories', ProductCategoryController::class)->except(['show']);
        });

        Route::middleware('permission:media.view')->group(function () {
            Route::get('media', [MediaController::class, 'index'])->name('media.index');
            Route::post('media', [MediaController::class, 'store'])
                ->middleware('permission:media.create')
                ->name('media.store');
            Route::delete('media/{mediaAsset}', [MediaController::class, 'destroy'])
                ->middleware('permission:media.delete')
                ->name('media.destroy');
        });

        Route::middleware('permission:quotes.view')->group(function () {
            Route::get('quotes', [QuoteRequestController::class, 'index'])->name('quotes.index');
            Route::get('quotes/{quote}', [QuoteRequestController::class, 'show'])->name('quotes.show');
            Route::patch('quotes/{quote}', [QuoteRequestController::class, 'update'])
                ->middleware('permission:quotes.edit')
                ->name('quotes.update');
            Route::delete('quotes/{quote}', [QuoteRequestController::class, 'destroy'])
                ->middleware('permission:quotes.edit')
                ->name('quotes.destroy');
        });

        Route::middleware('permission:content.view')->group(function () {
            Route::resource('solutions', SolutionController::class)->except(['show']);
            Route::resource('news', NewsArticleController::class)->except(['show']);
            Route::resource('pages', AdminPageController::class)->except(['show']);
        });

        Route::middleware('permission:projects.view')->group(function () {
            Route::resource('projects', ProjectController::class)->except(['show']);
        });

        Route::middleware('permission:messages.view')->group(function () {
            Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
            Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
            Route::patch('messages/{message}', [ContactMessageController::class, 'update'])
                ->middleware('permission:messages.edit')
                ->name('messages.update');
            Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])
                ->middleware('permission:messages.edit')
                ->name('messages.destroy');
        });

        Route::middleware('permission:careers.view')->group(function () {
            Route::resource('jobs', JobListingController::class)->except(['show']);
            Route::get('applications', [JobApplicationController::class, 'index'])->name('applications.index');
            Route::get('applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
            Route::patch('applications/{application}', [JobApplicationController::class, 'update'])
                ->middleware('permission:careers.edit')
                ->name('applications.update');
            Route::delete('applications/{application}', [JobApplicationController::class, 'destroy'])
                ->middleware('permission:careers.edit')
                ->name('applications.destroy');
            Route::get('applications/{application}/cv', [JobApplicationController::class, 'downloadCv'])
                ->name('applications.cv');
        });

        Route::middleware('permission:locations.view')->group(function () {
            Route::resource('locations', LocationController::class)->except(['show']);
        });

        Route::middleware('permission:settings.view')->group(function () {
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])
                ->middleware('permission:settings.edit')
                ->name('settings.update');
        });
    });
});

