<?php

use App\Http\Controllers\Admin\AnggotaKelompok\ViewAnggotaKelompok;
use App\Http\Controllers\Admin\Kelompok\Action\AddKelompok;
use App\Http\Controllers\Admin\Kelompok\Action\DeleteKelompok;
use App\Http\Controllers\Admin\Kelompok\Action\UpdateKelompok;
use App\Http\Controllers\Admin\Kelompok\CreateKelompok;
use App\Http\Controllers\Admin\Kelompok\EditKelompok;
use App\Http\Controllers\Admin\Kelompok\ViewKelompok;
use App\Http\Controllers\Admin\Kelompok\ListKelompok;
use Illuminate\Support\Facades\Route;

/**
 *  Kelompoks routes
 */

Route::prefix('/kelompok')->name('kelompok.')->group(function () {
    // View routes
    Route::get('/', ListKelompok::class)->name('index');
    Route::get('/create', CreateKelompok::class)->name('create');
    Route::get('/{id_kelompok}/view', ViewKelompok::class)->name('view');
    Route::get('/{id_kelompok}/edit', EditKelompok::class)->name('edit');
    // Action routes
    Route::post('/add', AddKelompok::class)->name('add');
    Route::put('/{id_kelompok}/update', UpdateKelompok::class)->name('update');
    Route::delete('/{id_kelompok}/destroy', DeleteKelompok::class)->name('destroy');

    /**
     *  Anggota kelompok routes
     */
    Route::prefix("/{id_kelompok}/anggota")->name('anggota.')->group(function () {
        // View routes
        Route::get("/{id_anggota}/view", ViewAnggotaKelompok::class)->name("view");
    });
});