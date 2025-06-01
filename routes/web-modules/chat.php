<?php

use App\Http\Controllers\ChatRoomController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('chat')->name('chat')->group(function () {
    Route::get('/{friend}', [ChatRoomController::class, 'show'])->name('.show');

    Route::get('/messages/{friend}', [ChatRoomController::class, 'index'])->name('.index');
    Route::post('/messages/{friend}', [ChatRoomController::class, 'store'])->name('.store');
});
