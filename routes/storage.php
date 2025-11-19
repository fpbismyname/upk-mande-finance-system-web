<?php

use App\Http\Controllers\StorageController;
use App\Http\Middleware\StorageMiddleware;
use Illuminate\Support\Facades\Route;


Route::prefix('/storage')->middleware([StorageMiddleware::class])->name('storage.')->group(function () {
    Route::get('/files', [StorageController::class, 'get_file'])->name('get-file');
});