<?php

use App\Http\Controllers\ChatRoomController;
use Illuminate\Support\Facades\Route;

Route::prefix('room')->name('.room')->group(function () {
    Route::get('/', [ChatRoomController::class, 'index'])->name('.index');
    Route::get('/{chatRoom}', [ChatRoomController::class, 'show'])->name('.show');
});
