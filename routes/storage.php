<?php

use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;


Route::prefix('/storage')->middleware('auth')->name('storage.')->group(function () {
    Route::get('/files', [StorageController::class, 'get_file'])->name('get-file');
});