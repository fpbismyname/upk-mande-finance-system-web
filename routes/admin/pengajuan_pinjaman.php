<?php

use App\Http\Controllers\Admin\PengajuanPinjaman\Action\SubmitReviewPengajuanPinjaman;
use App\Http\Controllers\Admin\PengajuanPinjaman\ListPengajuanPinjaman;
use App\Http\Controllers\Admin\PengajuanPinjaman\ReviewPengajuanPinjaman;

Route::prefix('/pengajuan-pinjaman')->name('pengajuan-pinjaman.')->group(function () {
    // View routes
    Route::get('/', ListPengajuanPinjaman::class)->name('index');
    Route::get('/{id}/review', ReviewPengajuanPinjaman::class)->name('review');
    // Action routes
    Route::put('/{id}/submit-review', SubmitReviewPengajuanPinjaman::class)->name('submit-review');
});