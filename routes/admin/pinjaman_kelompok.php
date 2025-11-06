<?php


use App\Http\Controllers\Admin\CicilanKelompok\ListCicilanKelompok;
use App\Http\Controllers\Admin\PinjamanKelompok\ListPinjamanKelompok;
use App\Http\Controllers\Admin\PinjamanKelompok\ViewPinjamanKelompok;
use Illuminate\Support\Facades\Route;

/**
 *  Pinjaman kelompok routes
 */

Route::prefix('/pinjaman-kelompok')->name('pinjaman-kelompok.')->group(function () {
    // View routes
    Route::get('/', ListPinjamanKelompok::class)->name('index');
    Route::get('/{id_pinjaman}/view', ViewPinjamanKelompok::class)->name('view');

    /**
     *  Cicilan kelompok routes
     */
    Route::prefix("/{id_pinjaman}/cicilan")->name('cicilan.')->group(function () {
        // View routes
        Route::get("/{id_cicilan}/view", ListPinjamanKelompok::class)->name("view");
    });
});