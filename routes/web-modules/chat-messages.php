<?php

use App\Http\Controllers\ChatMessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('message')->name('.message')->group(function () {
    Route::get('/{chatRoom}', [ChatMessageController::class, 'index'])->name('.index');
    Route::post('/{chatRoom}', [ChatMessageController::class, 'store'])->name('.store');
});
