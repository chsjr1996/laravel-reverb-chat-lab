<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'show'])->name('home');

Route::middleware(['auth', 'verified'])->group(callback: function () {
    require __DIR__.'/web-modules/chat.php';

    Route::get('dashboard', [DashboardController::class, 'show'])->name('dashboard');
});

require __DIR__.'/web-modules/settings.php';
require __DIR__.'/web-modules/auth.php';
