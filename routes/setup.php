<?php

use App\Http\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

Route::get('/setup/migrate', [SetupController::class, 'migrate'])
    ->name('setup.migrate');
