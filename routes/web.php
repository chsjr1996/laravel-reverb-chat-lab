<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('chat')->name('chat')->group(function () {
    require __DIR__.'/web-modules/chat-rooms.php';
    require __DIR__.'/web-modules/chat-messages.php';
});

require __DIR__.'/web-modules/settings.php';
require __DIR__.'/web-modules/auth.php';
