<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->name('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('.index');
});
