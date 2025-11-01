<?php

use App\Http\Controllers\Admin\Pendanaan\Action\TarikSaldoPendanaan;
use App\Http\Controllers\Admin\Pendanaan\Action\TopupPendanaan;
use App\Http\Controllers\Admin\Pendanaan\ListPendanaan;

Route::prefix('/pendanaan')->name('pendanaan.')->group(function () {
    // Pendanaan View
    Route::get("/", ListPendanaan::class)->name('index');

    // Pendanaan Action
    Route::post("/topup", TopupPendanaan::class)->name('topup');
    Route::post("/tarik-dana", TarikSaldoPendanaan::class)->name('tarik-saldo');
});
