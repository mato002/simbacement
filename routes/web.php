<?php

use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CorporatePageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SolutionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/about', [CorporatePageController::class, 'show'])
    ->defaults('slug', 'about')
    ->name('about');
Route::get('/manufacturing', [CorporatePageController::class, 'show'])
    ->defaults('slug', 'manufacturing')
    ->name('manufacturing');
Route::get('/quality', [CorporatePageController::class, 'show'])
    ->defaults('slug', 'quality')
    ->name('quality');
Route::get('/sustainability', [CorporatePageController::class, 'show'])
    ->defaults('slug', 'sustainability')
    ->name('sustainability');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/compare', [ProductController::class, 'compare'])->name('products.compare');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/solutions', [SolutionController::class, 'index'])->name('solutions.index');
Route::get('/solutions/{solution}', [SolutionController::class, 'show'])->name('solutions.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{article}', [NewsController::class, 'show'])->name('news.show');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{job}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{job}/apply', [CareerController::class, 'apply'])
    ->middleware('throttle:8,1')
    ->name('careers.apply');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::get('/quote', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/quote', [QuoteController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('quote.store');
Route::get('/quote/thanks/{quote}', [QuoteController::class, 'thanks'])->name('quote.thanks');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

