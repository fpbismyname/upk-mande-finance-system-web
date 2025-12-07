<?php

use App\Http\Controllers\StorageController;
use App\Http\Middleware\StorageMiddleware;
use Illuminate\Support\Facades\Route;


Route::prefix('/storage')->name('storage.')->group(function () {
    Route::middleware([StorageMiddleware::class])->group(function () {
        Route::get('/private', [StorageController::class, 'get_private_file'])->name('private.get');
    });
    Route::get('/public', [StorageController::class, 'get_public_file'])->name('public.get');
});