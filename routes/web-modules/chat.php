<?php

use App\Http\Controllers\ChatRoomController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('chat')->name('chat')->group(function () {
    Route::prefix('room')->group(function () {
        Route::get('/{chatRoom}', [ChatRoomController::class, 'show'])->name('.show');

        Route::get('/{chatRoom}/message', [ChatRoomController::class, 'index'])->name('.index');
        Route::post('/{chatRoom}/message', [ChatRoomController::class, 'store'])->name('.store');
    });
});
