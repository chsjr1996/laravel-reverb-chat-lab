<?php

use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ChatRoomController;
use Illuminate\Support\Facades\Route;

Route::prefix('chat')->name('chat')->group(function () {
    Route::prefix('room')->name('.room')->group(function () {
        Route::post('/', [ChatRoomController::class, 'store'])->name('.store');
        Route::get('/', [ChatRoomController::class, 'index'])->name('.index');
        Route::get('/{chatRoom}', [ChatRoomController::class, 'show'])->name('.show');
        Route::get('/user/{user}', [ChatRoomController::class, 'edit'])->name('.new.user.edit');
        Route::post('/user/{user}', [ChatRoomController::class, 'store'])->name('.new.user.store');
    });

    Route::prefix('message')->name('.message')->group(function () {
        Route::get('/{chatRoom}', [ChatMessageController::class, 'index'])->name('.index');
        Route::post('/{chatRoom?}', [ChatMessageController::class, 'store'])->name('.store');
    });
});
